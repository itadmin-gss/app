@include('common.head')

<body class='dark-bg'>
    <div class="container-fluid">

        <div class="row-fluid"> 


            <div class="row-fluid">

                <div class="login-box">
		            <img class="inLogo" width="250px" src="{!!URL::to('/assets/images/GSS-Logo.jpg') !!}">

                    <div class="icons">

                        <a href="index.html"><i class="halflings-icon home"></i></a>

                        <a href="#"><i class="halflings-icon cog"></i></a>

                    </div>

                    <h3>Login to your account</h3>

                    {!! Form::open(array('url' => 'login', 'class' => 'form-horizontal', 'method' => 'post')) !!}

                    <fieldset>



                        <div class="form-group" title="Username">

                            <span class="add-on"><i class="halflings-icon user"></i></span>



                            {!! Form::text('username', '', array('placeholder'=>'Username', 'class' => 'form-control')) !!}



                        </div>

                        <div class="clearfix"></div>



                        <div class="input-prepend" title="Password">

                            <span class="add-on"><i class="halflings-icon lock"></i></span>

                            {!! Form::password('password', array('placeholder'=>'Password', 'class' => 'form-control')) !!}

                     

                            @if(isset($active))

                            {!! Form::hidden('active', $active) !!}

                            @endif



                        </div>

                        <div class="clearfix"></div>



                        <label class="remember" for="remember"><input name="remember_me" value="1" type="checkbox" id="remember" />Remember me</label>



                        <div class="button-login">

                            <button type="submit" class="btn btn-primary">Login</button>

                        </div>

                        {!! Form::close() !!}




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

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <footer class="sticky-footer" style='width:100% !important;'>
        <div class="container">
            <div class="text-center">
            <small>Copyright © <a href='admin'>Good Scents Services</a> {!! date('Y') !!}</small>
            </div>
        </div>
    </footer>

    @include('common.footerbottom')