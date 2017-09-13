@extends('layouts.default')
@section('content')
<div id="content" class="span11">




    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><span class="break"></span>Edit Customer Type</h2>
            </div>


            <div class="box-content custome-form">
                @if (Session::has('message'))
                {{ Session::get('message') }}
                @endif

                @if(!$errors->isEmpty())
                @foreach ($errors->all() as $error)
                <div class="alert alert-error">{{ $error }}</div>
                @endforeach
                @endif
                {{ Form::open(array('url' => 'editCustomerType', 'class' => 'form-horizontal', 'method' => 'post')) }}
                <fieldset>
                         <div class="row-fluid">
                        <div class="span6 offset3 centered">
                            <div class="control-group">
                                     {{ Form::label('typeahead', 'Customer Type *', array('class' => 'control-label')) }}
                                <div class="controls">
                                    <div class="input-append">
                                       {{ Form::hidden('id', $CustomerType->id, array('class' => 'input-xlarge focused')) }}
                                        {{ Form::text('title', $CustomerType->title, array('class' => 'input-xlarge focused',
                                         'id' => 'focusedInput')) }}
                                           
                        
                                    </div>
                                </div>
                            </div>
                           
                           
                        </div>
                    </div>
                    <div class="form-actions">



                        <div class="pull-right">
            				 {{ Form::submit('Edit Customer Type', array('class' => 'btn btn-large btn-success')) }}
                            {{ Form::button('Cancel', 
                           		 array('class' => 'btn btn-large btn-info', 
                            'onClick' =>'location.href="'.URL::to('list-service-categories').'"')) }}
                           
                            
                        </div>
                    </div>
                </fieldset>
                {{Form::close()}}
            </div>
        </div>

 



    </div>

</div>
@stop
