<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;


class PassportController extends Controller
{
      public function welcome() {
        return view('passport.welcome');
    }

    public function passportEntryFormView() {
        return view('passport.PassportEntry');
    }


    public  function  visaEntryExitFormView(){
          return view('passport.VisaEntryExitForm');
    }


    public function savePassportEntryData(Request $r) {

//          return "ok";
        if($r->getId ==null) {
            $passportEntry = DB::table('passports')
                ->insert([
                    'passport_no' => $r->passport_no,
                    'country_code' => $r->country_code,
                    'sur_name' => $r->sur_name,
                    'given_name' => $r->given_name,
                    'nationality' => $r->nationality,
                    'sex' => $r->sex,
                    'date_of_birth' => $r->date_of_birth,
                    'place_of_birth' => $r->place_of_birth,
                    'place_of_issue' => $r->place_of_issue,
                    'date_of_issue' => $r->date_of_issue,
                    'date_of_expired' => $r->date_of_expired
                ]);
            if($passportEntry == true) {
                return "successfully inserted";
            }
        } else {
            $passportUpdate = DB::table('passports')
                ->where('id',$r->getId)
                ->update([
                    'passport_no' => $r->passport_no,
                    'country_code' => $r->country_code,
                    'sur_name' => $r->sur_name,
                    'given_name' => $r->given_name,
                    'nationality' => $r->nationality,
                    'sex' => $r->sex,
                    'date_of_birth' => $r->date_of_birth,
                    'place_of_birth' => $r->place_of_birth,
                    'place_of_issue' => $r->place_of_issue,
                    'date_of_issue' => $r->date_of_issue,
                    'date_of_expired' => $r->date_of_expired
                ]);
            if($passportUpdate == true) {
                return "Successfully Updated";
            }
        }

    }

public  function saveVisaEntryExitData(Request $r){

    $saveEntryExit =  DB::table('multi_entrys')
            ->insert([

                'date' => $r->date,
                'entry_reasons' => $r->entry_reasons,
                'comment' => $r->comment,
                'passport_id' => $r->passport_id,
                'entry_exit_status' => $r->entry_exit_status
            ]);
//    return response()->json();
    if($saveEntryExit != NULL) {
        return "successfully inserted";
    }

}

    public function saveVisaInformationData(Request $req)
    {
        DB::table('visa_details')->insert(
            [
//                'passport_no' => $req->passport_no,
//                'place_of_issue' => $req->place_of_issue,
//                'date_of_issue' => $req->date_of_issue,
//                'date_of_expired' => $req->date_of_expired,

                'type' => $req->type,
                'numbers_of_entries' => $req->numbers_of_entries,
                'duration_of_stay' => $req->duration_of_stay,
                'remarks' => $req->remarks,
                'passport_id' =>$req->passport_id
//                'sur_name' => $req->sur_name,
//                'date_of_birth' => $req->date_of_birth,
//                'sex' => $req->sex,

//                'nationlity' => $req->nationlity,

//                'passport_id' =>$req->passport_id
            ]
        );
        return response()->json();
    }



public  function  CheckPassportNo(Request $r){

    $checkPassport = DB::table('passports AS p')
        ->where('p.passport_no', $r->passport_no)
        ->get()->first();

    if (!$checkPassport) {
        return 'notfound';
    } else {
        return  'found';
    }

}

    public function getPassportDetailsInformation(Request $r) {




        $getPassportInfo = DB::table('passports')
           ->leftJoin('visa_details', 'visa_details.passport_id', '=','passports.id')
            ->where('passport_no', $r->passport_no)
            ->select('passports.*')
            ->get();
//        $file = fopen("Truckentry.txt","w");
//        echo fwrite($file,$getPassportInfo);
//        fclose($file);
//        return;
        return json_encode($getPassportInfo);


    }

    //please here is implement your code...

    public  function searchPassportEntryExitData(Request $r){

    $getAllpossportforEE = DB::table('passports')
        ->leftJoin('multi_entrys', 'multi_entrys.passport_id', '=','passports.id')
        ->where('passports.passport_no', $r->passport_no)
        ->select('passports.passport_no',
            'passports.id As p_id',
            'multi_entrys.*')
        ->get();
        return json_encode($getAllpossportforEE);

    }
// please see this one---------------------------------------------------------------------- :)
    public  function  allVisaInformationDetails(Request $r){
    $getVisaInfoShow = DB::table('passports')
                ->join('visa_details', 'visa_details.passport_id', '=','passports.id')
        ->where('passport_no', $r->v_passport_no)
        ->select(
            'passports.*',
            'visa_details.id as v_id',
            'visa_details.passport_id as passport_id',
            'visa_details.type as type',
            'visa_details.numbers_of_entries as numbers_of_entries',
            'visa_details.duration_of_stay as duration_of_stay',
            'visa_details.remarks as remarks'
        )
              ->get();
    return json_encode($getVisaInfoShow);
    }


    public  function  getAllExitEntryDataDetails(Request $r){

//        $file = fopen("Truckentry.txt","w");
//            echo fwrite($file,"Hello Raju :)".$r->exit);
//        fclose($file);
//        return;

        $getEntryExit = DB::table('passports')
            ->join('multi_entrys', 'multi_entrys.passport_id', '=','passports.id')
            ->where('passport_no', $r->exit)
            ->select(
                'passports.passport_no','multi_entrys.*')
            ->get();
        return json_encode($getEntryExit);
    }

public  function  visaInformationDetails(Request $r){
    $getAllVisaInfo = DB::table('passports')
//        ->join('visa_details', 'visa_details.passport_id', '=','passports.id')
        ->where('passport_no', $r->passport_no)
        ->select(
            'passports.*'
//            'visa_details.id as v_id',
//            'visa_details.passport_id as passport_id',
//            'visa_details.type as type',
//            'visa_details.numbers_of_entries as numbers_of_entries',
//            'visa_details.duration_of_stay as duration_of_stay',
//            'visa_details.remarks as remarks'
        )

        ->get();
    return json_encode($getAllVisaInfo);
}

    public function addvisa($id) {
        return view('passport.VisaDetailsForm',[
            'passport_id' =>$id
        ]);
    }

}
