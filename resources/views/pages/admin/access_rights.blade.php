@extends('layouts.default')
@section('content')
<div id="content" class="span11">
    
    <!--/span-->
</div>
<!--/row-->

<div class="row-fluid">
    <div class="box span12 text-right">
        {!!Form::button('Save', array('name'=>'save_continue',
    'class'=>'btn btn-large btn-success', 'data-target' => "#assign", 'id' => 'save-access-rights'))!!}
        {!!Form::close()!!}
    </div>
    <!--/span-->
</div>
<!--/row-->

</div>
@stop