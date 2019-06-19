@extends('layouts.master')
@section('title', 'Online Users')
@section('content')
	<div class="col-md-12 text-center table-responsive">
		<table class="table table-bordered">
			<caption><h4 class="text-center ok">Online Users</h4></caption>
			<thead>
			<tr>
				<th>S/L</th>
                <th>Name</th>
                <th>Current Port</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Username</th>
                <th>Role</th>
                <th>Photo</th>
			</tr>
			</thead>
			<tbody>
				
					@php($i=0)
					@if($users)
						@foreach($users as $user)
							@if(Cache::has('user-online-'.$user->id))
							<tr>
								<td>{{ ++$i }}</td>
								<td>{{ $user->name }}</td>
								<td>{{ $user->current_port_name }}</td>
								<td>{{ $user->mobile }}</td>
								<td>{{ $user->email }}</td>
								<td>{{ $user->username }}</td>
								<td>{{ $user->rolename }}</td>
								<td>
									<img class="img-responsive" height="100" width="100" src="{{ $user->photo != null ? '/'.$user->photo : '/img/noImg.jpg' }}">
								</td>
							</tr>
							@endif
						@endforeach
					@endif
			</tbody>
		</table>
	</div>
@endsection

