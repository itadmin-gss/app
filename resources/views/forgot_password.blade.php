@include('common.head')
<body>
    <div class="container-fluid">
        <div class="row-fluid">
			
            <div class="txtCenter"><img class="inLogo" width="250px" src="{{URL::to('/')}}/public/assets/images/GSS-Logo.jpg"></div>
            
            <div class="row-fluid">
                <div class="login-box">
                    <div class="icons">
                        <a href="{{URL::to('/')}}"><i class="halflings-icon home"></i></a>
                        <a href="#"><i class="halflings-icon cog"></i></a>
                    </div>
                    <h2>Reset Password</h2>


                    {{ Form::open(array('url' => 'password/remind', 'class' => 'form-horizontal', 'method' => 'post')) }}
                    <fieldset>

                        <div class="input-prepend" title="Email">
                            <span class="add-on"><i class="halflings-icon user"></i></span>

                            {{ Form::text('email', '', array('placeholder'=>'Enter your email.', 'class' => 'nput-large span10')) }}

                        </div>
                        <div class="clearfix"></div>
                        <div class="button-login">
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                        <div class="clearfix"></div>
                        </form>
                    </fieldset>


                    @if (Session::has('error'))
                    <div class="alert alert-error">{{ Session::get('error') }}</div>
                    @elseif (Session::has('status'))
                    <div class="alert alert-success">{{ Session::get('status') }}</div>
                    @endif


                </div><!--/span-->
            </div><!--/row-->

        </div><!--/fluid-row-->

    </div><!--/.fluid-container-->
    @include('common.footer')