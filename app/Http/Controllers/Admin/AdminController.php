<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Session;
use DB;
use Auth;
use Image;
use File;
use Input;
use PDF;
use Response;
use Cache;

class AdminController extends Controller
{
    public function welcomeAdmin()
    {
        $countUsers = DB::table('users')
                        ->count('users.id');
        $users = DB::table('users')
                        ->select('users.id')
                        ->get();
        $countOnlineUser = 0;
        foreach ($users as $user) {
            if($user){
                if(Cache::has('user-online-'.$user->id)) {
                    $countOnlineUser++; 
                }
            }
        }
//        $countOrganization = DB::table('organizations')
//                                ->count('organizations.id');
        $countImporters = DB::table('vatregs')
                            ->count('vatregs.id');
        return view('default.admin.welcome', compact('countUsers', 'countOnlineUser', 'countImporters'));
    }


    public function budgetEntryForm()
    {
        return view('default.admin.budget-entry-form');
    }


    //----------------------------------------Budget------------------------

    public function getSubHeadList(Request $r){
       // $results = array();
       // $results[] = ['value' => $r->term];

       // return $in_ex!='[]'?'j':'y';


        $term =$r->term;//Input::get('term');
        $results = array();
        $queries = DB::table('acc_sub_head as sh')
            ->join('acc_head as h','h.id','sh.head_id')
            ->where('sh.acc_sub_head', 'LIKE', $term.'%')
            //->where('h.in_ex_status',$in_ex)
            ->select('sh.acc_sub_head','sh.id','h.acc_head')
            // ->orWhere('last_name', 'LIKE', '%'.$term.'%')
            ->take(10)->get();

       // dd($queries[0]->id);
        if(!$queries){
            $results[] =['value' => 'no'];
        }
        else{
            foreach ($queries as $query)
            {
                $results[] = ['value' => $query->acc_sub_head,'desc' => $query->acc_head, 'subhead_id' => $query->id];
            }
        }

        return json_encode($results);
    }



    public function getAllBudgetData()
    {
        $limits = DB::select('SELECT ie.id,ie.subhead_id,ie.monthly_yearly_flag,ie.fiscal_year,ie.amount,ie.created_at,ie.created_by,ie.updated_at,ie.updated_by,sh.acc_sub_head AS sub_head_name 
                              FROM budget_in_ex AS ie
                             LEFT JOIN acc_sub_head AS sh ON sh.id=ie.subhead_id ORDER BY ie.id DESC');

        return json_encode($limits);

    }

    public function saveBudgetData(Request $req)
    {
        if ($req->id) {

            DB::table('budget_in_ex')
                ->where('id', $req->id)
                ->update([
                    'subhead_id' => $req->subhead_id,
                    'fiscal_year' => $req->fiscal_year,
                    'amount' => $req->amount,
                    'monthly_yearly_flag' => $req->monthly_yearly_flag,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            return Response::json(['k' => 'k'], 201);

        } else {

            $budget_exist = DB::select('SELECT * FROM budget_in_ex bg 
                                WHERE bg.subhead_id=? 
                                AND bg.fiscal_year=? 
                                AND bg.monthly_yearly_flag=?',[$req->subhead_id,$req->fiscal_year,$req->monthly_yearly_flag]);

            if (!$budget_exist) {
              //  return;
                DB::table('budget_in_ex')->insert(
                    [
                        'subhead_id' => $req->subhead_id,
                        'fiscal_year' => $req->fiscal_year,
                        'amount' => $req->amount,
                        'monthly_yearly_flag' => $req->monthly_yearly_flag,
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]
                );
                return Response::json(['k' => 'k'], 200);
            } else {
                return Response::json(['k' => 'k'], 401);

            }


        }

    }

    public function deleteBudgetData($id)
    {
        DB::table('budget_in_ex')->where('id', $id)->delete();

        return Response::json(['k' => 'k'], 200);
    }

    //==================================================Bonus & Increment===============================================//


}
