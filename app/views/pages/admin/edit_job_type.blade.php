@extends('layouts.default')
@section('content')
<div id="content" class="span11">

    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><span class="break"></span>User Type Edit</h2>
            </div>
            <div class="box-content custome-form clearfix">

                <div class="row-fluid">
                    <div id="profileSuccessMessage" class="">
                    </div>
                    <div id="profileValidationErrorMessage" class="">
                    </div>
                    <div id="profileErrorMessage" class="hide">
                        <h4 class="alert alert-error">Can not Updated Profile</h4>
                    </div>

                </div>
                @if($errors->has())
                @foreach ($errors->all() as $error)
                <div class="alert alert-error">{{ $error }}</div>
                @endforeach
                @endif
                <div class="row-fluid">
                    <div class="span12">
                        {{ Form::open(array('url' => 'save-job-type', 'id'=>'savejobtype' , 'class'=>'form-horizontal')) }}
                        <fieldset>
                            <div class="row-fluid">
                                <div class="span6">

                                    <div class="control-group">
                                        {{Form::label('title', 'Job Title', array('class' => 'control-label'))}}
                                        <div class="controls">
                                            {{ Form::text('title',  isset($user_data['title']) ? $user_data['title'] : '' , array('class'=>'span10 typeahead','id'=>'title'))}}
                                            {{Form::hidden('id', $user_data['id'], array('id' => 'user_id'))}}
                                        </div>
                                    </div>

                                   


                            <div class="form-actions text-right clearfix">
                                {{ Form::submit('Save', array('class'=>'btn btn-large btn-success text-left','id'=>'jobTypeSubmit'))}}
                                {{ Form::button('Cancel', array('class'=>'btn btn-large btn-inverse text-right','id'=>''
                ,'onClick' => 'location.href="'.URL::to('admin').'"'))}}
                            </div>
                        </fieldset>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div><!--/span-->

    </div><!--/row-->


</div>
@stop

