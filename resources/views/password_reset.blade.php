@include('common.head')
<body>
    <div class="container-fluid">
        <div class="row-fluid">

            <div class="row-fluid">
                <div class="login-box">
                    <div class="icons">
                        <a href=""><i class="halflings-icon home"></i></a>
                        <a href="#"><i class="halflings-icon cog"></i></a>
                    </div>
                    <h2>Reset Password</h2>


                    {!! Form::open(array('url' => 'password/reset', 'class' => 'form-horizontal', 'method' => 'post')) !!}
                    {!!Form::hidden('token', $token)!!}
                    <fieldset>
                        <div class="input-prepend" title="Email">
                            <span class="add-on"><i class="halflings-icon user"></i></span>

                            {!! Form::text('email', '', array('placeholder'=>'Enter your email.', 'class' => 'nput-large span10')) !!}

                        </div>

                        <div class="clearfix"></div>

                        <div class="input-prepend" title="Password">
                            <span class="add-on"><i class="halflings-icon user"></i></span>

                            {!! Form::password('password', array('placeholder'=>'Password', 'class' => 'nput-large span10')) !!}

                        </div>
                        <div class="clearfix"></div>
                        <div class="input-prepend" title="Password Confirmation">
                            <span class="add-on"><i class="halflings-icon user"></i></span>

                            {!! Form::password('password_confirmation', array('placeholder'=>'Password Confirmation', 'class' => 'nput-large span10')) !!}

                        </div>


                        <div class="button-login">
                            <button type="submit" class="btn btn-primary">Reset</button>
                        </div>

                    </fieldset>
                    {!!Form::close()!!}


                    @if (Session::has('error'))
                    <div class="alert alert-error">{!! Session::get('error') !!}</div>
                    @elseif (Session::has('status'))
                    <div class="alert alert-success">{!! Session::get('status') !!}</div>
                    @endif


                </div><!--/span-->
            </div><!--/row-->

        </div><!--/fluid-row-->

    </div><!--/.fluid-container-->
    @include('common.footer')