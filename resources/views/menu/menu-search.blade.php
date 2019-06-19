<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>S/L</th>
        <th>Id</th>
        <th>Menu Name</th>
        <th>Route Name</th>
        <th>Route Type</th>
        <th>Parent</th>
        <th>Accessable</th>
        <th>Displayable</th>
        <th>Status</th>
        <th>action</th>

    </tr>
    </thead>
    <tbody>

    <tbody>


    @if(count($menus)>0)
        @foreach($menus as $k=> $menu)


            <tr>
                <td>{{++$k}}</td>
                <td>{{$menu->id}}</td>


                <td>
                    {{ $menu->menu_name? $menu->menu_name : 'Not Avaliable' }}
                </td>

                <td>{{ $menu->route_name or 'Not Available'}}</td>
                <td class="text-capitalize">{{ $menu->route_type or 'Not Available'}}</td>
                <td>
                    @if($menu->menu)
                        {{$menu->menu->id.'-'.$menu->menu->menu_name }}
                    @else
                        No
                    @endif
                </td>
                <td>{{ $menu->is_common_access?'yes':'no' }}</td>
                <td>{{ $menu->is_displayable?'yes':'no' }}</td>
                <td>
                    @if(isset($menu->status) && $menu->status == true)
                        {{ 'Active' }}
                    @else
                        {{ 'Inactive' }}
                    @endif
                </td>

                <td>

                    <a class="btn btn-success btn-xs mrg" data-original-title="" data-toggle="tooltip"
                       href="{{ route('menu-edit-form',[$menu->id]) }}">
                        <i class="fa fa-edit"></i>
                        Edit
                    </a>

                    <a class="btn btn-danger delete_btn btn-xs mrg" data-original-title=""
                       onclick="return confirm('Are you sure?')" href="{{ route('menu-delete',[$menu->id]) }}">
                        <i class="fa fa-trash-o"></i>
                        Delete
                    </a>
                </td>

            </tr>
        @endforeach

        @else
        <tr>
            <th colspan="10" class="text-center text-warning">No Menu Found!</th>
        </tr>
    @endif

    </tbody>
</table>