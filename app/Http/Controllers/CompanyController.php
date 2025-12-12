<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\URL;

class CompanyController extends Controller
{

    public function index(Request $request)
    {
        if(!$request->ajax()){
            return view('companies.index');
        }else{
            $columnArray = [
                'sno' => 'id',
                'name'=>'name',
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
            $companyObj = new Company();
            foreach ($column_list as $key => $column) {
                $colId = $column['data'];
                $field_name = $columnArray[$colId];
                //only searchable and filter field with value
                if($column['searchable'] == true && !empty($column['search']['value']))
                {
                    $conditions[] = [$field_name, 'like', '%' . trim($column['search']['value']) . '%' ];
                }
            }

            $totalCompanies = $companyObj->where($conditions)->select('companies.*')->count();
            $companyObj = $companyObj->where($conditions)->orderBy('created_at', 'desc') ;
            if ($request->length != -1) {
                $companies = $companyObj->take($limit)->skip($offset)->select('companies.*')->get();
            }else{
                $companies = $companyObj->select('companies.*')->get();
            }

            $data = array();

            foreach ($companies as $key => $company) {

                $edit_url = URL::to('/companies/').'/edit/'.$company->id;
                $delete_url = URL::to('/companies/').'/destroy/'.$company->id;

                $action = "<a href='".$edit_url."'><span class='badge text-bg-success'>Edit </span></a>
                    <form action='".$delete_url."' method='POST' class='d-inline' 
                          onsubmit=\"return confirm('Are you sure delete this company?');\">
                        ".csrf_field()."
                        ".method_field('DELETE')."
                        <button type='submit' class='badge text-bg-danger border-0'>Delete</button>
                    </form>";

                $data[$key]['sno'] = $page * $limit + $key + 1;
                $data[$key]['name'] = $company->name;
                $data[$key]['created_at'] = date('d-m-Y',strtotime($company->created_at)) ;
                $data[$key]['action'] = $action;

            }



            $jsonData = [];
            $jsonData['draw'] = $draw;
            $jsonData['recordsTotal'] = $totalCompanies;
            $jsonData['recordsFiltered'] = $totalCompanies;
            $jsonData['data'] = $data;

            echo json_encode($jsonData);
            exit;
        }
    }

 
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required'
            ]);

            $company = new Company();
            $company->name = $request->name ;
            $company->save();

            if ($request->hasSession()) {
                $request->session()->flash('success', 'Company created successfully.');
            }
            //$request->session()->flash('success', 'Company create successfully.');
            return redirect('/companies');
        }

        return view('companies.create');
    }


    public function edit(Request $request ,$id)
    {
        $company = Company::where('id',$id)->first();
        if (empty($company)) {
            $request->session()->flash('error', 'Company id does not exist.');
            return redirect('/companies');
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required'
            ]);

            $company->name = $request->name ;
            $company->update();

            $request->session()->flash('success', 'Company edit successfully.');
            return redirect('/companies');
        }

        return view('companies.edit',['company' =>$company ]);
    }

    public function destroy(Request $request ,$id){
        $company = Company::where('id',$id)->first();
        if (empty($company)) {
            $request->session()->flash('error', 'Company id does not exist.');
            return redirect('/companies');
        }

        $company->delete();
        return redirect('/companies');
    }
}
