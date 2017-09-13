@extends('layouts.default')
@section('content')
<style type="text/css">
	.updtVndr h1, .updtVndr .alert { margin:0 0 30px;  }
	.updtVndr select#uniquevendorid { float: left;  margin: 0 10px 0 0; }
	.updtVndr .label-success { border: none; color: #fff; padding: 5px 15px; border-radius: 3px; }

</style>
<div id="content" class="span11 updtVndr">
<h1>Update Vendor</h1>
<div id="success" class="alert alert-success" style="display:none;"></div>
<select id="uniquevendorid">
@foreach($vendors as $vdata)

<option value="{{$vdata->id}}"  @if($recurrings->vendor_id==$vdata->id) selected="selected" @endif> 

{{$vdata->first_name}} {{$vdata->last_name}}
</option>
@endforeach
</select>
  <input type="hidden" value="{{$recurrings->id}}" id="uniquerecurringid">
 
<button class="label-success" onclick="changevendorname()">Submit</button>
</div>
	@stop