<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\URL;

class InvitationController extends Controller
{
    public function inviteAdmin(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'company_id' => 'required',
                'name' => 'required',
                'email' => 'required',
            ]);

            $user = new User();
            $user->name = $request->name ;
            $user->email = $request->email ;
            $user->role = 2 ; // 2 => admin
            $user->company_id = $request->company_id ;
            $user->password = bcrypt('password123');
            $user->save();

            if ($request->hasSession()) {
                $request->session()->flash('success', 'Admin invite successfully.');
            }
            //$request->session()->flash('success', 'Admin invite successfully.');
            return redirect('/invitations');
        }

        $companies = Company::get();
        return view('invitation.admin_invite',['companies' => $companies]);
    }

    public function index(Request $request)
    {
        $currentUser = auth()->user();
        if ($currentUser->userRole->name === 'Member') {
            return redirect('/login');
        }

        if(!$request->ajax()){
            return view('invitation.index');
        }else{
            $columnArray = [
                'sno' => 'id',
                'name'=>'name',
                'email'=>'email',
                'role'=>'role',
                'company'=>'company',
                'created_at'=>'created_at',
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
            

            if ($currentUser->userRole->name === 'SuperAdmin') {
                $userObj = User::with('userRole','company')
                    ->whereHas('userRole', function($q){
                        $q->whereIn('name', ['Admin','Member']);
                });

            }else if ($currentUser->userRole->name === 'Admin') {
                $userObj = User::with('userRole','company')
                    ->whereHas('userRole', function($q){
                        $q->where('name', 'Member');
                });
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


            $totalUsers = $userObj->where($conditions)->select('users.*')->count();
            $userObj = $userObj->where($conditions)->orderBy('created_at', 'desc') ;
            if ($request->length != -1) {
                $users = $userObj->take($limit)->skip($offset)->select('users.*')->get();
            }else{
                $users = $userObj->select('users.*')->get();
            }

            $data = array();

            foreach ($users as $key => $user) {
                

                $data[$key]['sno'] = $page * $limit + $key + 1;
                $data[$key]['name'] = $user->name;
                $data[$key]['email'] = $user->email;
                $data[$key]['role'] = $user->userRole->name;
                $data[$key]['company'] = $user->company->name;
                $data[$key]['created_at'] = date('d-m-Y',strtotime($user->created_at)) ;

            }



            $jsonData = [];
            $jsonData['draw'] = $draw;
            $jsonData['recordsTotal'] = $totalUsers;
            $jsonData['recordsFiltered'] = $totalUsers;
            $jsonData['data'] = $data;

            echo json_encode($jsonData);
            exit;
        }
    }

    public function inviteMember(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
            ]);

            $currentUser = auth()->user();

            $user = new User();
            $user->name = $request->name ;
            $user->email = $request->email ;
            $user->role = 3 ; // 2 => member
            $user->company_id = $currentUser->company_id ;
            $user->password = bcrypt('password123');
            $user->save();

            if ($request->hasSession()) {
                $request->session()->flash('success', 'Member invite successfully.');
            }
            //$request->session()->flash('success', 'Member invite successfully.');
            return redirect('/invitations');
        }

        $companies = Company::get();
        return view('invitation.member_invite');
    }
}
