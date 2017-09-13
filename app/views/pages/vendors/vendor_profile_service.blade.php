@extends('layouts.onecolumn')
@section('content')
<!-- start: Content -->
<div id="content" class="span12">

    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><span class="break"></span>Vender Profile Completion - Step 2 - Add Services</h2>
            </div>
            <div class="box-content custome-form">
                
                @if($errors->has())
                @foreach ($errors->all() as $error)
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $error }}</strong>
                </div>
                @endforeach
                @endif
                
                {{ Form::open(array('url' => 'vendor-service-complete', 'class'=>'form-horizontal')) }}
                    <fieldset>
                        <div  class="row-fluid vendor-ser">
                            <div class="control-group">

                                <h4 class="offset1">Select Services that your Company Provides:</h4>
                             
                                <div class="controls">
                                   
                                        <?php $i=1; ?>
                                        @foreach ($services as $service)
                                         
                                             <?php $i++; 
                                             $vendor_services_options[$service->id]=$service->title;

                                             ?>
                                        @endforeach
                                         {{ Form::select('services[]', $vendor_services_options ,array() , array('style'=>'width:500px', 'class'=>'span15 typeahead','id'=>'vendor_services', 'data-rel'=>'chosen','multiple'=>'true'))}}

                                   
                                </div>
                            </div>
                        </div>

                        <div class="form-actions text-right">
                            <button type="submit" class="btn btn-success">Finish</button>
                            <button type="button" class="btn btn-inverse">Cancel</button>
                        </div>  
                    </fieldset>
                </form>   

            </div>
        </div><!--/span-->

    </div><!--/row-->

</div>
<!-- end: Content -->
@stop