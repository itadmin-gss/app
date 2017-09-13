@extends('layouts.default')
@section('content')
<div id="content" class="span11">




    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><span class="break"></span>Add New Customer</h2>
            </div>

            <div class="box-content custome-form">
                @if (Session::has('message'))
                {{ Session::get('message') }}
                @endif
                @if($errors->has())
                @foreach ($errors->all() as $error)
                {{ $error }}
                @endforeach
                @endif
                {{ Form::open(array('url' => 'add-new-customer', 'class' => 'form-horizontal', 'method' => 'post')) }}
                <fieldset>
                    <div class="row-fluid">
                        <div class="span6 offset3 centered">
                            <div class="control-group">
                                {{ Form::label('typeahead', 'First Name: *', array('class' => 'control-label')) }}
                                <div class="controls">
                                    <div class="input-append">
                                        {{ Form::text('first_name', '', array('class' => 'input-xlarge focused',
                                         'id' => 'focusedInput')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                {{ Form::label('typeahead', 'Last Name: *', array('class' => 'control-label')) }}
                                <div class="controls">
                                    <div class="input-append">
                                        {{ Form::text('last_name', '', array('class' => 'input-xlarge focused',
                                         'id' => 'focusedInput')) }}
                                    </div>
                                </div>
                            </div>

                                  <div class="control-group"> {{ Form::label('typeahead', 'Company Name: *', array('class' => 'control-label')) }}
                <div class="controls">
                  <div class="input-append"> 

                  {{ Form::text('company', '', array('class' => 'input-xlarge focused',
                                         'id' => 'company')) }}


                  </div>
                </div>
              </div>
                            <div class="control-group">
                                {{ Form::label('typeahead', 'Email Address: *', array('class' => 'control-label')) }}
                                <div class="controls">
                                    <div class="input-append">
                                        {{ Form::text('email', '', array('id' => 'focusedInput',
                                         'class' => 'input-xlarge focused')) }}
                                    </div>
                                </div>
                            </div>

                              <div class="control-group">
                                {{ Form::label('typeahead', 'Password *', array('class' => 'control-label')) }}
                                <div class="controls">
                                    <div class="input-append">
                                        {{ Form::text('password', '', array('id' => 'password','class' => 'input-xlarge focused')) }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-actions">

                        <div class="pull-right">
                            <button type="submit" class="btn btn-large btn-success">Create Customer</button>
                            <button class="btn btn-large btn-info">Cancel</button>
                        </div>
                    </div>
                </fieldset>
                </form>
            </div>
        </div>





    </div>

</div>
@stop