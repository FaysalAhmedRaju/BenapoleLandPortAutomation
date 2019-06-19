<?php

namespace App\Http\Controllers\Leave;

use App\Models\Employee\Employee;
use App\Models\Leave\Leave;
use App\Models\Leave\LeaveApplication;
use App\Models\Leave\LeaveAvailable;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeaveController extends Controller
{

    protected $leave;
    protected $leaveApplication;

    public function __construct(Leave $leave)
    {
        $this->middleware('auth');
        $this->leave = $leave;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewType = 'Leave Lsit';
        $leaveList = Leave::all();

        return view('leave.index', compact('leaveList', 'viewType'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $availLeave=LeaveAvailable::whereLeaveId($id)->first();
        if ($availLeave){
            return redirect()->route('leave-list')->withErrors('This Leave Has Dependency!');
        }
        $leave=Leave::findOrFail($id);

        $leave->delete();
        return back()->withSuccess('Successfully Deleted!');

        //
    }

    //========================Leave Attach ment=======================


    public function leaveAttachedToEmployeeList()
    {
        $viewType = 'Attached List';
        $availableLeaveList = LeaveAvailable::paginate();
        return view('leave.attached-leave-list', compact('viewType', 'availableLeaveList'));
    }
    public function editLeaveAttachedToEmployee($id=null)
    {
        $viewType = 'Attached Edit Form';
        $leaveList = Leave::all();
        $availableLeave = LeaveAvailable::findOrFail($id);
        return view('leave.leave-attached-edit', compact('viewType', 'availableLeave','leaveList'));
    }
    public function updateLeaveAttachedToEmployee($id,Request $request)
    {

        $this->validate($request, [
            'leave_id' => 'required',
            'remaining' => 'required'
        ]);

        $availableLeave = LeaveAvailable::findOrFail($id);
        $availableLeave->remaining = $request->remaining;
        $availableLeave->save();

        return redirect()->route('leave-attached-to-employee-list')->withSuccess('Successfully Updated');
    }

    public function attachLeaveToEmployeeForm($id=null)
    {
        $viewType = 'Leave Attachment Form';
        $leaveId = $id;
        $leaveList = Leave::all();
        $employeeList = Employee::all();

        return view('leave.leave-attachment-form', compact('viewType','leaveId','leaveList', 'employeeList'));
    }

    public function getEmployeeListForAttachment(Request $request)
    {

        $leave_id = $request->leave_id;
        $viewType = 'Leave Attachment Employee List';
        $theLeave = Leave::findOrFail($leave_id);
        $employeeList = Employee::all();
        $availableLeaveList = LeaveAvailable::whereLeaveId($leave_id)->pluck('employee_id')->toArray();

        return view('leave.employee-list-for-attachment', compact('viewType', 'theLeave', 'availableLeaveList', 'employeeList'));
    }

    public function attachEmployeeToLeave(Request $request)
    {
        $leaveId = $request->leave_id;
        $empIds = $request->emp_ids;
        $theLeave = Leave::findOrFail($leaveId);
        $availableLeaveList = LeaveAvailable::whereLeaveId($leaveId)->get();

        /* $employess = (array)$empIds; // related ids
         $pivotData = array_fill(0, count($employess), ['remaining' => $theLeave->max_days]);
         $syncData = array_combine($employess, $pivotData);*/

        // $theLeave->employees()->sync($syncData);


        foreach ($empIds as $k => $empId) {

            $avail = LeaveAvailable::whereEmployeeId($empId)->whereLeaveId($leaveId)->first();
            if ($avail) {

            } else {
                $newAvail = new LeaveAvailable();
                $newAvail->employee_id = $empId;
                $newAvail->leave_id = $leaveId;
                $newAvail->remaining = $theLeave->max_days;
                $newAvail->save();

            }
        }

        $willBeDeleted = [];
        foreach ($availableLeaveList as $k => $value) {

            if (!in_array($value->employee_id, $empIds)) {
                $willBeDeleted[] = $value->id;
            }
        }

        $deleteLevae = LeaveAvailable::whereIn('id', $willBeDeleted)->delete();


        // $theLeave->employees()->sync($syncData);

        return response()->json(['message' => 'Successfully Matched!'], 200);

    }


    //=====================================================================Application=======================


    public function applicationList()
    {
        $viewType = 'Leave Application List';
        $leaveApplicationList = null;
        $leaveAdmin = false;
        $employeeList = null;

        if (Auth::user()->leaveAdmin()) {
            $leaveAdmin = true;
            $employeeList = Employee::all();
            $leaveApplicationList = LeaveApplication::orderBy('id', 'DESC')->paginate(20);
        } else {
            $empid = Auth::user()->port_employee_id;
            $leaveApplicationList = LeaveApplication::whereEmployeeId($empid)->orderBy('id', 'DESC')->paginate(10);
        }

        //dd($leaveApplicationList);

        return view('leave.applicatin-list', compact('viewType', 'leaveApplicationList', 'leaveAdmin', 'employeeList'));
    }

    public function applicationSearchByEmployee(Request $request)
    {
        $viewType = 'Leave Application List';
        $leaveApplicationList = null;
        $leaveAdmin = false;
        $employeeList = null;
        $empid = $request->employee_id;

        if (Auth::user()->leaveAdmin()) {
            $leaveAdmin = true;
            $employeeList = Employee::all();
            $leaveApplicationList = LeaveApplication::whereEmployeeId($empid)->orderBy('id', 'DESC')->paginate(20);
        } else {
            $leaveApplicationList = LeaveApplication::whereEmployeeId($empid)->orderBy('id', 'DESC')->paginate(10);
        }
        return view('leave.applicatin-list', compact('viewType', 'leaveApplicationList', 'leaveAdmin', 'employeeList'));

    }


    public function applicationForm()
    {
        $employeeList = null;
        $leaveAdmin = false;
        $viewType = 'Leave Application Form';
        $leaveList = Leave::all();

        if (Auth::user()->leaveAdmin()) {
            $leaveAdmin = true;
            $employeeList = Employee::all();
        };
        return view('leave.leave-application', compact('viewType', 'leaveList', 'employeeList', 'leaveAdmin'));
    }


    public function storeApplication(Request $request)
    {

        $this->validate($request, [
            'leave_id' => 'required',
            'from' => 'required',
            'to' => 'required',
            'leave_days' => 'required'
        ]);

        $empId = ($request->input('employee_id') ? $request->input('employee_id') : Auth::user()->portEmployee->id);
        $remainingLeave = LeaveAvailable::whereEmployeeId($empId)->whereLeaveId($request->input('leave_id'))->first();

        if (!$remainingLeave || $remainingLeave->remaining <= $request->input('leave_days')) {
            return \Redirect::back()->withInput()->withErrors(['You Don"t have The Leave']);
        }


        $leave = \DB::table('leave_applications')->insertGetId([
            'leave_id' => $request->input('leave_id'),
            'employee_id' => $empId,
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'leave_days' => $request->input('leave_days'),
            'reason' => $request->input('reason'),
            'applied_on' => Carbon::now(),
            'status' => "Applied"
        ]);
        //$leave->save();

        //  dd($leave);

        if ($request->hasFile('application_copy')) {
            $image = $request->file('application_copy');
            $imageName = $leave . '.jpg';
            $destinationPath = public_path('img/leave-application');
            $img = \Image::make($image->getRealPath());
            //return $image->getRealPath();
            $img->encode('jpg')->save($destinationPath . '/' . $imageName);
            \DB::table('leave_applications')->where('id', $leave)->update(['application_copy' => $imageName]);
            // $leave->application_copy= $imageName;
            // $leave->save();
        }
        return redirect()->route('leave-application-list');
    }


    public function editApplication($id)
    {
        $employeeList = null;
        $leaveAdmin = false;
        $viewType = 'Leave Application Edit Form';
        $leaveList = Leave::all();
        $theApplication = LeaveApplication::findOrFail($id);

        if (!Auth::user()->leaveAdmin()) {
            $empId = Auth::user()->portEmployee->id;
            if ($empId != $theApplication->employee_id) {
                return redirect()->route('leave-application-list')->withInput()->withErrors('Sorry, You are not authorized!');
            }
        }
        if (Auth::user()->leaveAdmin()) {
            $leaveAdmin = true;
            $employeeList = Employee::all();
        };
        // dd($leaveList);

        return view('leave.leave-application-edit', compact('viewType', 'leaveList', 'employeeList', 'leaveAdmin', 'theApplication'));
    }


    public function updateApplication($id, Request $request)
    {

        $this->validate($request, [
            'leave_id' => 'required',
            'from' => 'required',
            'to' => 'required',
            'leave_days' => 'required'
        ]);
        $theApplication = LeaveApplication::findOrFail($id);

        //check if the application he/she has access

        if (!Auth::user()->leaveAdmin()) {
            $empId = Auth::user()->portEmployee->id;
            if ($empId != $theApplication->employee_id) {
                return back()->withInput()->withErrors('Sorry, You are not authorized!');
            }
        }


        $empId = ($request->input('employee_id') ? $request->input('employee_id') : Auth::user()->portEmployee->id);
        $remainingLeave = LeaveAvailable::whereEmployeeId($empId)->whereLeaveId($request->input('leave_id'))->first();

        if (!$remainingLeave || $remainingLeave->remaining <= $request->input('leave_days')) {
            return \Redirect::back()->withInput()->withErrors(['You Don"t have The Leave']);
        }

        // LeaveApplication::whereId($id)->update([
        $theApplication->update([
            'leave_id' => $request->input('leave_id'),
            'employee_id' => $empId,
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'leave_days' => $request->input('leave_days'),
            'reason' => $request->input('reason'),
            'status' => "Applied"
        ]);

        // dd($theApplication);

        if ($request->hasFile('application_copy')) {
            $image = $request->file('application_copy');
            $imageName = $theApplication->id . '.jpg';
            $destinationPath = public_path('img/leave-application');
            $img = \Image::make($image->getRealPath());
            //return $image->getRealPath();
            $img->encode('jpg')->save($destinationPath . '/' . $imageName);
            \DB::table('leave_applications')->where('id', $theApplication->id)->update(['application_copy' => $imageName]);
        }
        return redirect()->route('leave-application-list')->withSuccess('Succefully Updated!');
    }


    public function grantApplication(Request $request)
    {
        $app_id = $request->application_id;
        $reason = $request->grant_reason;


        $theApplication = LeaveApplication::findOrFail($app_id);

        $theApplication->granted_on = Carbon::now();
        $theApplication->status = 'Granted';
        $theApplication->reason = $reason;
        $theApplication->save();

        //cut his/her remaining leave

        $remainingLeave = LeaveAvailable::whereEmployeeId($theApplication->employee_id)->whereLeaveId($theApplication->leave_id)->first();
        if ($remainingLeave) {
            $remainingLeave->remaining = ($remainingLeave->remaining - $theApplication->leave_days);
            $remainingLeave->save();
        }


        return response()->json(['message' => 'Successfully Granted the Application']);


    }

    public function rejectApplication(Request $request)
    {
        $app_id = $request->application_id;
        $reason = $request->reject_reason;
        $theApplication = LeaveApplication::findOrFail($app_id);
        $theApplication->rejected_on = Carbon::now();
        $theApplication->status = 'Rejected';
        $theApplication->reason = $reason;
        $theApplication->save();
        return response()->json(['message' => 'Successfully Rejected the Application']);


    }

    public function delectApplication($id)
    {
        $theApplication = LeaveApplication::findOrFail($id);
        $theApplication->delete();
        return back()->withSuccess('Successfully Deleted the Application');


    }
}
