<section class="sidebar">
    <!-- Sidebar user panel -->


    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">


        {{--For admin==============================START====================================ADMiN--}}
        @if(Auth::user()->role->name == 'Admin')
            <li class="header"><a href="{{route('admin-welcome-view')}}"><i class="fa fa-dashboard fa-2x"></i>&nbsp; &nbsp;
                    &nbsp; DASHBOARD</a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-linode"></i> <span>Importer Create</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('importer-list-view')}}"><i class="fa fa-info-circle"></i>Importer List</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-group"></i> <span>User Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('user-entry-form-view')}}"><i class="fa fa-user-circle"></i>Create New User</a></li>

                </ul>
            </li>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-institution"></i> <span>Organization Create</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('organization-entry-form-view')}}"><i class="fa fa-building-o"></i>Organization Entry Form</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-id-badge"></i> <span>C&F</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('c&f-create-c&f-view')}}"><i class="fa fa-building-o"></i>Create C&F</a></li>
                    <li><a href="{{route('c&f-create-cnf-employee-view')}}"><i class="fa fa-recycle"></i>Create C&F Employee</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-check-circle"></i> <span>Monitoring</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>

            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{url('')}}"><i class="fa fa-circle-o"></i>Report</a></li>
                    <li><a href="{{route('user-monitoring-online-users-view')}}"><i class="fa fa-user-circle"></i>Online Users</a></li>
                </ul>
            </li>


            <li class="treeview">
                <a href="#">
                    <i class="fa fa-product-hunt"></i> <span>Tariff</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('charges-tariff-tariff-entry-form-view')}}"><i class="fa fa-tag"></i>Tariff</a></li>
                    <li><a href="{{route('tariff-handling-other-charges-view')}}"><i class="fa fa-tag"></i>Other Charges</a></li>
                    <li><a href="{{route('charges-tariff-goods-tariff-goods-entry-view')}}"><i class="fa fa-tag"></i>Tariff Goods</a></li>
                </ul>
            </li>


            <li class="treeview">
                <a href="#">
                    <i class="fa fa-check-circle"></i> <span>Add Budget</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('admin-expenditure-budget-entry-form-view')}}"><i class="fa fa-tag"></i>Budget</a></li>
                </ul>
            </li>
        @endif
        {{--For ================================END==========================admin END--}}



        {{--For Truck===================================START===============================--}}
        @if(Auth::user()->role->name == 'Truck')
            <li class="header"><a href="{{route('truck-welcome-view')}}"> <i class="fa fa-dashboard fa-2x"></i> &nbsp;&nbsp;&nbsp;
                    DASHBOARD</a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-truck"></i> <span>Truck Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('truck-truck-entry-form-view')}}"><i class="fa fa-location-arrow"></i>Truck Entry/Exit</a>
                    </li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file"></i> <span>Report</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                <li><a href="{{route('truck-other-report-view')}}"><i class="fa fa-file-o"></i>Other Reports</a></li>
                </ul>
            </li>


            {{-- <li class="treeview">
                <a href="#">
                    <i class="fa fa fa-file"></i> <span>Others Report</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{url('DateWiseTruckReport')}}"><i class="fa fa fa-file-o"></i>Date Wise</a></li>
                </ul>
            </li> --}}

        @endif
        {{--For ============================END==============================Truck END--}}




        {{--For WeighBridge===================================START===============================--}}
        @if(Auth::user()->role->name == 'WeighBridge')

            <li class="header"><a href="{{route('weighbridge-welcome-view')}}"><i class="fa fa-dashboard fa-2x"></i> &nbsp;&nbsp;&nbsp;DASHBOARD</a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-balance-scale"></i> <span>WeighBridge Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('weighbridge-entry-form-view')}}"><i class="fa fa-truck fa-flip-horizontal"></i>&nbsp;WeighBridge
                            Entry/Exit</a></li>
                    {{-- <li><a href="{{url('WeighBridgeOut')}}"><i class="fa fa-truck"></i>WeighBridge Exit</a></li> --}}
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file"></i> <span>Report</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('weighbridge-weight-report-view')}}"><i class="fa fa-file-o"></i>Weight Report</a></li>
                    {{-- <li><a href="{{url('dateWiseWeightbridgeEntryReport')}}"><i class="fa fa-file-o"></i>Datewise Weightbridge Entry</a></li> --}}
                    <li><a href="{{route('weighbridge-other-reports-view')}}"><i class="fa fa-file-o"></i>Other Reports</a></li>
                </ul>
            </li>

        @endif
        {{--For ============================END==============================WeighBridge END--}}

        {{--For Custom===================================START===============================--}}
        @if(Auth::user()->role->name == 'Customs')
            <li class="header"><a href="{{route('customs-welcome-view')}}"><i class="fa fa-dashboard fa-2x"></i> &nbsp;&nbsp;&nbsp;DASHBOARD</a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-home"></i> <span>Custom Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('customs-entry-form-view')}}"><i class="fa fa-file-o"></i>Manifest Posting</a></li>
                </ul>
            </li>

        @endif
        {{--For ============================END==============================END END--}}



        {{--For Posting===================================START===============================--}}
        @if(Auth::user()->role->name == 'Posting')

            <li class="header"><a href="{{route('posting-branch-welcome-view')}}"> <i class="fa fa-dashboard fa-2x"></i> &nbsp;&nbsp;&nbsp;DASHBOARD</a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file-text-o"></i> <span>Posting Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('posting-entry-form-view')}}"><i class="fa fa-file"></i>Manifest Posting</a></li>
                    {{-- <li><a href="{{url('reportPosting')}}"><i class="fa fa-file"></i>Report</a></li> --}}
                    {{--<li><a href="{{url('yardGraphicalView')}}"><i class="fa fa-gratipay"></i>Graphical View</a></li>--}}
                </ul>

            </li>

            {{--<li class="treeview">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-truck"></i> <span>TruckEntry Module</span>--}}
                    {{--<span class="pull-right-container">--}}
                            {{--<i class="fa fa-angle-left pull-right"></i>--}}
                    {{--</span>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li><a href="{{url('TruckEntryForm')}}"><i class="fa fa-location-arrow"></i>Truck Entry/Exit</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}

            {{--</li>--}}

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file"></i> <span>Report</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    {{-- <li><a href="{{url('reportPosting')}}"><i class="fa fa-file-o"></i>Datewise Manifest Posting</a></li> --}}
                    <li><a href="{{route('posting-other-reports-view')}}"><i class="fa fa-file-o"></i>Other Reports</a></li>

                </ul>
            </li>

        @endif
        {{--For ============================END==============================Posting END--}}


        {{--For WareHouse===================================START===============================--}}
        @if(Auth::user()->role->name == 'WareHouse')



            <li class="header"><a href="{{route('wareHouse-welcome-view')}}"><i class="fa fa-dashboard fa-2x"></i> &nbsp;&nbsp;&nbsp;DASHBOARD</a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-home"></i> <span>WareHouse Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('warehouse-receive-entry-form-view')}}"><i class="fa fa-check-square"></i>Commodity Receive</a>
                    </li> {{-- Delivery Receive --}}
                    <li><a href="{{route('warehouse-delivery-request-view')}}"><i class="fa fa-road"></i>Delivery Request</a></li>
                    <li><a href="{{route('warehouse-delivery-local-transport-form-view')}}"><i class="fa fa-road"></i>Local Delivery</a></li>
                    {{-- <li><a href="{{url('TruckDeliveryEntryForm')}}"><i class="fa fa-truck"></i>Delivery</a></li> --}}
                    {{--                     <li><a href="{{url('Requisition')}}"><i class="fa fa-wrench"></i>Requisition</a></li> --}}
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file"></i> <span>Report</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">

                    {{-- <li><a href="{{url('/DateWiseWarehouseReceive')}}"><i class="fa fa-file-o"></i>Datewise WareHouse Entry</a></li> --}}
                    <li><a href="{{route('warehouse-others-reports-view')}}"><i class="fa fa-file-o"></i>Other Reports</a></li>

                </ul>
            </li>
            {{--<li class="treeview">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-truck"></i> <span>Truck</span>--}}
                    {{--<span class="pull-right-container">--}}
              {{--<i class="fa fa-angle-left pull-right"></i>--}}
            {{--</span>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li><a href="{{url('TruckEntryForm')}}"><i class="fa fa-location-arrow"></i>Truck Entry/Exit</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file-text-o"></i> <span>Manifest Posting</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    {{-- <li><a href="{{url('TranshipmentManifestPosting')}}"><i class="fa fa-file"></i>Manifest Posting</a></li> --}}
                    <li><a href="{{route('posting-entry-form-view')}}"><i class="fa fa-file"></i>Manifest Posting</a></li>
                </ul>
            </li>



        @endif
        {{--For ============================END==============================WareHouse END--}}



        {{--For Bank===================================START===============================--}}
        @if(Auth::user()->role->name == 'Bank')

            <li class="header"><a href="{{route('bank-welcome-view')}}"><i class="fa fa-dashboard fa-2x"></i>&nbsp;&nbsp;&nbsp;DASHBOARD</a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-bank"></i> <span>Bank Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('bank-payment-view')}}"><i class="fa fa-dollar"></i>Payment Bank</a></li>

                </ul>
            </li>
        @endif
        {{--For ============================END==============================Bank END--}}






        {{--For Maintenance===================================START===============================--}}
        @if(Auth::user()->role->name == 'Maintenance')
            <li class="header"><a href="{{route('posting-branch-welcome-view')}}">DASHBOARD</a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>WareHouse Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('warehouse-receive-entry-form-view')}}"><i class="fa fa-circle-o"></i>Delivery Receive</a></li>
                    <li><a href="{{url('DeliveryRequest')}}"><i class="fa fa-circle-o"></i>Delivery Request</a></li>
                    <li><a href="{{url('TruckDeliveryEntryForm')}}"><i class="fa fa-circle-o"></i>Delivery</a></li>
                </ul>
            </li>

        @endif
        {{--For ============================END==============================Maintenance END--}}




        {{--For C&F===================================START===============================--}}
        @if(Auth::user()->role->name == 'C&F')
            <li class="header"><a href="{{route('c&f-welcome-view')}}"><i class="fa fa-dashboard fa-2x"></i> &nbsp; &nbsp;
                    &nbsp;DASHBOARD</a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-recycle"></i> <span>C&F Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('posting-entry-form-view')}}"><i class="fa fa-file"></i>Manifest Posting</a></li>
                    <li><a href="{{route('c&f-bd-truck-entry-form-view')}}"><i class="fa fa-check-square"></i>BD
                            Truck Entry</a></li>
                    <li><a href="{{route('assessment-assessment-sheet-view')}}"><i class="fa fa-file-text-o"></i>Assessment Sheet</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file"></i> <span>Report</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('c&f-reports-manifest-wise-report-view')}}"><i class="fa fa-file-text"></i>Manifest Wise Report</a></li>
                    <li><a href="{{route('c&f-reports-importer-wise-report-view')}}"><i class="fa fa-linode"></i>Importer Wise Report</a></li>
                    <li><a href="{{route('c&f-reports-cargo-wise-report-view')}}"><i class="fa fa-circle-o"></i>Cargo Wise Report</a></li>
                    <li><a href="{{route('c&f-reports-cnf-date-wise-report-view')}}"><i class="fa fa-calendar"></i>Date Wise Report</a></li>
                </ul>
            </li>
        @endif
        {{--For ============================END==============================C&F END--}}






        {{--For TransShipment===================================START================================================--}}
        @if(Auth::user()->role->name == 'TransShipment')


            <li class="header"><a href="{{route('transhipment-welcome-view')}}"><i class="fa fa-dashboard fa-2x"></i> &nbsp;
                    &nbsp; &nbsp;DASHBOARD</a></li>

            {{--<li class="treeview">
                <a href="#">
                    <i class="fa fa-truck"></i> <span>Truck</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{url('TruckEntryForm')}}"><i class="fa fa-location-arrow"></i>Truck Entry/Exit</a>
                    </li>
                    <li><a href="{{route('truck-other-report-view')}}"><i class="fa fa-file-o"></i>Other Reports</a></li>

                </ul>

            <li class="header"><a href="{{route('trans-welcome')}}">
                <i class="fa fa-dashboard fa-2x"></i> &nbsp; &nbsp; &nbsp;DASHBOARD</a>

            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file-text-o"></i> <span>Manifest Posting</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    {{-- <li><a href="{{url('TranshipmentManifestPosting')}}"><i class="fa fa-file"></i>Manifest Posting</a></li> --}}
                    <li><a href="{{route('transshipment-posting-form')}}"><i class="fa fa-file"></i>Manifest Posting</a></li>

                    <li><a href="{{route('transshipment-posting-other-reports')}}"><i class="fa fa-file-o"></i>Other Reports</a></li>



                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-home"></i> <span>Transshipment</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    {{-- <li><a href="{{url('deliveryReceiveTranshipment')}}"><i class="fa fa-check-square"></i>Delivery Receive</a></li> --}}
                    <li><a href="{{route('transshipment-warehouse-entry-form')}}"><i class="fa fa-check-square"></i>Commodity Receive</a></li>
                    <li><a href="{{route('transshipment-warehouse-delivery-request-form')}}"><i class="fa fa-road"></i>Commodity Delivery</a>

                </ul>
            </li>
            {{-- <li class="treeview">
              <a href="#">
                  <i class="fa fa-home"></i> <span>Delivery Request</span>
                  <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
              </a>
              <ul class="treeview-menu">
                  <li><a href="{{url('deliveryRequestTranshipmentReq')}}"><i class="fa fa-road"></i>Delivery Request</a></li>

              </ul>
          </li> --}}
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-dollar"></i> <span>Assessment</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('transshipment-assessment-sheet-view')}}"><i class="fa fa-file-text-o"></i>Assessment Sheet</a></li>
                    {{--<li><a href="{{route('parishable-item')}}"><i class="fa fa-check"></i>Perishable Items</a></li>--}}
                    <li><a href="{{route('assessment-assessment-other-reports-view')}}"><i class="fa fa-file-o"></i>Other Reports</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-bank"></i> <span>Bank</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    {{--   <li><a href="{{url('TruckDeliveryEntryFormTranship')}}"><i class="fa fa-circle-o"></i>Delivery</a></li>
   --}}


                    <li><a href="{{route('bank-payment-view')}}"><i class="fa fa-dollar"></i>Payment Bank</a></li>

                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-envelope-open"></i> <span>Challan</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('assessment-invoice-challan-view')}}"><i class="fa fa-file-text-o"></i>Challan</a></li>

                </ul>
            </li>

            {{-- <li class="treeview">
                <a href="#">
                    <i class="fa fa-home"></i> <span>Delivery</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{url('TruckDeliveryEntryFormTranship')}}"><i class="fa fa-truck"></i>Delivery</a></li>
                    <li><a href="{{url('DeliveryRequest')}}"><i class="fa fa-road"></i>Delivery</a></li>
                    

                </ul>
            </li> --}}

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-sign-out"></i> <span>Exit Pass</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{url('LocalTruckGateOutTranshipment')}}"><i class="fa fa-truck"></i>Local Truck
                            GateOut</a></li>

                </ul>
            </li>

            {{-- <li class="treeview">
                <a href="#">
                    <i class="fa fa-sign-out"></i> <span>Assessment Admin</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{url('TodaysCompletedAssessment')}}"><i class="fa fa-truck"></i>Today's Assessment</a></li>

                </ul>
            </li> --}}

        @endif
        {{--For ============================END==============================TransShipment=================================== END--}}


        {{--For Assessment===================================START===============================--}}
        @if(Auth::user()->role->name == 'Assessment')

            <li class="header"><a href="{{url('/WelcomeAssessment')}}"><i class="fa fa-dashboard fa-2x"></i> &nbsp;&nbsp;&nbsp;DASHBOARD</a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-dollar"></i> <span>Assessment Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('assessment-assessment-sheet-view')}}"><i class="fa fa-file-text-o"></i>Assessment Sheet</a></li>
                   {{-- <li><a href="{{route('partial-assessment')}}"><i class="fa fa-file-text-o"></i>Partial
                            Assessment</a></li>--}}
                    <li><a href="{{route('assessment-assessment-other-reports-view')}}"><i class="fa fa-file-o"></i>Other Reports</a></li>

                    {{--    <li><a href="{{url('AssessmentVerification')}}"><i class="fa fa-user-circle"></i>Assessment
                                Verification</a></li>
                        <li><a href="{{url('AssessmentApprove')}}"><i class="fa fa-check"></i>Assessment Approve</a></li>
                    --}}
                </ul>
            </li>
            {{-- <li class="treeview">
                 <a href="#">
                     <i class="fa fa-envelope-open"></i> <span>Challan</span>
                     <span class="pull-right-container">
               <i class="fa fa-angle-left pull-right"></i>
             </span>
                 </a>
                 <ul class="treeview-menu">
                     <li><a href="{{url('AssessmentInvoice')}}"><i class="fa fa-file-text-o"></i>Challan</a></li>
                 </ul>
             </li>--}}

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-truck"></i> <span>Deliery Module</span>
                    <span class="pull-right-container">
                         <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('warehouse-delivery-request-view')}}"><i class="fa fa-road"></i>Delivery Request</a></li>
                </ul>
            </li>
        @endif
        {{--For ============================END==============================Assessment END--}}

        {{--For Passport===================================START===============================--}}
        @if(Auth::user()->role->name == 'Passport')

            <li class="header"><a href="{{url('/WelcomePassport')}}"><i class="fa fa-dashboard fa-2x"></i> &nbsp;&nbsp;&nbsp;DASHBOARD</a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-plane"></i><span>Passenger Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('passport-entry-form-view')}}"><i class="fa fa-cc-visa"></i>Passport Entry</a></li>
                    <li><a href="{{route('passport-entry-exit-form-view')}}"><i class="fa fa-sign-out"></i>Entry / Exit</a></li>


                </ul>
            </li>
        @endif
        {{--For ============================END==============================Passport END--}}


        {{--For Gate Start===================================START===============================--}}
        @if(Auth::user()->role->name == 'GateOut')

            <li class="header"><a href="{{route('gateout-welcome-view')}}"><i class="fa fa-dashboard fa-2x"></i> &nbsp; &nbsp;
                    &nbsp;DASHBOARD</a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-sign-out"></i> <span>Gate Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('gateout-local-truck-gateout-form-view')}}"><i class="fa fa-truck"></i>Local Truck In/Out</a></li>
                </ul>
            </li>
        @endif
        {{--For ============================END==============================Gate END--}}

        {{--For Accounts===================================START===============================--}}
        @if(Auth::user()->role->name == 'Accounts')

            <li class="header"><a href="{{route('accounts-welcome-view')}}"><i class="fa fa-dashboard fa-2x"></i> &nbsp; &nbsp;
                    &nbsp;DASHBOARD</a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Accounts Module</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li><a href="{{route('accounts-create-head-or-subhead-view')}}"><i class="fa fa-header"></i>Create Heading/Sub-Heading</a>
                    </li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-group"></i> <span>Salary Module</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('accounts-salary-employee-details-view')}}"><i class="fa fa-user"></i>Employee Details</a></li>
                    <li><a href="{{route('accounts-salary-designation-employee-view')}}"><i class="fa fa-cog"></i>Designation</a></li>
                    <li><a href="{{route('accounts-salary-bonus-and-increment-view')}}"><i class="fa fa-arrows"></i>Bonus And Increment</a></li>
                    <li><a href="{{route('accounts-salary-facilities-deduction-view')}}"><i class="fa fa-shield"></i>Facilities &
                            Deduction</a></li>
                    <li><a href="{{route('accounts-salary-home-rental-allowance-rates-form-view')}}"><i class="fa fa-shield"></i>Home Rental Allowance</a></li>
                    <li><a href="{{route('accounts-salary-employee-basic-form-view')}}"><i class="fa fa-shield"></i>Employee Basic</a></li>
                    <li><a href="{{route('accounts-salary-generate-salaty-view')}}"><i class="fa fa-spinner"></i>Generate Salary</a></li>
                    <li><a href="{{ route('accounts-salary-salary-report-view') }}"><i class="fa fa-envelope-open"></i>Report</a></li>


                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-dollar"></i> <span>Income Module</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    {{-- <li><a href="{{url('Invoice')}}"><i class="fa fa-envelope-open"></i>Challan</a></li> --}}
                    <li><a href="{{route('assessment-invoice-challan-view')}}"><i class="fa fa-file-text-o"></i>Challan</a></li>
                    <li>
                        <a href="{{route('accounts-income-reports-view')}}">
                            <i class="fa fa-file"></i> <span>Report</span>
                            {{-- <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span> --}}
                        </a>
                    </li>
                </ul>

            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Expenditure Module</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('accounts-expenditure-entry-form-view')}}"><i class="fa fa-keyboard-o"></i>Create Expenditure</a>
                    </li>
                    <li><a href="{{route('accounts-expenditure-report-expenditure-reports-view')}}"><i class="fa fa-file"></i>Expenditure Report</a></li>


                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-plus-circle"></i> <span>FDR Module</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    {{--  <li><a href="{{url('FDROpenning')}}"><i class="fa fa-credit-card"></i>FDR Openning</a></li> --}}
                    <li><a href="{{route('accounts-fdr-fdr-account-details-view')}}"><i class="fa fa-credit-card"></i>FDR Details</a></li>
                </ul>
            </li>

            {{--<li class="treeview">--}}
            {{--<a href="{{url('accountsReport')}}">--}}
            {{--<i class="fa fa-file"></i> <span>Report</span>--}}
            {{-- <span class="pull-right-container">--}}
            {{--<i class="fa fa-angle-left pull-right"></i>--}}
            {{--</span> --}}
            {{--</a>--}}
            {{-- <ul class="treeview-menu">--}}
            {{--<li><a href="{{url('MonthlyRevenue')}}" target="_BLANK"><i class="fa fa-lemon-o"></i> Month Wise Revenue</a></li>--}}
            {{--<li><a href="{{url('dateWiseRevenue')}}" ><i class="fa fa-calendar" ></i>Date Wise Revenue</a></li>--}}

            {{--</ul> --}}
            {{--</li>--}}
        @endif
        {{--For ============================END==============================Accounts END--}}

        {{--For Assessment Admin===================================START===============================--}}
        @if(Auth::user()->role->name == 'Assessment Admin' || Auth::user()->role->name =='TransShipment Assessment Admin')

            <li class="header"><a href="{{route('assessment-admin-welcome-view')}}"><i class="fa fa-dashboard fa-2x"></i> &nbsp;
                    &nbsp; &nbsp;DASHBOARD</a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Ass. Admin Module</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    @if(Auth::user()->role->id==23)
                        <li><a href="{{route('transshipment-completed-assessment-list-view')}}"><i class="fa fa-calendar"></i>Completed Assessment</a></li>

                    @else
                        <li>
                            <a href="{{route('assessment-admin-todays-completed-assessment-view')}}"><i class="fa fa-calendar"></i>Completed Assessment</a>
                        </li>

                    @endif

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-dollar"></i> <span>Assessment Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    @if(Auth::user()->role->id==23)
                        <li><a href="{{route('transshipment-assessment-sheet-view')}}"><i class="fa fa-file-text-o"></i>Assessment Sheet</a></li>

                    @else
                        <li><a href="{{route('assessment-assessment-sheet-view')}}"><i class="fa fa-file-text-o"></i>Assessment Sheet</a></li>

                    @endif


                    <li><a href="{{route('assessment-assessment-other-reports-view')}}"><i class="fa fa-file-o"></i>Other Reports</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-truck"></i> <span>Deliery Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    @if(Auth::user()->role->id==23)
                    <li><a href="{{route('transshipment-warehouse-delivery-request-form-view')}}"><i class="fa fa-road"></i>Delivery Request</a></li>
                    @else
                        <li><a href="{{route('warehouse-delivery-request-view')}}"><i class="fa fa-road"></i>Delivery Request</a></li>
                    @endif
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Reports</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{route('assessment-admin-truck-report-view')}}"><i class="fa fa-calendar"></i>Truck</a>
                    </li>
                    <li>
                        <a href="{{route('assessment-admin-weighbridge-report-view')}}"><i class="fa fa-calendar"></i>WeighBridge</a>
                    </li>
                    <li>
                        <a href="{{route('assessment-admin-posting-report-view')}}"><i class="fa fa-calendar"></i>Posting</a>
                    </li>
                    <li>
                        <a href="{{route('assessment-admin-warehouse-receive-report-view')}}"><i class="fa fa-calendar"></i>Warehouse(Receive)</a>
                    </li>
                    <li>
                        <a href="{{route('assessment-admin-warehouse-delivery-report-view')}}"><i class="fa fa-calendar"></i>Warehouse(Delivery)</a>
                    </li>
                </ul>
            </li>
        @endif
        {{--For ============================END==============================AssessmentAdmin END--}}

        {{--For Assessment Admin===================================START===============================--}}
        @if(Auth::user()->role->name == 'Export')

            <li class="header"><a href="{{route('export-truck-welcome-view')}}"><i class="fa fa-dashboard fa-2x"></i> &nbsp; &nbsp;
                    &nbsp;DASHBOARD</a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Export Module</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('export-truck-bus-type-entry-form-view')}}"><i class="fa fa-calendar"></i>Truck/Bus Type
                            Entry</a></li>
                    <li><a href="{{route('export-truck-entry-exit-form-view')}}"><i class="fa fa-calendar"></i>Truck Entry/Exit</a></li>
                    {{--<li><a href="{{url('ExBusEntry')}}"><i class="fa fa-calendar"></i>Bus Entry/Exit</a></li>--}}
                    <li><a href="{{route('export-truck-challan-entry-form-view')}}"><i class="fa fa-calendar"></i>Truck Challan</a></li>
                    {{--<li><a href="{{url('ExportBusChallan')}}"><i class="fa fa-calendar"></i>Bus Challan</a></li>--}}
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-truck"></i> <span>Import Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    {{--<li><a href="{{url('TruckEntryForm')}}"><i class="fa fa-location-arrow"></i>Truck Entry/Exit</a>--}}
                    {{--</li>--}}
                    <li><a href="{{route('truck-truck-entry-form-view')}}"><i class="fa fa-location-arrow"></i>Truck Entry/Exit</a>
                    </li>
                </ul>
            </li>
        @endif
        {{--For ============================END==============================Export END--}}



        {{--============================================================= Bus Module ============================================--}}
        @if(Auth::user()->role->name == 'Bus')

            <li class="header"><a href="{{route('export-bus-welcome-view')}}"><i class="fa fa-dashboard fa-2x"></i> &nbsp; &nbsp;
                    &nbsp;DASHBOARD</a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Bus Module</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('export-bus-type-entry-form-view')}}"><i class="fa fa-calendar"></i>Truck/Bus Type Entry</a></li>
                    <li><a href="{{route('export-bus-entry-form-view')}}"><i class="fa fa-calendar"></i>Bus Entry/Exit</a></li>
                    <li><a href="{{route('export-bus-challan-entry-form-view')}}"><i class="fa fa-calendar"></i>Bus Challan</a></li>
                </ul>
            </li>
            {{--<li class="treeview">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-truck"></i> <span>Import Module</span>--}}
                    {{--<span class="pull-right-container">--}}
              {{--<i class="fa fa-angle-left pull-right"></i>--}}
            {{--</span>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li><a href="{{url('TruckEntryForm')}}"><i class="fa fa-location-arrow"></i>Truck Entry/Exit</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}
        @endif
        {{--============================================================= Bus Module End ============================================--}}



        {{--==============For Transshipment Assessment Admin START===============================--}}
        {{-- @if(Auth::user()->role->name == 'TransShipment Assessment Admin')

            <li class="header"><a href="{{url('/WelcomeAssessmentAdmin')}}"><i class="fa fa-dashboard fa-2x"></i> &nbsp;
                    &nbsp; &nbsp;DASHBOARD</a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Assessment Module</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{url('TodaysCompletedAssessment')}}"><i class="fa fa-calendar"></i>Today's Assessment</a>
                    </li>
                </ul>
            </li>
        @endif --}}
        {{--==============For Transshipment Assessment Admin END===============================--}}
        {{--=================================Super Admin START============================--}}
        @if(Auth::user()->role->name == 'Super Admin')
            <li class="header"><a href="{{route('super-admin-welcome-view')}}"><i class="fa fa-dashboard fa-2x"></i> &nbsp;
                    &nbsp;
                    &nbsp;DASHBOARD</a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Truck Module</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('super-admin-truck-date-wise-truck-entry-monitor-view')}}"><i class="fa fa-calendar"></i>Monitor</a></li>
                    {{--<li><a href="{{url('TruckEntryForm')}}"><i class="fa fa-location-arrow"></i>Truck Entry/Exit</a>--}}
                    {{--</li>--}}
                    <li><a href="{{route('super-admin-truck-all-details-summary-view')}}"><i class="fa fa-file"></i>Reports</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Weighbridge Module</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('super-admin-weighbridge-date-wise-weighbridge-entry-monitor-view')}}"><i class="fa fa-calendar"></i>Monitor</a>
                    </li>
                    {{--<li><a href="{{url('WeighBridgeIn')}}"><i class="fa fa-truck fa-flip-horizontal"></i>&nbsp;WeighBridge--}}
                            {{--Entry/Exit</a></li>--}}
                    <li><a href="{{route('super-admin-weighbridge-all-details-summary-reports-view')}}"><i class="fa fa-file"></i>Reports</a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Posting Module</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('super-admin-posting-date-wise-entry-monitor-view')}}"><i class="fa fa-calendar"></i>Monitor</a></li>
                    {{--<li><a href="{{url('getManifestPosting')}}"><i class="fa fa-file"></i>Manifest Posting</a></li>--}}
                    <li><a href="{{route('super-admin-posting-all-details-summary-reports-view')}}"><i class="fa fa-file"></i>Reports</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i><span>Warehouse Module</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('super-admin-warehouse-receive-date-wise-warehouse-receive-entry-monitor-view')}}"><i class="fa fa-calendar"></i>Receive Monitor</a>
                    </li>
                    <li><a href="{{route('super-admin-warehouse-delivery-date-wise-warehouse-delivery-monitor-view')}}"><i class="fa fa-calendar"></i>Delivery
                            Monitor</a></li>
                    {{--<li><a href="{{url('WareHouseEntryForm')}}"><i class="fa fa-check-square"></i>Commodity Receive</a></li>--}}
                    {{--<li><a href="{{url('DeliveryRequest')}}"><i class="fa fa-road"></i>Delivery</a></li>--}}
                    <li><a href="{{route('super-admin-warehouse-reports-view')}}"><i class="fa fa-tag"></i>Reports</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Ass. Admin Module</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{route('assessment-admin-todays-completed-assessment-view')}}"><i class="fa fa-calendar"></i>Completed
                            Assessment</a>
                    </li>
                    <li><a href="{{route('assessment-assessment-other-reports-view')}}"><i class="fa fa-file-o"></i>Other Reports</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-check-circle"></i> <span>Admin Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('user-entry-form-view')}}"><i class="fa fa-user-circle"></i>Create User</a></li>
                    <li><a href="{{route('user-monitoring-online-users-view')}}"><i class="fa fa-user-circle"></i>Online Users</a></li>
                    <li><a href="{{route('admin-expenditure-budget-entry-form-view')}}"><i class="fa fa-tag"></i>Expenditure Limit</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-check-circle"></i> <span>Voucher Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('super-admin-bank-voucher-entry-view')}}"><i class="fa fa-file"></i>Bank Voucher</a></li>

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-truck"></i> <span>Accounts Module</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{route('accounts-income-reports-view')}}">
                            <i class="fa fa-file"></i> <span>Income Report</span>
                            {{-- <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span> --}}
                        </a>
                    </li>
                    <li><a href="{{route('accounts-expenditure-report-expenditure-reports-view')}}"><i class="fa fa-file"></i>Expenditure Report</a></li>
                    <li><a href="{{route('accounts-salary-salary-report-view')}}"><i class="fa fa-envelope-open"></i>Salary Report</a></li>
                    <li><a href="{{route('accounts-fdr-report-get-total-fund-postion-report')}}" target="_blank"><i class="fa fa-file"></i>Total Fund
                            Position</a></li>

                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-check-circle"></i> <span>Yearly Report</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('super-admin-yearly-reports-view')}}"><i class="fa fa-tag"></i>Reports</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Export Module</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('export-truck-bus-type-entry-form-view')}}"><i class="fa fa-calendar"></i>Truck/Bus Type
                            Entry</a></li>
                    <li><a href="{{route('export-truck-entry-exit-form-view')}}"><i class="fa fa-calendar"></i>Truck Entry/Exit</a></li>
                    {{--<li><a href="{{url('ExBusEntry')}}"><i class="fa fa-calendar"></i>Bus Entry/Exit</a></li>--}}
                    <li><a href="{{route('export-truck-challan-entry-form-view')}}"><i class="fa fa-calendar"></i>Truck Challan</a></li>
                    {{--<li><a href="{{url('ExportBusChallan')}}"><i class="fa fa-calendar"></i>Bus Challan</a></li>--}}
                </ul>
            </li>

        @endif
        {{--=================================Super Admin END============================--}}


        {{--============================================================= Export Admin Module ============================================--}}
        @if(Auth::user()->role->name == 'Export Admin')

            <li class="header"><a href="{{route('export-admin-welcome-view')}}"><i class="fa fa-dashboard fa-2x"></i> &nbsp; &nbsp;
                    &nbsp;DASHBOARD</a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Export Admin</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li><a href="{{route('export-admin-all-completed-challan-export-view')}}"><i class="fa fa-calendar"></i>Completed Challan</a></li>

                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Reports</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li>
                        <a href="{{route('export-admin-date-wise-bus-entry-report-view')}}"><i class="fa fa-calendar"></i>Export</a>
                    </li>

                    <li>
                        <a href="{{route('export-admin-date-wise-truck-entry-report-view')}}"><i class="fa fa-calendar"></i>Import</a>
                    </li>
                    <li>
                        <a href="{{route('export-admin-date-wise-weighbridge-entry-report')}}"><i class="fa fa-calendar"></i>WeighBridge Entry</a>
                    </li>

                </ul>
            </li>

        @endif
        {{--============================================================= Export Admin Module End ============================================--}}
    </ul>
</section>
<!-- /.sidebar -->