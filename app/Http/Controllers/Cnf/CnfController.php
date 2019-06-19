<?php

namespace App\Http\Controllers\Cnf;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use DB;
use Image;
use App\Models\Port;
use App\Models\Cnf\Cnf;
use Session;
use File;

class CnfController extends Controller
{
    public $cnf;


    public function __construct(Cnf $cnf)
    {

        $this->cnf = $cnf;
    }

    public function createCnfView() {
        $portList = (new Port())->all();
//        $itemsPerPage =10;
//        $pagenumber = 2;
//        $firstLimit = ($pagenumber-1)*$itemsPerPage;
//        $lastLimit = $itemsPerPage;
//        $data = DB::table('cnf_details')
//            ->select('cnf_details.*',
//                DB::raw('TIMESTAMPDIFF(DAY, cnf_details.register_date, cnf_details.expired_date) AS total_day_difference'),
//                DB::raw('TIMESTAMPDIFF(DAY, cnf_details.register_date, CURDATE()) AS diff_from_today'),
//                DB::raw('(SELECT GROUP_CONCAT(cnf_port.port_id) FROM cnf_port WHERE cnf_port.cnf_id = cnf_details.id) AS port_id'),
//                DB::raw('(SELECT GROUP_CONCAT(ports.port_name) FROM ports,cnf_port WHERE cnf_port.cnf_id = cnf_details.id AND cnf_port.port_id=ports.id
//                    ) AS port_name'),
//                DB::raw('(SELECT  COUNT(cnf_details.id) FROM cnf_details) AS total')
//            )
//            ->offset($firstLimit)
//            ->limit($lastLimit)
//            ->get();
//        dd($data);
//
//        $theCnf = $this->cnf->findOrFail(33);
//        count(Cnf::findOrFail(33)->ports);
//
//        // return;
//
//        //attach or sync port to the user
//        if (count(Cnf::findOrFail(33)->ports) > 0) {
//            $theCnf->ports()->sync([1,4,8,9]);
//        } else {
//            $theCnf->ports()->attach([1,4,8]);
//        }
//
//
//        $theCnf->save();

//        $image_path_licence_photo = 'img/cnf/192.jpg';
//        if(File::exists($image_path_licence_photo)) {
//            File::delete($image_path_licence_photo);
//        }
//        dd(File::exists($image_path_licence_photo));
        return view('default.cnf.cnf-organitation',compact('portList'));
    }


    public function getAllCnfDetails($itemsPerPage,$pagenumber) {
        $firstLimit = ($pagenumber-1)*$itemsPerPage;
        $lastLimit = $itemsPerPage;
        $data = DB::table('cnf_details')
            ->select('cnf_details.*',
                DB::raw('TIMESTAMPDIFF(DAY, cnf_details.register_date, cnf_details.expired_date) AS total_day_difference'),
                DB::raw('TIMESTAMPDIFF(DAY, cnf_details.register_date, CURDATE()) AS diff_from_today'),
                DB::raw('(SELECT GROUP_CONCAT(cnf_port.port_id) FROM cnf_port WHERE cnf_port.cnf_id = cnf_details.id) AS port_id'),
                DB::raw('(SELECT GROUP_CONCAT(ports.port_name) FROM ports,cnf_port WHERE cnf_port.cnf_id = cnf_details.id AND cnf_port.port_id=ports.id
                    ) AS port_name'),
                DB::raw('(SELECT  COUNT(cnf_details.id) FROM cnf_details) AS total')
            )
            ->offset($firstLimit)
            ->limit($lastLimit)
            ->get();
        return json_encode($data);
    }


    public function saveCnfData(Request $r) {
    	
    	$createdBy = Auth::user()->id;
        $createdTime = date('Y-m-d H:i:s');
    	$insertCNF = DB::table('cnf_details')
    						->insertGetId([
    								'cnf_name' => $r->cnf_name,
    								'ain_no' => $r->ain_no,
                                    'licence_date' => $r->licence_date,
                                    'address' => $r->address,
                                    'mobile' => $r->mobile,
                                    'email' => $r->email,
                                    'register_date' => $r->register_date,
                                    'validity' => $r->validity,
                                    'expired_date' => $r->expired_date,
                                    'created_by' => $createdBy,
                                    'created_at' => $createdTime
                            ]);

        $theCnf = $this->cnf->findOrFail($insertCNF);
        $portArray = explode(',', $r->port_id);
//       \Log::info($portArray);

        if (count(Cnf::findOrFail($insertCNF)->ports) > 0) {
            $theCnf->ports()->sync($portArray);
        } else {
            $theCnf->ports()->attach($portArray);
        }

        $theCnf->save();


        if($r->hasFile('licence_photo') || $r->hasFile('owner_photo') || $r->hasFile('owner_nid_photo') || $r->hasFile('bank_voucher_photo') || $r->hasFile('shonchoypatro_photo') || $r->hasFile('agreement_photo')) {

            if($r->hasFile('licence_photo'))
            {
                $image = $r->file('licence_photo');
                $imageName = $insertCNF.'.jpg'; //$image->getClientOriginalExtension();
                $destinationPath = public_path('img/cnf');
                $img = Image::make($image->getRealPath());
                // return $image->getRealPath();

                $img->resize(140, 140)->encode('jpg')->save($destinationPath.'/'.$imageName);

                /*$img->resize(100, 100, function($constraint){
                    $constraint->aspectRatio();
                })->encode('jpg')->save($destinationPath.'/'.$imageName);*/

                DB::table('cnf_details')->where('id', $insertCNF)->update([
                    'licence_photo' => $imageName
                ]);

            }

            if($r->hasFile('owner_photo'))
            {
                $image_owner = $r->file('owner_photo');
                $imageName_owner = $insertCNF.'.jpg'; //$image->getClientOriginalExtension();
                $destinationPath_owner = public_path('img/cnf/owner_photo');

                $img_owner = Image::make($image_owner->getRealPath());
                // return $image->getRealPath();

                $img_owner->resize(140, 140)->encode('jpg')->save($destinationPath_owner.'/'.$imageName_owner);

                DB::table('cnf_details')->where('id', $insertCNF)->update([
                    'owner_photo' => $imageName
                ]);
            }

            if($r->hasFile('owner_photo'))
            {
                $image = $r->file('owner_photo');
                $imageName = $insertCNF.'.jpg'; //$image->getClientOriginalExtension();
                $destinationPath = public_path('img/cnf/owner_photo');
                $img = Image::make($image->getRealPath());
                // return $image->getRealPath();

                $img->resize(140, 140)->encode('jpg')->save($destinationPath.'/'.$imageName);

                DB::table('cnf_details')->where('id', $insertCNF)->update([
                    'owner_photo' => $imageName
                ]);
            }

            if($r->hasFile('owner_nid_photo'))
            {
                $image = $r->file('owner_nid_photo');
                $imageName = $insertCNF.'.jpg'; //$image->getClientOriginalExtension();
                $destinationPath = public_path('img/cnf/owner_nid_photo');
                $img = Image::make($image->getRealPath());
                // return $image->getRealPath();

                $img->resize(140, 140)->encode('jpg')->save($destinationPath.'/'.$imageName);

                DB::table('cnf_details')->where('id', $insertCNF)->update([
                    'owner_nid_photo' => $imageName
                ]);
            }
            //new Fields
            if($r->hasFile('bank_voucher_photo'))
            {
                $image = $r->file('bank_voucher_photo');
                $imageName = $insertCNF.'.jpg'; //$image->getClientOriginalExtension();
                $destinationPath = public_path('img/cnf/bank_voucher_photo');
                $img = Image::make($image->getRealPath());
                // return $image->getRealPath();

                $img->resize(140, 140)->encode('jpg')->save($destinationPath.'/'.$imageName);

                DB::table('cnf_details')->where('id', $insertCNF)->update([
                    'bank_voucher_photo' => $imageName
                ]);
            }

            if($r->hasFile('shonchoypatro_photo'))
            {
                $image = $r->file('shonchoypatro_photo');
                $imageName = $insertCNF.'.jpg'; //$image->getClientOriginalExtension();
                $destinationPath = public_path('img/cnf/shonchoypatro_photo');
                $img = Image::make($image->getRealPath());
                // return $image->getRealPath();

                $img->resize(140, 140)->encode('jpg')->save($destinationPath.'/'.$imageName);

                DB::table('cnf_details')->where('id', $insertCNF)->update([
                    'shonchoypatro_photo' => $imageName
                ]);
            }
            if($r->hasFile('agreement_photo'))
            {
                $image = $r->file('agreement_photo');
                $imageName = $insertCNF.'.jpg'; //$image->getClientOriginalExtension();
                $destinationPath = public_path('img/cnf/agreement_photo');
                $img = Image::make($image->getRealPath());
                // return $image->getRealPath();

                $img->resize(140, 140)->encode('jpg')->save($destinationPath.'/'.$imageName);

                DB::table('cnf_details')->where('id', $insertCNF)->update([
                    'agreement_photo' => $imageName
                ]);
            }

        }
        //return $insertUser;
        if(isset($insertCNF)) {
            return 'Success';
        }
    }

    public function updateCnfData(Request $r) {
    	$createdBy = Auth::user()->id;
        $createdTime = date('Y-m-d H:i:s');
        //return $r->validity;
    	$updateCNF = DB::table('cnf_details')
    						->where('id', '=', $r->cnf_id)
    						->update([
    								'cnf_name' => $r->cnf_name,
    								'ain_no' => $r->ain_no,
                                    'licence_date' => $r->licence_date,
                                    'address' => $r->address,
                                    'mobile' => $r->mobile,
                                    'email' => $r->email,
                                    'register_date' => $r->register_date,
                                    'validity' => $r->validity,
                                    'expired_date' => $r->expired_date,
                                    'updated_by' => $createdBy,
                                    'updated_at' => $createdTime
                            ]);

        $theCnf = $this->cnf->findOrFail($r->cnf_id);
        $portArray = explode(',', $r->port_id);
//       \Log::info($portArray);

        if (count(Cnf::findOrFail($r->cnf_id)->ports) > 0) {
            $theCnf->ports()->sync($portArray);
        } else {
            $theCnf->ports()->attach($portArray);
        }

        $theCnf->save();


        if($r->hasFile('licence_photo') || $r->hasFile('owner_photo') || $r->hasFile('owner_nid_photo') || $r->hasFile('bank_voucher_photo') || $r->hasFile('shonchoypatro_photo') || $r->hasFile('agreement_photo')) {

            if($r->hasFile('licence_photo'))
            {

                $image = $r->file('licence_photo');
                $imageName = $r->cnf_id.'.jpg';
                $destinationPath = public_path('img/cnf');
                $img = Image::make($image->getRealPath());
                $img->resize(140, 140)->encode('jpg')->save($destinationPath.'/'.$imageName);
                DB::table('cnf_details')->where('id', $r->cnf_id)->update([
                    'licence_photo' => $imageName
                ]);

            }

            if($r->hasFile('owner_photo'))
            {
                $image_owner = $r->file('owner_photo');
                $imageName_owner = $r->cnf_id.'.jpg'; //$image->getClientOriginalExtension();
                $destinationPath_owner = public_path('img/cnf/owner_photo');

                $img_owner = Image::make($image_owner->getRealPath());
                // return $image->getRealPath();

                $img_owner->resize(140, 140)->encode('jpg')->save($destinationPath_owner.'/'.$imageName_owner);

                DB::table('cnf_details')->where('id', $r->cnf_id)->update([
                    'owner_photo' => $imageName_owner
                ]);
            }

            if($r->hasFile('owner_photo'))
            {
                $image = $r->file('owner_photo');
                $imageName = $r->cnf_id.'.jpg'; //$image->getClientOriginalExtension();
                $destinationPath = public_path('img/cnf/owner_photo');
                $img = Image::make($image->getRealPath());
                // return $image->getRealPath();

                $img->resize(140, 140)->encode('jpg')->save($destinationPath.'/'.$imageName);

                DB::table('cnf_details')->where('id', $r->cnf_id)->update([
                    'owner_photo' => $imageName
                ]);
            }

            if($r->hasFile('owner_nid_photo'))
            {
                $image = $r->file('owner_nid_photo');
                $imageName = $r->cnf_id.'.jpg'; //$image->getClientOriginalExtension();
                $destinationPath = public_path('img/cnf/owner_nid_photo');
                $img = Image::make($image->getRealPath());
                // return $image->getRealPath();

                $img->resize(140, 140)->encode('jpg')->save($destinationPath.'/'.$imageName);

                DB::table('cnf_details')->where('id', $r->cnf_id)->update([
                    'owner_nid_photo' => $imageName
                ]);
            }
            //new Fields
            if($r->hasFile('bank_voucher_photo'))
            {
                $image = $r->file('bank_voucher_photo');
                $imageName = $r->cnf_id.'.jpg'; //$image->getClientOriginalExtension();
                $destinationPath = public_path('img/cnf/bank_voucher_photo');
                $img = Image::make($image->getRealPath());
                // return $image->getRealPath();

                $img->resize(140, 140)->encode('jpg')->save($destinationPath.'/'.$imageName);

                DB::table('cnf_details')->where('id', $r->cnf_id)->update([
                    'bank_voucher_photo' => $imageName
                ]);
            }

            if($r->hasFile('shonchoypatro_photo'))
            {
                $image = $r->file('shonchoypatro_photo');
                $imageName = $r->cnf_id.'.jpg'; //$image->getClientOriginalExtension();
                $destinationPath = public_path('img/cnf/shonchoypatro_photo');
                $img = Image::make($image->getRealPath());
                // return $image->getRealPath();

                $img->resize(140, 140)->encode('jpg')->save($destinationPath.'/'.$imageName);

                DB::table('cnf_details')->where('id', $r->cnf_id)->update([
                    'shonchoypatro_photo' => $imageName
                ]);
            }
            if($r->hasFile('agreement_photo'))
            {
                $image = $r->file('agreement_photo');
                $imageName = $r->cnf_id.'.jpg'; //$image->getClientOriginalExtension();
                $destinationPath = public_path('img/cnf/agreement_photo');
                $img = Image::make($image->getRealPath());
                // return $image->getRealPath();

                $img->resize(140, 140)->encode('jpg')->save($destinationPath.'/'.$imageName);

                DB::table('cnf_details')->where('id', $r->cnf_id)->update([
                    'agreement_photo' => $imageName
                ]);
            }

        }
        if(isset($updateCNF)) {
            return 'Success';
        }	
    }


    public function deleteCnfData(Request $r) {

        $theCnf = $this->cnf->findOrFail($r->id);
        $portArray = [];
        if (count(Cnf::findOrFail($r->id)->ports) > 0) {
            $theCnf->ports()->sync($portArray);
        }
        $theCnf->save();

//            if($r->licence_photo != 'null') {
//                File::delete('/img/cnf/'.$r->licence_photo);
//            }
        $image_path_licence_photo = 'img/cnf/'.$r->licence_photo;
        if(File::exists($image_path_licence_photo)) {
            File::delete($image_path_licence_photo);
        }

//                    if($r->licence_photo) {
//                        File::delete('img/cnf/'.$r->licence_photo);
//                    }




        $image_path_owner_photo = 'img/cnf/owner_photo/'.$r->owner_photo;
        if(File::exists($image_path_owner_photo)) {
            File::delete($image_path_owner_photo);
        }
//            if($r->owner_photo != 'null') {
//                File::delete('/img/cnf/owner_photo/'.$r->owner_photo);
//            }


        $image_path_owner_nid_photo = 'img/cnf/owner_nid_photo/'.$r->owner_nid_photo;
        if(File::exists($image_path_owner_nid_photo)) {
            File::delete($image_path_owner_nid_photo);
        }
//        if($r->hasFile('owner_nid_photo'))
//        {
//
//            if($r->owner_nid_photo != 'null') {
//                File::delete('/img/cnf/owner_nid_photo/'.$r->owner_nid_photo);
//            }
//
//        }

        $image_path_bank_voucher_photo = 'img/cnf/bank_voucher_photo/'.$r->bank_voucher_photo;
        if(File::exists($image_path_bank_voucher_photo)) {
            File::delete($image_path_bank_voucher_photo);
        }
//        if($r->hasFile('bank_voucher_photo'))
//        {
//
//            if($r->bank_voucher_photo != 'null') {
//                File::delete('/img/cnf/bank_voucher_photo/'.$r->bank_voucher_photo);
//            }
//
//        }

        $image_path_shonchoypatro_photo = 'img/cnf/shonchoypatro_photo/'.$r->shonchoypatro_photo;
        if(File::exists($image_path_shonchoypatro_photo)) {
            File::delete($image_path_shonchoypatro_photo);
        }
//        if($r->hasFile('shonchoypatro_photo'))
//        {
//
//
//            if($r->shonchoypatro_photo != 'null') {
//                File::delete('/img/cnf/shonchoypatro_photo/'.$r->shonchoypatro_photo);
//            }
//
//
//        }
        $image_path_agreement_photo = 'img/cnf/agreement_photo/'.$r->agreement_photo;
        if(File::exists($image_path_agreement_photo)) {
            File::delete($image_path_agreement_photo);
        }
//        if($r->hasFile('agreement_photo'))
//        {
//
//            if($r->agreement_photo != 'null') {
//                File::delete('/img/cnf/agreement_photo/'.$r->agreement_photo);
//            }
//
//        }

    	$data = DB::table('cnf_details')
    				->where('id', '=', $r->id)
    				->delete();
    	if(isset($data)) {
    		return "success";
    	}
    }
}
