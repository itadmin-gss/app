@include('common.head')

<body>

    <div class="container-fluid">

        <div class="row-fluid"> 

		<div class="txtCenter"><img class="inLogo" width="250px" src="{!!URL::to('/')!!}/public/assets/images/GSS-Logo.jpg"></div>

            <div class="row-fluid">

                <div class="login-box">

                    <div class="icons">

                        <a href="index.html"><i class="halflings-icon home"></i></a>

                        <a href="#"><i class="halflings-icon cog"></i></a>

                    </div>

                    <h2>Login to your account</h2>

                    {!! Form::open(array('url' => 'login', 'class' => 'form-horizontal', 'method' => 'post')) !!}

                    <fieldset>



                        <div class="input-prepend" title="Username">

                            <span class="add-on"><i class="halflings-icon user"></i></span>



                            {!! Form::text('username', '', array('placeholder'=>'Username', 'class' => 'nput-large span10')) !!}



                        </div>

                        <div class="clearfix"></div>



                        <div class="input-prepend" title="Password">

                            <span class="add-on"><i class="halflings-icon lock"></i></span>

                            {!! Form::password('password', array('placeholder'=>'Password', 'class' => 'nput-large span10')) !!}

                     

                            @if(isset($active))

                            {!! Form::hidden('active', $active) !!}

                            @endif



                        </div>

                        <div class="clearfix"></div>



                        <label class="remember" for="remember"><input name="remember_me" value="1" type="checkbox" id="remember" />Remember me</label>



                        <div class="button-login">

                            <button type="submit" class="btn btn-primary">Login</button>

                        </div>

                        <div class="clearfix"></div>

                        </form>

                        <hr>

                       

                      <!--   <p>

                            <a href="<?php //echo URL::to('forgot-password'); ?>">Forgot username or password?</a> 

                       </p> -->



                        @if(!$errors->isEmpty())

                        <div class="alert alert-error alert-danger form-element">

                            {!! $errors->first('password') !!}

                            {!! $errors->first('username') !!}

                        </div>

                        @endif



                </div><!--/span-->

            </div><!--/row-->



        </div><!--/fluid-row-->



    </div><!--/.fluid-container-->

    @include('common.footerbottom')

    @include('common.footer')