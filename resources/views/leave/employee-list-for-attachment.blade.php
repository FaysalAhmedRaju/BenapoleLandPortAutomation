    <div >

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th colspan="8">
                    <button type="button" id="save-list-btn" class="btn btn-info btn-sm center-block">Save
                    </button>
                </th>

            </tr>
            <tr>
                <th>S/L</th>
                <th>Employee Name</th>
            </tr>
            </thead>
            <tbody>
            @foreach($employeeList as $k=> $emp)
                <tr>
                    <td>{{++$k}}</td>
                    <td>
                        <input @if(in_array($emp->id,$availableLeaveList)) checked @endif title="" value=" {{ $emp->id}}" type="checkbox" id="attach-leave" class="attach-leave-checkbox">
                        {{ $emp->name or 'Not Available'}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

