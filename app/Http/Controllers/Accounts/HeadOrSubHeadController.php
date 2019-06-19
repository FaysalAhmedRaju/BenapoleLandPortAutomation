<?php

namespace App\Http\Controllers\Accounts;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;

class HeadOrSubHeadController extends Controller
{
    public function createHeadOrSubHead() {
    	return view('default.accounts.create-head-or-sub-head');
    }

    public function saveHead(Request $r) {
    	$postHead = DB::table('acc_head')
    					->insert([
    						'acc_head' => $r->acc_head,
                            'in_ex_status' => $r->in_ex_status
    						]);
    	if($postHead == true) {
    		return "Success";
    	}
    }

    public function getHead() {
    	$getHead = DB::table('acc_head')
    					->get();
    	return json_encode($getHead);
    }

    public function editHead(Request $r) {
    	$editHead = DB::table('acc_head')
    					->where('id', $r->id)
    					->update([
    						'acc_head' => $r->acc_head,
                            'in_ex_status' => $r->in_ex_status

    						]);
    	if($editHead == true) {
    		return "Successfully Edited";
    	}
    }

    public function deleteHead($id) {
    	$checkSubhead = DB::table('acc_sub_head')
    						->where('head_id', $id)
    						->get();
    	if(count($checkSubhead)) {
    		return "subHeadExist";
    	} else {
	    	$deleteHead = DB::table('acc_head')
	    					->where('id', $id)
	    					->delete();
	    	if($deleteHead == true) {
	    		return "Deleted";
	    	}
	    }
    }
    //========================SUB HEAD=================================
    public function saveSubHeadData(Request $r) {
    	$postSubHead = DB::table('acc_sub_head')
    						->insert([
    							'head_id' => $r->head_id,
    							'acc_sub_head' => $r->acc_sub_head
    							]);
    	if($postSubHead == true) {
    		return "Success";
    	}
    }

    public function getSubHead($head_id) {
    	$getSubHead = DB::table('acc_sub_head')
    						->where('head_id', $head_id)
    						->get();
    	return json_encode($getSubHead);
    }

    public function editSubHeadData(Request $r) {
    	$editSubHead = DB::table('acc_sub_head')
    					->where('id',$r->id)
    					->update([
    						'head_id' => $r->head_id,
    						'acc_sub_head' => $r->acc_sub_head
    						]);
    	if($editSubHead == true) {
    		return "Successfully Edited";
    	}
    }

    public function deleteSubHead($id) {
    	$deleteSubHead = DB::table('acc_sub_head')
    					->where('id', $id)
    					->delete();
    	if($deleteSubHead == true) {
    		return "Deleted";
    	}
    }
}
