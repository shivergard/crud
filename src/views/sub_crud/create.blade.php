@extends((\Config::get('crud.extend_view') ? \Config::get('crud.extend_view') : 'crud::app'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <button id="search_filter" type="button" onclick="window.location = '{{ action($controller."@index" , array('method' => $method) ) }}'" class="btn btn-primary">Back</button>
        </div>
        <div class="col-md-8 col-md-offset-2">
    <!-- if there are creation errors, they will show here -->
    {!! Form::open(array('class' => 'form-horizontal' ,'url' => action($controller."@store" , array('method' => $method)))) !!}
        @foreach($fields as $col)
            <div class="form-group">
                <div class="col-md-4">
                    {!! Form::label($col, $col) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::text($col, Input::old($col), array('class' => 'form-control')) !!}
                </div>
            </div>
        @endforeach

    {!! Form::submit('Create', array('class' => 'btn btn-primary')) !!}

    {!! Form::close() !!}
    {!! Html::ul($errors->all()) !!}
</div>
@endsection