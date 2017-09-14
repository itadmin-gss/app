@extends('layouts.default')
@section('content')
<div id="content" class="span11">

    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><span class="break"></span>City Edit</h2>
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
                        {{ Form::open(array('url' => 'save-city', 'files'=>true, 'id'=>'CityEditForm' , 'class'=>'form-horizontal')) }}
                        <fieldset>
                            <div class="row-fluid">
                                <div class="span6">

                                    <div class="control-group">
                                        {{Form::label('name', 'Name', array('class' => 'control-label'))}}
                                        <div class="controls">
                                            {{ Form::text('name',  isset($city_data['name']) ? $city_data['name'] : '' , array('class'=>'span10 typeahead','id'=>'name'))}}
                                            {{Form::hidden('id', $city_data['id'], array('id' => 'id'))}}
                                        </div>
                                    </div>
                                    
                                </div>                                                             

                            </div>
                            <!--/row-fluid-->

                            <div class="row-fluid ">
                                <?php
                                $states_data = array('0' => 'Select State');
                                foreach ($states as $state) {
                                    $states_data[$state['id']] = $state['name'];
                                }
                                ?>
                                <div class="control-group span3">

                                    {{Form::label('state_id', 'State', array('class' => 'control-label '))}}

                                    <div class="controls">
                                        {{ Form::select('state_id', $states_data , isset($city_data['state_id']) ? $city_data['state_id'] : '0', array('class'=>'span8 typeahead','id'=>'state_id', 'data-rel'=>'chosen'))}}
                                    </div>
                                </div>
                            </div>                        


                            

                            <div class="form-actions text-right clearfix">
                                {{ Form::submit('Save', array('class'=>'btn btn-large btn-success text-left','id'=>'profileSaveButton'))}}
                                {{ Form::button('Cancel', array('class'=>'btn btn-large btn-inverse text-right','id'=>'profileCancelButton'
                ,'onClick' => 'location.href="'.URL::to('list-city').'"'))}}
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