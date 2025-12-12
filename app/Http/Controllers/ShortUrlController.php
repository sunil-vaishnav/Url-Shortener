<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use App\Models\Company;
use Illuminate\Support\Facades\URL;

class ShortUrlController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = auth()->user();

        if(!$request->ajax()){
            return view('shorturl.index');
        }else{
            $columnArray = [
                'sno' => 'id',
                'company'=>'company',
                'created_by'=>'created_by',
                'original_url'=>'original_url',
                'short_code'=>'short_code',
                'created_at'=>'created_at',
                'action' => 'action',
            ];

            $draw = (int) $request->draw && $request->draw > 0  ? $request->draw : 1;
            $start = $request->start;
            $limit = $request->length;
            if($start == 0){
                $page = 0;
            }else{
                $page = $start / $limit;
            }

            $offset = $page * $limit;
            $column_list = $request->columns;
            //Order by
            $columnId = $request->order[0]['column'];
            $columnDir = $request->order[0]['dir'];
            $columnKey = $column_list[$columnId]['data'];
            $orderBy = $columnArray[$columnKey];
            $orderByDir = $columnDir;

            //filter conditions
            $conditions = [];
            

            $shortUrlObj = ShortUrl::with('user','company');
            if (in_array($currentUser->userRole->name, ['Admin','Member'])) {
                $shortUrlObj = $shortUrlObj->where('created_by',$currentUser->id);
            }

            foreach ($column_list as $key => $column) {
                $colId = $column['data'];
                $field_name = $columnArray[$colId];
                //only searchable and filter field with value
                if($column['searchable'] == true && !empty($column['search']['value']))
                {
                    $conditions[] = [$field_name, 'like', '%' . trim($column['search']['value']) . '%' ];
                }
            }


            $totalShortUrls = $shortUrlObj->where($conditions)->select('short_urls.*')->count();
            $shortUrlObj = $shortUrlObj->where($conditions)->orderBy('created_at', 'desc') ;
            if ($request->length != -1) {
                $shortUrls = $shortUrlObj->take($limit)->skip($offset)->select('short_urls.*')->get();
            }else{
                $shortUrls = $shortUrlObj->select('short_urls.*')->get();
            }

            $data = array();

            foreach ($shortUrls as $key => $shortUrl) {
                //prd($user);
                $edit_url = URL::to('/shorturls/edit').'/'.$shortUrl->id;
                $delete_url = URL::to('/shorturls/delete').'/'.$shortUrl->id;

                $action = "<a href='".$edit_url."'><span class='badge text-bg-success'>Edit </span></a>
                        <form action='".$delete_url."' method='POST' class='d-inline' 
                          onsubmit=\"return confirm('Are you sure delete this short url?');\">
                        ".csrf_field()."
                        ".method_field('DELETE')."
                        <button type='submit' class='badge text-bg-danger border-0'>Delete</button>
                    </form>";

                $data[$key]['sno'] = $page * $limit + $key + 1;
                $data[$key]['company'] = $shortUrl->company->name;
                $data[$key]['created_by'] = $shortUrl->user->name;
                $data[$key]['original_url'] = $shortUrl->original_url;
                $data[$key]['short_code'] = $shortUrl->short_code;
                $data[$key]['created_at'] = date('d-m-Y',strtotime($shortUrl->created_at)) ;
                $data[$key]['action'] = $action;

            }



            $jsonData = [];
            $jsonData['draw'] = $draw;
            $jsonData['recordsTotal'] = $totalShortUrls;
            $jsonData['recordsFiltered'] = $totalShortUrls;
            $jsonData['data'] = $data;

            echo json_encode($jsonData);
            exit;
        }
    }

    public function add(Request $request)
    {
        //prd($request->all());
        if ($request->isMethod('post')) {
            $request->validate([
                'company_id' => 'required',
                'original_url' => 'required',
                'short_code' => 'required',
            ]);

            $shortUrl = new ShortUrl();
            $shortUrl->company_id  = $request->company_id ;
            $shortUrl->created_by = auth()->user()->id ;
            $shortUrl->original_url = $request->original_url ; 
            $shortUrl->short_code  = $request->short_code ;
            $shortUrl->save();

            $request->session()->flash('success', 'Short url create successfully.');
            return redirect('/shorturls');
        }

        $company = auth()->user()->company;
        return view('shorturl.add',['company' => $company]);
    }

    public function edit(Request $request ,$id)
    {
        $shorturl = ShortUrl::where(['id'=>$id,'created_by' => auth()->user()->id])->first();
        if (empty($shorturl)) {
            $request->session()->flash('error', 'ShortUrl does not exist may be id missing or you are not owner this short url.');
            return redirect('/shorturls');
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'company_id' => 'required',
                'original_url' => 'required',
                'short_code' => 'required',
            ]);

            $shorturl->company_id  = $request->company_id ;
            $shorturl->original_url = $request->original_url ; 
            $shorturl->short_code  = $request->short_code ;
            $shorturl->update();

            $request->session()->flash('success', 'ShortUrl updated successfully.');
            return redirect('/shorturls');
        }

        return view('shorturl.edit',['shorturl' =>$shorturl ]);
    }

    public function delete(Request $request ,$id){
        $shortUrl = ShortUrl::where(['id'=>$id,'created_by' => auth()->user()->id])->first();
        if (empty($shortUrl)) {
            $request->session()->flash('error', 'ShortUrl does not exist may be id missing or you are not owner this short url.');
            return redirect('/shorturls');
        }

        $shortUrl->delete();
        return redirect('/shorturls');
    }

    public function resolve(Request $request,$code){
        if(empty($code)){
            $request->session()->flash('error', 'Shorturl code not exist.');
            return redirect('/login');
        }

        $shorturl = ShortUrl::where('short_code', $code)->firstOrFail();
        if (empty($shorturl)) {
            $request->session()->flash('error', 'Shorturl not found.');
            return redirect('/login');
        }

        return redirect($shorturl->original_url);
    }
}
