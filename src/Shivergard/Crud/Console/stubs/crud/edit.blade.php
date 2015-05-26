@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
        	<button id="search_filter" type="button" onclick="window.location = '{{ action($controller."@index") }}'" class="btn btn-primary">Back</button>
            <button id="reset_filter" type="button" onclick="window.location = '{{ action($controller."@create") }}'" class="btn btn-primary">Create</button>
        </div>
        <div class="col-md-8 col-md-offset-2">
	{!!  Illuminate\Html\HtmlFacade::ul($errors->all()) !!}
{!! Form::open(array('url' => action(
		"$controller@update" , 
		array('id' => $list->id)
) , 'method' => 'PUT'

)
) !!}
	</div>
	<div class="col-md-8 col-md-offset-2">

	@foreach($fields as $col)
		<div class="form-group">
			<div class="col-md-4">
			{!! Form::label($col, $col) !!}
			</div>
			<div class="col-md-6">
				{!! Form::text($col, $list->$col , array('class' => 'form-control')) !!}
			</div>
		</div>
	@endforeach
	</div>

	<div class="col-md-4 col-md-offset-2">
		{!! Form::submit('Edit!', array('class' => 'btn btn-primary')) !!}
	</div>
	

{!! Form::close() !!}

</div>
@endsection