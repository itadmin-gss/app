@extends('layouts.onecolumn')
@section('content')
<div id="content" class="span12">

    <div class="row-fluid">
        <div class="box span12 text-center">
            <div class="box text-center">
                <h1><span class="break "></span>Registration Successful</h1>
            </div>
            
            <div class="box-content custome-form clearfix">
                
                <h1 class="text-center">Thank you {!!$user->first_name!!} {!!$user->last_name!!}</h1>
                <h2 class="text-center">Your Account has been created Successfully & an email has been sent to you on {!!$user->email!!}</h2>
                <h2 class="text-center">Click on Login Button to login to your Account <a href="{!!URL::to('/')!!}">Login</a></h2>
                
                
                
            </div>
        </div><!--/span-->
    </div><!--/row-->
</div>
@stop