@include('common.head')
<body>
    <div class="container-fluid">
        <div class="row-fluid">

            <div class="row-fluid">
                <div class="login-box">
                    <div class="icons">
                        <a href="index.html"><i class="halflings-icon home"></i></a>
                        <a href="#"><i class="halflings-icon cog"></i></a>
                    </div>

                    <style>
.icon { display: none; }
.hasicon { position: relative; }
.hasicon .icon {
    display: block;
    position: absolute;
    top: 0;
    right: 0;
    width: 32px;
    height: 32px;
    cursor: pointer;
}

                    </style>
                    <div class="hasicon">
                        <img src="http://t2.gstatic.com/images?q=tbn:ANd9GcQ7E5G4jUpfyFFtv5f-dSIT7ySOEOjiJB4_wOJTqtcYz7EqZGR8fA"  />
                        <img src="http://t2.gstatic.com/images?q=tbn:ANd9GcQ7E5G4jUpfyFFtv5f-dSIT7ySOEOjiJB4_wOJTqtcYz7EqZGR8fA" class="icon" />
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

                        </div>
                        <div class="clearfix"></div>

                        <label class="remember" for="remember"><input name="remember_me" value="1" type="checkbox" id="remember" />Remember me</label>

                        <div class="button-login">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                        <div class="clearfix"></div>
                        </form>
                        <hr>
                        <h3>Forgot Password?</h3>
                        <p>
                            No problem, <a href="#">click here</a> to get a new password.
                        </p>

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