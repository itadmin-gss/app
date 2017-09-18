@extends('layouts.onecolumn')

@section('content')

<div id="content" class="span12">



    <div class="row-fluid">

        <div class="box span12">

            <div class="box-header" data-original-title>

                <h2><span class="break"></span>Vendor Profile Completion - Step 1</h2>

            </div>

            <div class="box-content custome-form clearfix">



                 @if (Session::has('message'))

                <div class="alert alert-success">{!! Session::get('message') !!}</div>

                @endif



                @if($errors->has())

                @foreach ($errors->all() as $error)

                <div class="alert alert-error">{!! $error !!}</div>

                @endforeach

                @endif



                {!! Form::open(array('url' => 'vendor-profile-add', 'files'=>true,  'class'=>'form-horizontal')) !!}

                <fieldset>

                    <div class="row-fluid">

                        <div class="span6">



                            <div class="control-group">

                                {!!Form::label('username', 'Username', array('class' => 'control-label'))!!}

                                <div class="controls">

                                    @if($user_detail['username'])

                                    {!! Form::text('username',  isset($user_detail['username']) ? $user_detail['username'] : '' , array('class'=>'span10 typeahead','id'=>'username', 'readonly'=>'true'))!!}

                                    {!! Form::hidden('create_by_admin', 'no')!!}

                                    @else

                                    {!! Form::text('username',  isset($user_detail['username']) ? $user_detail['username'] : '' , array('class'=>'span10 typeahead','id'=>'username'))!!}

                                    {!! Form::hidden('create_by_admin', 'yes')!!}

                                    @endif



                                </div>

                            </div>



                            <div class="control-group">

                                {!!Form::label('email', 'Email', array('class' => 'control-label'))!!}

                                <div class="controls">

                                    {!! Form::email('email',  isset($user_detail['email']) ? $user_detail['email'] : '' , array('class'=>'span10 typeahead','id'=>'email', 'readonly'=>'true'))!!}

                                </div>

                            </div>



                            <div class="control-group">

                                {!!Form::label('company', 'Company', array('class' => 'control-label'))!!}

                                <div class="controls">

                                    {!! Form::text('company',  isset($user_detail['company']) ? $user_detail['company'] : '' ,  array('class'=>'span10 typeahead','id'=>'company'))!!}

                                </div>

                            </div>



                            <div class="control-group">

                                {!!Form::label('phone', 'Phone', array('class' => 'control-label'))!!}

                                <div class="controls">

                                    {!! Form::text('phone', isset($user_detail['phone']) ? $user_detail['phone'] : '' , array('class'=>'span10 typeahead','id'=>'phone'))!!}

                                </div>

                            </div>



                            <div class="control-group">



                                 {!!Form::label('address_1', 'Address 1', array('class' => 'control-label'))!!}

                                <div class="controls">

                                       {!! Form::text('address_1', isset($user_detail['address_1']) ? $user_detail['address_1'] : ''  , array('class'=>'span10 typeahead','id'=>'address_1'))!!}



                                </div>

                            </div>



                            <div class="control-group">

                                {!!Form::label('address_2', 'Address 2', array('class' => 'control-label'))!!}

                                <div class="controls">

                                      {!! Form::text('address_2', isset($user_detail['address_2']) ? $user_detail['address_2'] : '' , array('class'=>'span10 typeahead','id'=>'address_2'))!!}

                                </div>

                            </div>



                        </div>

                        <!--/span-4-->



                        <div class="span6 text-center">

                                    <div class="control-group">

                                        <div class="fileinput fileinput-new" data-provides="fileinput">

                                          <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">

                                              @if($user_detail['profile_picture'])

                                              {!! Html::image(Config::get('app.upload_path').$user_detail['profile_picture']) !!}

                                              @endif

                                          </div>

                                          <div>

                                            <span class="btn btn-default btn-file">

                                                {!!Form::file('profile_picture', array('value'=>$user_detail['profile_picture'],'class'=>'input-file uniform_on', 'id'=>'fileInput'))!!}

                                            </span>

                                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>

                                          </div>

                                        </div>

                                    </div>

                                </div>

                        <!--/span-4-->



                    </div>

                    <!--/row-fluid-->



                    <div class="row-fluid custome-input">







                                <div class="control-group span5">

                                    <?php

                                    $states_data = array('0' => 'Select State');

                                    foreach ($states as $state) {

                                        $states_data[$state['id']] = $state['name'];

                                    }

                                    ?>

                                    {!!Form::label('state_id', 'State', array('class' => 'control-label '))!!}



                                    <div class="controls">

                                        {!! Form::select('state_id', $states_data , isset($user_detail['state_id']) ? $user_detail['state_id'] : '0', array('class'=>'span8 typeahead','id'=>'state_id', 'data-rel'=>'chosen'))!!}

                                    </div>

                                </div>



                           <?php

                                $city_data = array('' => 'Select City');

                                $cities = City::getCitiesByStateId(Request::old('state_id'));

                                foreach ($cities as $city) {

                                    $city_data[$city['id']] = $city['name'];

                                }

                                ?>

                                <div class="control-group  span4">



                                    {!!Form::label('city_id', 'City', array('class' => 'control-label first-label'))!!}



                                    <div class="controls">

                                        {!! Form::select('city_id', $city_data , isset($user_detail['city_id']) ? $user_detail['city_id'] : '0' , array('class'=>'span8 typeahead','id'=>'city_id', 'data-rel'=>'chosen' ))!!}

                                    </div>

                                </div>



                        <div class="control-group span3">

                            {!!Form::label('zipcode', 'Zip ', array('class' => 'control-label '))!!}



                            <div class="controls">

                                 {!! Form::text('zipcode', isset($user_detail['zipcode']) ? $user_detail['zipcode'] : '' , array('class'=>'span7 typeahead','id'=>'zipcode'))!!}

                                </div>

                        </div>



                    </div>



                    <div class="form-actions text-right clearfix">



                         <input type="submit"  name="save_continue" value="Save & Continue" class="btn btn-success">

                        <input type="submit" name="save_exit" class="btn btn-info" value="Save & Exit">

                        <button type="button" class="btn btn-inverse">Cancel</button>

                    </div>

                </fieldset>

                {!! Form::close() !!}



            </div>

        </div><!--/span-->



    </div><!--/row-->





</div>

@stop