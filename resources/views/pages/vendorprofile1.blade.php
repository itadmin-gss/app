@extends('layouts.onecolumn')
@section('content')
<!-- start: Content -->
			<div id="content" class="span12">
									
			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><span class="break"></span>Vendor Profile Completion</h2>
					</div>
					<div class="box-content custome-form">
						<form class="form-horizontal">
						  <fieldset>
                           <div class="row-fluid">
                             <div class="span6">
                                <div class="control-group">
                                  <label class="control-label" for="typeahead">Username </label>
                                  <div class="controls">
                                    <input type="text" class="span10 typeahead" id="typeahead"  data-provide="typeahead" data-items="4" data-source=''>
                                  </div>
                                </div>
                                                            
                                <div class="control-group">
                                  <label class="control-label" for="typeahead">Email </label>
                                  <div class="controls">
                                    <input type="email" class="span10 typeahead" id="typeahead"  data-provide="typeahead" data-items="4">
                                  </div>
                                </div>
                                                          
                                <div class="control-group">
                                  <label class="control-label" for="typeahead">Phone </label>
                                  <div class="controls">
                                    <input type="text" class="span10 typeahead" id="typeahead"  data-provide="typeahead" data-items="4">
                                  </div>
                                </div>
                                                          
                                <div class="control-group">
                                  <label class="control-label" for="typeahead">Address 1</label>
                                  <div class="controls">
                                    <input type="text" class="span10 typeahead" id="typeahead"  data-provide="typeahead" data-items="4">
                                  </div>
                                </div>
                                                          
                                                          
                                <div class="control-group">
                                  <label class="control-label" for="typeahead">Address 2</label>
                                  <div class="controls">
                                    <input type="text" class="span10 typeahead" id="typeahead"  data-provide="typeahead" data-items="4">
                                  </div>
                                </div>
                                                          
                              
                                <div class="control-group">
                                  <label class="control-label" for="typeahead">Company Name </label>
                                  <div class="controls">
                                    <input type="text" class="span6 typeahead" id="typeahead"  data-provide="typeahead" data-items="4" data-source='["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Dakota","North Carolina","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"]'>
                                  </div>
                                </div>
           
                              </div>
                              <div class="span5">                           
                                <div class="control-group">
                                  <div class="row">
                                    <div class="span4 offset4 input-file-box">
                                       
                                    </div>
                                  </div>
                                  
                                  <label class="control-label" for="fileInput">Profile Picture</label>
                                  <div class="controls">
                                    <input class="input-file uniform_on" id="fileInput" type="file">
                                  </div>
                                </div>  
    						  </div>
                              
                            </div> 
                            
                            <div class="row-fluid custome-input">
                            	
                                <div class="control-group  span4">
                                  <label class="control-label first-label" for="typeahead">City </label>
                                  <div class="controls">
                                    <input type="text" class="span8 typeahead" id="typeahead"  data-provide="typeahead" data-items="4" data-source=''>
                                  </div>
                                </div>
                              
                                <div class="control-group span3">
                                  <label class="control-label" for="typeahead">State </label>
                                  <div class="controls">
                                    <input type="text" class="span12 typeahead" id="typeahead"  data-provide="typeahead" data-items="4" data-source='["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Dakota","North Carolina","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"]'>
                                  </div>
                                </div>
                                        
                                <div class="control-group span3">
                                  <label class="control-label" for="typeahead">Zip </label>
                                  <div class="controls">
                                    <input type="text" class="span7 typeahead" id="typeahead"  data-provide="typeahead" data-items="4" data-source='["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Dakota","North Carolina","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"]'>
                                  </div>
                                </div>
                                
                            </div>
           
                            <div class="form-actions text-right clearfix">
                              <a href="vendor-profile-2.html" type="button" class="btn btn-success">Save & Continue</a>
                               <button type="submit" class="btn btn-info">Save & Exit</button>
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