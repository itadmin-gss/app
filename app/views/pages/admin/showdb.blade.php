@extends('layouts.default')
@section('content')

<div id="content" class="span11">

    <div class="row-fluid ">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><span class="break"></span>Swap Database</h2>
            </div>
            <div class="box-content custome-form">
                
                <div class="row-fluid">
                    <!-- <div id="addVendorSuccessMessage" class="">                        
                    </div>
                    <div id="addVendorValidationErrorMessage" class="">
                    </div>
                    <div id="addVendorErrorMessage" class="hide">
                        <h4 class="alert alert-error">Can not Updated Profile</h4>
                    </div> -->
                    @if(Session::has('swapped'))
                    <h4 class="alert alert-success">{{ Session::get('swapped') }}</h4>
                    @endif
                    
                </div>
                
                {{ Form::open(array('url' => 'swap-db-admin', 'id'=>'swapDBForm' , 'class'=>'form-horizontal')) }}
                    <fieldset>
                        <div class="row-fluid">
                            <div class="span6 offset3 centered">

                                <div class="control-group">
                                    {{ Form::label('db', 'Database *', array('class' => 'control-label')) }}
                                    <div class="controls">
                                        <div class="input-append">
                                            {{ Form::select('db', array('old' => 'OLD', 'new' => 'NEW'), null, [ 'class' => '', 'onchange' => 'setDB(this.value)' ]) }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="pull-right">
                                {{ Form::submit('Swap', array('class'=>'btn btn-large btn-success text-left','id'=>'swapdbBtn'))}}
                            </div>
                        </div>   
                    </fieldset>
                </form>
            </div>
        </div>





    </div>

</div>
<!-- end: Content -->
@stop