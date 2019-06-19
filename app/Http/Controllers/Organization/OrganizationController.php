<?php

namespace App\Http\Controllers\Organization;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use Auth;

class OrganizationController extends Controller
{
    public function organizationEntryForm() {
    	return view('default.organization.organization-entry-form');
    }

    public function getOrgTypeData() {
//    	$organizations = DB::table('org_types')
//                        ->where('id', '!=', 1)
//                        ->Where('id', '!=', 2)
//                        ->get();
//    	return json_encode($organizations);
    }

    public function getPortDetails() {
    	$ports = DB::table('ports')->get();
    	return json_encode($ports);
    }

    public function getAllOrganization() {
//    	$allOrganization = DB::table('organizations')
//    						->join('org_types', 'org_types.id', '=','organizations.org_type_id')
//                            ->join('ports', 'ports.id', '=','organizations.port_id')
//                            ->select('organizations.*','org_types.org_type','ports.port_name')
//                            ->get();
//        return json_encode($allOrganization);
    }

    public function saveOrganizationData(Request $r) {
        //return $r;
//        $createdBy = Auth::user()->name;
//        $createdTime = date('Y-m-d H:i:s');
//    	$insertOrganization = DB::table('organizations')
//    						->insert([
//    								'org_type_id' => $r->org_type_id,
//    								'org_name' => $r->org_name,
//                                    'add1' => $r->add1,
//                                    'add2' => $r->add2,
//                                    'port_id' => $r->port_id,
//                                    'propriter_name' => $r->propriter_name,
//                                    'phone' => $r->phone,
//                                    'mobile' => $r->mobile,
//                                    'email' => $r->email,
//                                    'created_by' => $createdBy,
//                                    'create_datetime' => $createdTime
//									]);
//        if($insertOrganization == true) {
//            return 'Success';
//        }
    }

    public function updateOrganizationData(Request $r) {
//        $updatedBy = Auth::user()->name;
//        $updatedTime = date('Y-m-d H:i:s');
//        $updateOrganization = DB::table('organizations')
//                            ->where('id', $r->id)
//                            ->update([
//                                    'org_type_id' => $r->org_type_id,
//                                    'org_name' => $r->org_name,
//                                    'add1' => $r->add1,
//                                    'add2' => $r->add2,
//                                    'port_id' => $r->port_id,
//                                    'propriter_name' => $r->propriter_name,
//                                    'phone' => $r->phone,
//                                    'mobile' => $r->mobile,
//                                    'email' => $r->email,
//                                    'created_by' => $updatedBy,
//                                    'create_datetime' => $updatedTime
//                                    ]);
//        if($updateOrganization == true) {
//            return 'Updated';
//        }
    }

    public function deleteOrganizationData(Request $r) {
//        $deleteOrganization = DB::table('organizations')
//                                ->where('id', $r->id)
//                                ->delete();
//        if($deleteOrganization == true) {
//            return 'Deleted';
//        }
    }
}
