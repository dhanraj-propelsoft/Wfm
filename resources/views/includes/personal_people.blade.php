@section('sidebar')
@parent
	<li class="header"><span>My People</span></li>
      <li><a data-link="people" href="{{ route('personal_people.index') }}"> <i class="fa fa-user-o"></i><span>My People</span></a></li>
@stop

@section('dom_links')
@parent
@stop