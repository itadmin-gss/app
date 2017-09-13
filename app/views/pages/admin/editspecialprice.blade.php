@extends('layouts.default')
@section('content')

<div id="content" class="span11">
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><span class="break"></span>Edit Special Price</h2>
            </div>
            <div class="box-content custome-form">
                <div class="row-fluid">
                    <div id="editSpecialPriceSuccessMessage" class="">                        
                    </div>
                    <div id="editSpecialPriceValidationErrorMessage" class="">
                    </div>
                    <div id="editSpecialPriceErrorMessage" class="hide">
                        <h4 class="alert alert-error">Can not Add Special Price</h4>
                    </div>
                </div>
                {{ Form::open(array('action' => 'SpecialPriceController@addSpecialPrice', 'id'=>'editSpecialPriceForm', 'class' => 'form-horizontal', 'method' => 'post')) }}
                <form class="form-horizontal">
                    <fieldset>                         
                        <div class="control-group">
                            {{ Form::label('adminEditSpecialPriceServiceDropdown', 'Service: ', array('class' => 'control-label')) }}
                            <div class="controls">
                                {{ Form::select('service_id', $services, $special_price->service_id, array('id'=>'adminEditSpecialPriceServiceDropdown', 'data-rel'=>'chosen')) }}
                            </div>
                        </div>
                        <div class="control-group">
                            {{ Form::label('adminEditSpecialPriceCustomer', 'Customer: ', array('class' => 'control-label')) }}
                            <div class="controls">
                                {{ Form::text('customer',  isset($special_price->customer_id) ? $special_price->user->first_name.'  '.$special_price->user->last_name : '' , array('class'=>'typeahead','id'=>'adminEditSpecialPriceCustomer', 'readonly'=>'true'))}}
                            </div>
                        </div>
                       <div class="control-group">
                            {{Form::label('adminEditSpecialPriceServicePrice', 'Price:', array('class' => 'control-label'))}}
                            <div class="controls">
                                <div class="input-append">
                                    {{ Form::text('special_price',$special_price->special_price, array('size'=>'16','id'=>'adminEditSpecialPriceServicePrice'))}}<span class="add-on">.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            {{Form::label('adminEditSpecialPriceStatusDropdown', 'Status:', array('class' => 'control-label'))}}
                            <div class="controls">
                                {{ Form::select('status', array('0'=>'Disable','1'=>'Enable'), $special_price->status, array('id'=>'adminEditSpecialPriceStatusDropdown')) }}
                            </div>
                        </div>
                        <div class="form-actions">
                            {{ Form::hidden('submitted', 1) }}
                            {{ Form::hidden('customer_id',$special_price->customer_id ) }}
                            {{ Form::submit('Save changes', array('class'=>'btn btn-large btn-success text-left submit_form','id'=>$special_price->id))}}
                            {{ Form::button('Cancel', array('class'=>'btn btn-large btn-info text-right','id'=>'','onClick' =>'location.href="'.URL::to('list-special-prices').'"'))}}
                        </div>                               
                    </fieldset>
                </form>
            </div>
        </div>

<script>
	var $id = "{{ $special_price->special_price_id }}";
 	</script>



    </div>

</div>
<!-- end: Content -->

@stop