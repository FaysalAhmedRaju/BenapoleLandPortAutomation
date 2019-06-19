

angular.module('bankApp', ['customServiceModule'])
    .controller('bankCtrl', function($scope,$http,$filter,$timeout, manifestService) {

        //capitalize the TruckType
        $scope.$watch('searchText', function (val) {

            $scope.searchText = $filter('uppercase')(val);

        }, true);


//New Manifest Added Start- 12/6/17
        $scope.keyBoard = function(event) {
            $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
        }

        $scope.$watch('searchText', function(){
            $scope.searchText = manifestService.addYearWithManifest($scope.searchText, $scope.keyboardFlag);
        });
//New Manifest Added End- 12/6/17

//====================Global Variable=================
        $scope.Mani_Id=0;

        $scope.Mani_No=0;

        $scope.TotalAssValue=0;
        $scope.TotalPaymentPaid=0;


//convert InWord============================================================================InWord
        var th = ['','thousand','million', 'billion','trillion'];
        var dg = ['zero','one','two','three','four', 'five','six','seven','eight','nine'];
        var tn = ['ten','eleven','twelve','thirteen', 'fourteen','fifteen','sixteen', 'seventeen','eighteen','nineteen'];
        var tw = ['twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

        function toWords(s)
        {


            s = s.toString();
            s = s.replace(/[\, ]/g,'');
            if (s != parseFloat(s)) return 'not a number';
            var x = s.indexOf('.');
            if (x == -1) x = s.length;
            if (x > 15) return 'too big';
            var n = s.split('');
            var str = '';
            var sk = 0;
            for (var i=0; i < x; i++)
            {
                if ((x-i)%3==2)
                {
                    if (n[i] == '1')
                    {
                        str += tn[Number(n[i+1])] + ' ';
                        i++;
                        sk=1;
                    }
                    else if (n[i]!=0)
                    {
                        str += tw[n[i]-2] + ' ';
                        sk=1;
                    }
                }
                else if (n[i]!=0)
                {
                    str += dg[n[i]] +' ';
                    if ((x-i)%3==0) str += 'hundred ';
                    sk=1;
                }


                if ((x-i)%3==1)
                {
                    if (sk) str += th[(x-i-1)/3] + ' ';
                    sk=0;
                }
            }
            if (x != s.length)
            {
                var y = s.length;
                str += 'point ';
                for (var i=x+1; i<y; i++) str += dg[n[i]] +' ';
            }
            return str.replace(/\s+/g,' ');
        }

//Calculate Slab Funtion=========================


        $scope.CalculateSlab = function (day, goods, shedYard) {//day 34

            console.log(goods)
            console.log(shedYard)

            $scope.firstSlabCharge=null;
            $scope.secondSlabCharge=null;
            $scope.thirdSlabCharge=null;

            $scope.totalFirstSlabCharge=null;
            $scope.totalSecondSlabCharge=null;
            $scope.totalThirdSlabCharge=null;

            $scope.firstSlabEndDay=null;
            $scope.secondSlabEndDay=null;
            $scope.thirdSlabEndDay=null;


            $scope.WarehouseTotalCharge=0
            if (day >= 1 && day <= 21) {//1 slab will be calculated------------------1
                $scope.gotFirstSlab=true

                $scope.firstSlabEndDay=day;
                $scope.firstSlabCharge=  $scope.SlabCharge(1,goods,shedYard)//
                $scope.totalFirstSlabCharge=$scope.WarehouseReceiveWeight*day*$scope.firstSlabCharge

                $scope.WarehouseTotalCharge+=$scope.totalFirstSlabCharge

            }

            else if (day >= 22 && day <= 50) {//2 slab will be calculated------------------2
                $scope.gotFirstSlab=true
                $scope.gotSecondSlab=true


                //---first slab
                $scope.firstSlabEndDay=21;
                $scope.firstSlabCharge=  $scope.SlabCharge(1,goods,shedYard)//
                $scope.totalFirstSlabCharge=$scope.WarehouseReceiveWeight*21*$scope.firstSlabCharge


                $scope.WarehouseTotalCharge+=$scope.totalFirstSlabCharge

                //--second slab
                $scope.secondSlabEndDay=day;
                $scope.secondSlabCharge=  $scope.SlabCharge(2,goods,shedYard)//
                $scope.totalSecondSlabCharge=$scope.WarehouseReceiveWeight*(day-21)*$scope.secondSlabCharge


                $scope.WarehouseTotalCharge+=$scope.totalFirstSlabCharge

            }
            else {//3 slab will be calculated---------------------------------3

                $scope.gotFirstSlab=true
                $scope.gotSecondSlab=true
                $scope.gotThirdSlab=true

                //---first slab
                $scope.firstSlabEndDay=21;
                $scope.firstSlabCharge=  $scope.SlabCharge(1,goods,shedYard)//
                $scope.totalFirstSlabCharge=$scope.WarehouseReceiveWeight*21*$scope.firstSlabCharge


                $scope.WarehouseTotalCharge+=$scope.totalFirstSlabCharge

                //--second slab
                $scope.secondSlabEndDay=50;
                $scope.secondSlabCharge=  $scope.SlabCharge(2,goods,shedYard)//
                $scope.totalSecondSlabCharge=$scope.WarehouseReceiveWeight*(day-21)*$scope.secondSlabCharge


                $scope.WarehouseTotalCharge+=$scope.totalSecondSlabCharge

                //--third slab
                $scope.thirdSlabEndDay=day;
                $scope.thirdSlabCharge=  $scope.SlabCharge(3,goods,shedYard)//
                $scope.totalThirdSlabCharge=$scope.WarehouseReceiveWeight*(day-50)*$scope.thirdSlabCharge


                $scope.WarehouseTotalCharge+=$scope.totalThirdSlabCharge
            }


        }

        $scope.SlabCharge=function (slab,goods,shedyard) {

            var rate=0;

            if (shedyard>=9 && shedyard<=24)//shedyard 9-24 --YARD
            {
                if (slab==1)
                {
                    if (goods==1) rate=0.16
                    else  if (goods==2) rate=0.23
                    else  if (goods==3) rate=0.53
                    else  if (goods==4) rate=1.06
                    else  if (goods==5) rate=4.13
                    else  if (goods==6) rate=20.56
                    else  if (goods==7) rate=41.06
                    else  if (goods==8) rate=82.11
                    else  if (goods==9) rate=5.41
                    else  if (goods==10) rate=2.27
                    else rate=7.2

                }

                else if (slab==2)
                {
                    if (goods==1) rate=0.23
                    else  if (goods==2) rate=0.46
                    else  if (goods==3) rate=1.06
                    else  if (goods==4) rate=2.07
                    else  if (goods==5) rate=8.23
                    else  if (goods==6) rate=41.06
                    else  if (goods==7) rate=82.11
                    else  if (goods==8) rate=164.22
                    else  if (goods==9) rate=12.16
                    else  if (goods==10) rate=4.06
                    else rate=17.99

                }

                else //slab =3
                {
                    if (goods==1) rate=0.30
                    else  if (goods==2) rate=0.63
                    else  if (goods==3) rate=1.56
                    else  if (goods==4) rate=3.10
                    else  if (goods==5) rate=12.33
                    else  if (goods==6) rate=61.43
                    else  if (goods==7) rate=123.18
                    else  if (goods==8) rate=246.30
                    else  if (goods==9) rate=17.99
                    else  if (goods==10) rate=8.12
                    else rate=23.38

                }




            }//shedyard 9-24 END
            else {//shedyard 25-30 --SHED


                if (slab==1)
                {
                    if (goods==1) rate=0.23
                    else  if (goods==2) rate=0.46
                    else  if (goods==3) rate=1.06
                    else  if (goods==4) rate=2.07
                    else  if (goods==5) rate=6.16
                    else  if (goods==6) rate=30.83
                    else  if (goods==7) rate=61.58
                    else  if (goods==8) rate=102.63
                    else  if (goods==9) rate=9.01
                    else  if (goods==10) rate=2.73
                    else rate=9.01

                }

                else if (slab==2)
                {
                    if (goods==1) rate=0.46
                    else  if (goods==2) rate=0.84
                    else  if (goods==3) rate=2.07
                    else  if (goods==4) rate=4.13
                    else  if (goods==5) rate=12.33
                    else  if (goods==6) rate=61.58
                    else  if (goods==7) rate=123.17
                    else  if (goods==8) rate=205.25
                    else  if (goods==9) rate=17.99
                    else  if (goods==10) rate=5.41
                    else rate=17.99

                }

                else //slab =3
                {
                    if (goods==1) rate=0.63
                    else  if (goods==2) rate=1.25
                    else  if (goods==3) rate=3.10
                    else  if (goods==4) rate=6.16
                    else  if (goods==5) rate=18.49
                    else  if (goods==6) rate=92.38
                    else  if (goods==7) rate=184.74
                    else  if (goods==8) rate=307.87
                    else  if (goods==9) rate=26.97
                    else  if (goods==10) rate=7.22
                    else rate=28.75

                }


            }
            return rate;

        }


//====================InWord Fuction END==========================================InWord Fuction END=========================

        $scope.doSearch=function (txt) {
            $scope.TotalAssValue=0;
            $scope.TotalPaymentPaid=0;

            $scope.BankPaymentDiv=false;
            $scope.AssessmentDiv=false;

            $scope.MNotFound=true;



          var  data={
                Mani_No:$scope.searchText
            }


            if (txt !='' && ! $scope.form.$invalid ) {

              console.log('ok')
                $scope.dataLoading=true;

                $http.post("/bank/api/serach-by-manifest-for-bank-data",data)

                    .then(function (data) {
                        console.log(data.data[0]);
                        if (data.data.length >= 1 && data.data[0].approved==1) {//?Manifest found

                            $scope.allData=data.data[0];

                            $scope.Mani_No=data.data[0].M_No; //need for saving in Global variable
                            $scope.Mani_Id= data.data[0].M_id

                            $scope.TotalAssValue=data.data[0].total

                            $scope.TotalAmount=$scope.TotalAssValue;

                            $('#totalInWord').html(toWords(Math.ceil((Math.ceil($scope.TotalAmount) *15/100) + Math.ceil($scope.TotalAmount) )) );



                          //  $scope.AssessmentData($scope.Mani_No)


                            $scope.GetPaidPaymentDetails(data.data[0].M_id)

                            $scope.BankPaymentDiv=true;
                            $scope.AssessmentDiv=true;
                            $scope.notFoundMsg='';



                        }
                        else {

                            if (data.data[0]==undefined){

                                $scope.notFoundMsg='Manifest Is not found!';
                            }

                                else if(data.data[0].approved!=1){console.log('nnnn')
                                    $scope.notFoundMsg='Assessment Is not Approved!';

                                }

                            $scope.BankPaymentDiv=false;
                            $scope.AssessmentDiv=false;
                        }

                    }).catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                    }).finally(function () {
                        $scope.dataLoading=false;
                    })
            }

            else{

            }
        };






        //AssessmentDiv function START




        $scope.AssessmentData=function (text) {

            $scope.dataLoading=true;

            var data={
                mani_No:text
            }


            //==========WareHouse Charge====================
            $http.post("/assessment/api/get-warehouse-data-for-assessment",data)

                .then(function (data) {
                    console.log(data.data[0])


                    //get Assessment  heading

                    $scope.ManifestNo=data.data[0].manifest_no;
                    $scope.Mani_date=data.data[0].manifest_date;
                    $scope.Bill_No=data.data[0].bill_entry_no;
                    $scope.Bill_date=data.data[0].bill_entry_date;
                    $scope.Custome_release_No=data.data[0].custom_realise_order_No;
                    $scope.Custome_release_Date=data.data[0].custom_realise_order_date;
                    $scope.Consignee=data.data[0].importer;
                    $scope.Consignor='';
                    $scope.CnF_Agent='';
                    $scope.Shed_Yard=data.data[0].posted_yard_shed;





                    var warehouse=data.data[0];

                    $scope.receive_date=warehouse.receive_date
                    $scope.freeday=warehouse.freedayend

                    $scope.fromdate=warehouse.warehouse_charge_start
                    $scope.deliver_date=warehouse.deliver_date


                    $scope.WarehouseNetDay=Math.ceil(warehouse.warehouse_charges_days);
                    $scope.Goods_id=warehouse.goods_id;
                    $scope.Shed_yard=warehouse.posted_yard_shed;

                    $scope.WarehouseReceiveWeight=Math.ceil(warehouse.ReceiveWeight);

                    //========Calculate Slab===============-------------------

                    if($scope.WarehouseNetDay>=1){

                        $scope.CalculateSlab($scope.WarehouseNetDay, $scope.Goods_id,$scope.Shed_yard);
                    }

                })
                .catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                })
                .finally(function () {

                })


            //==========Handling Charge====================
            $http.post("/assessment/api/get-handling-charge-for-assesment",data)

                .then(function (data) {
                    console.log(data.data[0])

                    $scope.WeightForHandling=Math.ceil(data.data[0].TotalNetWeight);
                    $scope.OffloadMode=data.data[0].offload_mode;
                    $scope.LoadingMode=data.data[0].onload_mode;
                    $scope.OffloadCharge=data.data[0].offload_charges;
                    $scope.LoadingCharge=data.data[0].onload_charges;



                    $scope.TotalForOffloadHandling= ($scope.WeightForHandling*data.data[0].offload_charges)

                    $scope.TotalForLoadHandling=($scope.WeightForHandling*data.data[0].onload_charges)

                })
                .catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                })
                .finally(function () {

                })



//==================Other Dues=================================
            $http.post("/assessment/api/get-other-dues-for-assessment",data)

                .then(function (data) {
                    $scope.Manifest_id=data.data[0].m_id
                    $scope.m=data.data[0];
                    var data=data.data[0];

                    $scope.foreignTruckAmount=data.Foreign_Truck*53.92;
                    $scope.localTruckAmount=data.Local_Truck*53.92;

                    //Carpenter charge
                    // $scope.NoofPackages= data.data[0].package_no ;
                    $scope.NoofPackages= data.package_no;

                    $scope.totalCarpenterCharge=$scope.NoofPackages*7.22;



                    //Weughbridge charge
                    $scope.totalWeighbridgeCharge=data.Foreign_Truck*89.84;


                })
                .catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                })
                .finally(function () {

                })





//=====================For Holtage for each Truck===================

            $http.post("/api/GetHaltageforAssesment",data)   // controller function id deactivate

                .then(function (data) {
                    console.log(data.data)
                    $scope.HaltageData=data.data;

                    $scope.TotalForeignTruck=data.data[0].TotalForeignTruck
                    $scope.HoltageDay=Math.ceil(data.data[0].HoltageDay)

                    $scope.holtageAmount=$scope.TotalForeignTruck* $scope.HoltageDay*71.86




                })
                .catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                })
                .finally(function () {
                    $scope.dataLoading=false;
                })


        };//END manifestSearch()

        //==================AssessmentDiv function END================================


// ==========================Bank Payment Section START====================================

//Get Remaining Balance
        $scope.GetPaidPaymentDetails=function (mid) {
            $scope.TotalPaymentPaid=0;
            console.log(mid)

            $http.get("/bank/api/get-paid-payment-details/"+mid)

                .then(function (data) {

                    $scope.PaymentData=data.data;




                   angular.forEach(data.data,function (v,k) {

                      // console.log(v.T_charge)
                       $scope.TotalPaymentPaid += parseFloat(v.T_charge)

                   })

                    console.log(($scope.TotalAssValue))

                    var assvalue=

                    $scope.Payment =Math.ceil(parseFloat($scope.TotalAssValue*15/100)+ parseFloat($scope.TotalAssValue)-$scope.TotalPaymentPaid);

                    console.log($scope.TotalPaymentPaid)
                    console.log($scope.TotalAssValue)

                    if ($filter('ceil')($scope.TotalPaymentPaid) > (parseFloat($scope.TotalAssValue*15/100)+ parseFloat($scope.TotalAssValue)))//full payment complete
                    {
                        $scope.PaymentComplete=true;
                        return;
                    }
                    $scope.PaymentComplete=false;

                })
                .catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                })
                .finally(function () {

                })


        }



        $scope.CheckPaymentExceed=function (p) {


            console.log(parseFloat($scope.TotalAssValue*15/100)+ parseFloat($scope.TotalAssValue)-$scope.TotalPaymentPaid)

            if (p>Math.ceil(parseFloat($scope.TotalAssValue*15/100)+ parseFloat($scope.TotalAssValue)-$scope.TotalPaymentPaid))
            {
                $scope.ExceedAmount=true;
            }
            else {
                $scope.ExceedAmount=false;
            }


        }


$scope.PaymentModePayorder=false;
$scope.PayOrder=function (payMode) {



    if (payMode == '1') {//means cash


        $scope.PaymentModePayorder=false;
        $scope.PayorderNo=null;

    }

    else {//0 means payorder


        $scope.PaymentModePayorder=true;
    }

}


$scope.savePayment=function () {



           if($scope.PaymentMode=='1'){//CASH

               console.log('click 1')

               var data={

                   manif_id : $scope.Mani_Id,
                   paymode: $scope.PaymentMode,
                   T_charge:$scope.Payment,
                   comment:$scope.Comment==undefined?'No Comment':$scope.Comment,
                   payment_details:null,
                   challan_no:$scope.challan_no
               }

           }

           else{//Payorder
               console.log($scope.PayorderNo)

                   if($scope.PayorderNo==undefined){

                       $scope.PayorderNoReq=true;
                       $("#PayorderNoReq").show().fadeTo(1500, 500).slideUp(500, function () {
                           $("#PayorderNoReq").slideUp(500);
                       });
                       return;

                   }
                   else{


                       var data={

                           manif_id : $scope.Mani_Id,
                           paymode: $scope.PaymentMode,
                           T_charge:$scope.Payment,
                           payment_details:$scope.PayorderNo,
                           comment:$scope.Comment==undefined?'No Comment':$scope.Comment,
                           challan_no:$scope.challan_no
                       }

                   }

           }

    $http.post("/bank/api/save-bank-payment-data",data)

        .then(function (data) {

               // $scope.Mani_Id

               $scope.Payment=null
                $scope.PayorderNo=null
               $scope.Comment=null
            $scope.challan_no=null;

            $scope.GetPaidPaymentDetails($scope.Mani_Id)

        })
        .catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
            console.log('err')
        })
        .finally(function () {

        })


        }

// Bank Payment Section END

//PDF function


        $scope.generatePDF=function () {

            console.log('ok');


            $scope.ForPdf = true;
            $(".ForPdf").show().fadeTo(30, 30).slideUp(20, function () {
                $(".ForPdf").slideUp(10);
            });
            /* $("#aa").show().fadeTo(30, 30).slideUp(20, function () {
             $("#aa").slideUp(10);
             });
             */



            kendo.drawing.drawDOM($('#aa'), {
                // paperSize: [1100, 1430],   //letter size 8.5"x11"
                paperSize: "A4",
                landscape: true,
                PrintOnFirstPage:false,
                margin: { top: "1cm", left: ".5cm", right: ".5cm", bottom: ".6cm" },
                template: $("#page-template").html(),
                scale: 0.8,
                forcePageBreak: ".page-break",
                date: new Date(),
                title: 'My Title',
                subject: 'My subject'


            }).then(function (group) {

                kendo.drawing.pdf.saveAs(group, "Report" + new Date() + ".pdf");
            });





        }



// END Controller
})


.filter('ceil', function() {
    return function(input) {
        return Math.ceil(input);
    };
})
/*
    .filter('payMode', function() {
    return function(input) {



        var mode;
        angular.forEach(input,function (v) {

            console.log(v);
        switch (v) {
            case 0:
                mode = "Cash";
                break;
            case 1:
                mode = "Pay Order";
        }
    })
        return mode;
        console.log(mode)


    };
})*/

    .filter('payMode', function () {

        return function (items) {

            for (var i = 0; i < items.length; i++) {
                var item = items[i];

                if (item == "1")
                {
                    item = "Cash";
                }
                else if (item == "0")
                {
                    item = "Pay-Order";
                }
            }
            return item;
        }

    })

