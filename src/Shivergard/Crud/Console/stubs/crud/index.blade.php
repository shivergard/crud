@extends('app')

@section('content')

<div class="container-fluid">
	{!! Form::open(array('url' => action(
        "$controller@index" ),
        'id' => "filter"
    )) !!}
    	<input type="hidden" name="crud_filter" value="1"></input>
    <div class="row">
        <div class="col-md-6 col-md-offset-2">
        	

        	<div class='col-sm-3'>
                <div class="form-group">
                <label for="from">Created from:</label>
                    <div class='input-group date' id='datetimepicker1'>
                    
                        <input name="from" type='text' class="form-control dPix" 
                        @if (Session::has('filter_'.$prefix.'_from'))
                            value="{{Carbon\Carbon::parse(Session::get('filter_'.$prefix.'_from'))->format('Y-m-d')}}"
                        @else
                           value="{{Carbon\Carbon::now()->subYear()->format('Y-m-d')}}"
                        @endif
                        

                         />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class='col-sm-3'>
                <div class="form-group">
                <label for="till">Created Till:</label>
                    <div class='input-group date' id='datetimepicker2'>
                    
                        <input name="till" type='text' 

                        @if (Session::has('filter_'.$prefix.'_from'))
                            value="{{Carbon\Carbon::parse(Session::get('filter_'.$prefix.'_till'))->format('Y-m-d')}}"
                        @else
                            value="{{Carbon\Carbon::now()->format('Y-m-d')}}" 
                        @endif

                        class="form-control dPix" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            </div>

			<div class="col-md-6 col-md-offset-2">	
            @foreach($fields as $col)
            	@if (!in_array($col , array('updated_at' , 'created_at')))
					<div class='col-sm-3'>
		                <div class="form-group">
		                <label for="{{$col}}">{{$col}}  :</label>
		                    <div class='input-group' >
		                    
		                        <input name="{{$col}}" type='text' 

		                        @if (Session::has('filter_'.$prefix.'_'.$col))
		                            value="{{Session::get('filter_'.$prefix.'_'.$col)}}"
		                        @else
		                            value="" 
		                        @endif

		                        class="form-control" />
		                    </div>
		                </div>
		            </div>
	            @endif
			@endforeach
		</div>

		<div class="col-md-6 col-md-offset-2">	
			{!! Form::submit('Filter', array('class' => 'btn btn-primary')) !!}
            <button id="reset_filter" type="button"  class="btn btn-primary">Reset</button>
            <button type="button" onclick="window.location = '{{ action($controller."@create") }}'" class="btn btn-primary">Create</button>

        </div>
    </form>
        <div class="col-md-8 col-md-offset-2">
        	{!!$list->render()!!}
			<table class="table">
			    <thead>
				@foreach($fields as $col)
					<th>{{ $col }}</th>
				@endforeach
			    <th>Actions</th>
			    </thead>
			    <tbody id="item-list">
			    	@include('list' , array('list' => $list))	      	
			    </tbody>
			  </table>
			  {!!$list->render()!!}
		</div>
	</div>
</div>

</div>
@endsection

@section('style')
<link href="{{ asset('/packages/datepicker/css/datepicker.css') }}" rel="stylesheet">
@endsection

@section('script')
@parent
<script src="{{ asset('/packages/datepicker/js/bootstrap-datepicker.js') }}"></script>
<script type="text/javascript">
	function dropNode(data){
		$('#drop_'+ data).submit();
	}

	$(document).ready(function (){
		$('#search_filter').click(
	            function(){}
	    );

		 $('#reset_filter').click(
	            function(){

	                ajaxCall = {
	                    type: $('#filter').attr('method'),
	                    url: $('#filter').attr('action'),
	                    data: {
	                       _token :$('#filter').find('input[name=_token]').val() ,
	                       crud_filter : 1,
	                       clear: 1  
	                    },
	                    success: function (data) {
	                    	location.reload();
	                    }
	                };

	                $.ajax(ajaxCall);
	            }
	       );

            $('.dPix').datepicker({
			           format: "yyyy-mm-dd"
			});	
	});


	
</script>
@endsection