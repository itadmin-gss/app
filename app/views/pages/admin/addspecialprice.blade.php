@extends('layouts.default')
@section('content')

<div id="content" class="span11">
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><span class="break"></span>Add Special Price</h2>
            </div>
            <div class="box-content custome-form">
                <div class="row-fluid">
                    <div id="addSpecialPriceSuccessMessage" class="">                        
                    </div>
                    <div id="addSpecialPriceValidationErrorMessage" class="">
                    </div>
                    <div id="addSpecialPriceErrorMessage" class="hide">
                        <h4 class="alert alert-error">Can not Add Special Price</h4>
                    </div>
                    
                </div>
                {{ Form::open(array('action' => 'SpecialPriceController@addSpecialPrice', 'id'=>'addSpecialPriceForm', 'class' => 'form-horizontal', 'method' => 'post')) }}
                <form class="form-horizontal">
                    <fieldset> 
                        <div class="control-group">
                            {{ Form::label('adminAddSpecialPriceServiceDropdown', 'Service: ', array('class' => 'control-label')) }}
                            <div class="controls">
                                {{ Form::select('service_id', $services, null, array('id'=>'adminAddSpecialPriceServiceDropdown')) }}
                            </div>
                        </div>
                        <div class="control-group">
                            {{ Form::label('adminAddSpecialPriceCustomerDropdown', 'Customer: ', array('class' => 'control-label')) }}
                            <div class="controls">
                                {{ Form::select('customer_id', $customers, null, array('id'=>'adminAddSpecialPriceCustomerDropdown', 'data-rel'=>'chosen')) }}
                            </div>
                        </div>
                       
                       <div class="control-group">
                            {{Form::label('adminAddSpecialPriceServicePrice', 'Price:', array('class' => 'control-label'))}}
                            <div class="controls">
                                <div class="input-append">
                                    {{ Form::text('special_price','', array('size'=>'16','id'=>'adminAddSpecialPriceServicePrice'))}}<span class="add-on">.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            {{Form::label('adminAddSpecialPriceStatusDropdown', 'Status:', array('class' => 'control-label'))}}
                            <div class="controls">
                                {{ Form::select('status', array('0'=>'Disable','1'=>'Enable'), null, array('id'=>'adminAddSpecialPriceStatusDropdown')) }}
                            </div>
                        </div>
                        <div class="form-actions">
                            {{ Form::hidden('submitted', 1) }}
                            {{ Form::hidden('type_id', 2) }}
                            {{ Form::submit('Save changes', array('class'=>'btn btn-large btn-success text-left','id'=>''))}}
                            {{ Form::button('Cancel', array('class'=>'btn btn-large btn-info text-right','id'=>'','onClick' =>'location.href="'.URL::to('list-special-prices').'"'))}}
                        </div>                               
                    </fieldset>
                </form>
            </div>
        </div>





    </div>

</div>
<!-- end: Content -->

@stop