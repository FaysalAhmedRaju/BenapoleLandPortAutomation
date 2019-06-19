<?php

namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\GlobalFunctionController;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use DB;
use Auth;
use DateTime;
use DateInterval;
use DatePeriod;
use Response;
use Session;

class AssessmentWarehouseChargeController extends Controller
{
    private $globalfunctionCtrl;

    public function __construct(GlobalFunctionController $globalfunctionCtrl)
    {
        $this->globalfunctionCtrl = $globalfunctionCtrl;
    }


    public function assessmentDebug()
    {
        $mani = '50681/3/2017';
        return json_encode($this->getWarehouseForAssesment($mani));
    }


    public function getWarehouseForAssesment($mani_no)
    {
        // $mani_no='947/2/2017';
        $year = date('Y');
        $assessmentCreatedYear = $this->globalfunctionCtrl->getAassessmentCreatedYear($mani_no);

        if ($assessmentCreatedYear) {
            $year = $assessmentCreatedYear;
        }

        $w = DB::select('SELECT receive_date,deliver_date,m_id
             FROM(SELECT m.goods_id,m.package_no,m.id AS m_id,m.posted_yard_shed AS posted_yard_shed,m.approximate_delivery_date AS deliver_date,
          /*  (SELECT truck_entry_regs.receive_datetime FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.receive_datetime ASC LIMIT 1)AS receive_date,*/
            (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id ORDER BY truck_entry_regs.id DESC LIMIT 1)AS ReceiveWeight
            FROM manifests m  WHERE m.manifest=?)t', [$mani_no]);


        $receive_date = $w[0]->receive_date;
        $deliver_date = $w[0]->deliver_date;
        $item_wise_yard_charge = null;
        $item_wise_shed_charge = null;
        $mani_id = $w[0]->m_id;

        $freeEndDay = $this->GetFreedayEndForWarehouseRent($mani_id, $receive_date);//return $receive_date + 3 days including holidays
        $ChargeStartDay = $this->ChargeStartDay($freeEndDay);
        $wareHouseRentDay = $this->number_of_working_days($receive_date, $ChargeStartDay, $deliver_date);


        $check_the_port_has_tariff = DB::select('SELECT  COUNT(ts.id) AS found_tariff FROM tariff_schedule AS ts WHERE ts.port_id=? AND ts.tariff_year=?', [Session::get('PORT_ID'), $year]);

        //  dd($check_the_port_has_tariff[0]->found_tariff);

        if ($check_the_port_has_tariff[0]->found_tariff > 0) {
            // $tariff_port_id=Session::get('PORT_ID');


            $item_wise_yard_charge = DB::select('SELECT id.item_type,id.goods_id,id.dangerous,ts.yard_first_slab AS first_slab,ts.yard_second_slab AS second_slab,ts.yard_third_slab AS third_slab,ic.Description,
             (
            CASE id.item_type
            WHEN 4 THEN (CEIL (id.item_quantity/1000))
            ELSE id.item_quantity
            END
            ) AS item_quantity
            FROM item_details AS id 
            JOIN tariff_schedule AS ts ON ts.goods_id=id.goods_id
            JOIN item_codes AS ic ON ic.id=id.item_Code_id
            WHERE id.manf_id=? AND ts.tariff_year=? AND id.yard_shed=0 AND id.port_id=? AND ts.port_id=?', [$mani_id, $year, Session::get('PORT_ID'), Session::get('PORT_ID')]);

            $item_wise_shed_charge = DB::select('SELECT id.item_type,id.goods_id,id.dangerous,ts.Shed_first_slab AS first_slab,ts.Shed_second_slab AS second_slab,ts.Shed_third_slab AS third_slab,ic.Description,
            (
            CASE id.item_type
            WHEN 4 THEN (CEIL (id.item_quantity/1000))
            ELSE id.item_quantity
            END
            ) AS item_quantity
            FROM item_details AS id 
            JOIN tariff_schedule AS ts ON ts.goods_id=id.goods_id
            JOIN item_codes AS ic ON ic.id=id.item_Code_id
             WHERE id.manf_id=? AND ts.tariff_year=? AND id.yard_shed=1 AND id.port_id=? AND ts.port_id=?', [$mani_id, $year, Session::get('PORT_ID'), Session::get('PORT_ID')]);


        } else {

            $item_wise_yard_charge = DB::select('SELECT id.item_type,id.goods_id,id.dangerous,ts.yard_first_slab AS first_slab,ts.yard_second_slab AS second_slab,ts.yard_third_slab AS third_slab,ic.Description,
             (
            CASE id.item_type
            WHEN 4 THEN (CEIL (id.item_quantity/1000))
            ELSE id.item_quantity
            END
            ) AS item_quantity
            FROM item_details AS id 
            JOIN tariff_schedule AS ts ON ts.goods_id=id.goods_id
            JOIN item_codes AS ic ON ic.id=id.item_Code_id
            WHERE id.manf_id=? AND ts.tariff_year=? AND id.yard_shed=0 AND id.port_id=? AND ts.port_id IS NULL', [$mani_id, $year, Session::get('PORT_ID')]);

            $item_wise_shed_charge = DB::select('SELECT id.item_type,id.goods_id,id.dangerous,ts.Shed_first_slab AS first_slab,ts.Shed_second_slab AS second_slab,ts.Shed_third_slab AS third_slab,ic.Description,
            (
            CASE id.item_type
            WHEN 4 THEN (CEIL (id.item_quantity/1000))
            ELSE id.item_quantity
            END
            ) AS item_quantity
            FROM item_details AS id 
            JOIN tariff_schedule AS ts ON ts.goods_id=id.goods_id
            JOIN item_codes AS ic ON ic.id=id.item_Code_id
             WHERE id.manf_id=? AND ts.tariff_year=? AND id.yard_shed=1 AND id.port_id=? AND ts.port_id IS NULL', [$mani_id, $year, Session::get('PORT_ID')]);
        }

        return array(
            "WareHouseRentDay" => $wareHouseRentDay,
            'item_wise_yard_charge' => $item_wise_yard_charge,
            'item_wise_shed_charge' => $item_wise_shed_charge,
        );

    }

    public function number_of_working_days($receive_date, $from, $to)
    {
        $holiday = DB::table('holidays')
            ->select('holidays.hday')
            ->get();

        $holi = json_encode($holiday, False);

        $implode = array();
        $multiple = json_decode($holi, true);
        foreach ($multiple as $single) {
            $implode[] = implode(', ', $single);

        }

        $comma_separated = implode(",", $implode);


        $workingDays = [0, 1, 2, 3, 4]; # date format = w (1 = Sunnday, ...)
        $holidayDays = explode(',', $comma_separated);  # variable and fixed holidays

        // $holidayDays = ['*-12-25', '*-05-03', '2017-05-19', '2017-05-27']; # variable and fixed


        $from = new DateTime($from);
        $to = new DateTime($to);
        $receive_date = new DateTime($receive_date);

        $to->modify('+1 day');
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($from, $interval, $to);

        $days = 0;
        foreach ($periods as $period) {

            //  if (!in_array($period->format('w'), $workingDays)) continue;
            //if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
            // if (in_array($period->format('*-m-d'), $holidayDays)) continue;
            $days++;
        }
        //  $to->modify('-1 day');
        // if ($receive_date->format('w') >= 5) $days += 1;//add 1 day if unload on holiday
        // if ($to->format('w') >= 5 ) $days+=1;//add 1 day if load on holiday

        return $days;
    }

    public function GetFreedayEndForWarehouseRent($m_id, $from1)
    {
        $allForeignTruck = DB::table('truck_entry_regs as tr')
            ->whereManfId($m_id)
            ->select('tr.receive_datetime')
            ->orderBy('id', 'ASC')
            ->get();


        $holiday = DB::table('holidays')
            ->select('holidays.hday')
            ->get();

        $holi = json_encode($holiday, False);
        $implode = array();
        $multiple = json_decode($holi, true);
        foreach ($multiple as $single) {
            $implode[] = implode(', ', $single);
        }

        $comma_separated = implode(",", $implode);

        $workingDays = [0, 1, 2, 3, 4];
        $holidayDays = explode(',', $comma_separated);
        $from = new DateTime($from1);
        $unloadHoliday = new DateTime($from1);
        $loopControl = 0;

        $from->modify('+2 day');

        //   return (iterator_count($periods));echo json_encode($period);


        /*for ($i = 1; $i <= 20; $i++) {//check the day is in holiday
            if (!in_array($from->format('w'), $workingDays) || in_array($from->format('Y-m-d'), $holidayDays)) {

                if ($unloadHoliday->format('w') >=5 ) {
                    $unloadHoliday->modify('+2 day');
                    $from->modify('+1 day');
                    $loopControl++;
                } else {
                    $from->modify('+1 day');
                }
            } else {// if the day is not holiday or workingday
                $loopControl++;
                if ($loopControl < 3) {//will break loop if value 3
                    $from->modify('+1 day');
                } else {
                    break;
                }
            }
        }*/

        //below logic is for check if all truck unload same day or different day. if different day then free day will be decreased

        /*  $lastEntryReceiveTime = $allForeignTruck[0]->receive_datetime;//the result is DESC. o meanean last entried truck
          if (count($allForeignTruck) > 1) {//check foreign truck more than 1
              if (date($from1) != date($lastEntryReceiveTime)) {
                  $from->modify('-1 day');
              }
          }
          $loop = 0;
          if (count($allForeignTruck) > 2) {//check foreign truck more than 2. this for getting the 3rd truck received in different day too
              $from_date = date('Y-m-d', strtotime($from1));
              foreach ($allForeignTruck as $key => $value) {
                  if ($loop != 1) {
                      if (date('Y-m-d', strtotime($value->receive_datetime)) != date('Y-m-d', strtotime($lastEntryReceiveTime))) {
                          if (date('Y-m-d', strtotime($value->receive_datetime)) != $from_date) {
                              $from->modify('-1 day');
                              $loop++;
                          }
                      }
                  }else{
                      break;
                  }
              }
          }*/
        return $from->format('Y-m-d');

    }


    public function ChargeStartDay($from1)
    {


        /* $holiday = DB::table('holidays')
             ->select('holidays.hday')
             ->get();

         $holi=json_encode($holiday,False);

         $implode = array();
         $multiple = json_decode($holi, true);
         foreach($multiple as $single)
         {
             $implode[] = implode(', ', $single);
         }

         $comma_separated = implode(",", $implode);


         $workingDays = [0,1, 2, 3, 4]; # date format = w (1 = Sunnday, ...)
         $holidayDays =explode(',', $comma_separated);  # variable and fixed holidays
  */
        $from2 = new DateTime($from1);
        $from = $from2->modify('+1 day');
        /*$i=1;
        $loopControl=1;

        for ($i; $i <=20; $i++) {


            if ($loopControl!=2){

                if (!in_array($from->format('w'), $workingDays)  || in_array($from->format('Y-m-d'), $holidayDays))
                {
                    $from->modify('+1 day');
                    continue;
                }
                else// if the day is not holiday or workingday
                {
                    $loopControl++;
                }

            }
            else{//$loopControl == 2
                break;
            }
        }*/
        return $from->format('Y-m-d');

    }


}
