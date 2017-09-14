<div class="modal-dialog">
        		<div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h1>Property Detail</h1>
                    </div>
                    <div class="modal-body">
                        
                        <div class="row-fluid">
                          <div class="box span12 noMarginTop">
                            <div class="custome-form assets-form">
                              <form class="form-horizontal">
                                <fieldset>
                                  <div class="row-fluid">
                                    <div class="span6">
                                      <!-- <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Property Number:</label>
                                        <label class="control-label" for="typeahead">{!!$asset->asset_number!!}</label>
                                      </div> -->
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Property Address:</label>
                                        <label class="control-label" for="typeahead">{!!$asset->property_address!!} </label>
                                      </div>
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">City: </label>
                                        <label class="control-label" for="typeahead"> {!!$asset->city->name!!}</label>
                                      </div>
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">State:</label>
                                        <label class="control-label" for="typeahead">{!!$asset->state->name!!}</label>
                                      </div>
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Zip :</label>
                                        <label class="control-label" for="typeahead">{!!$asset->zip!!}</label>
                                      </div>
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Lockbox </label>
                                        <label class="control-label" for="typeahead">{!!$asset->lock_box!!} </label>
                                      </div>
                                 <!--      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Customer: </label>
                                        <label class="control-label" for="typeahead">{!!$asset->user->first_name." ".$asset->user->last_name!!} </label>
                                      </div> -->
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Get / Access Code: </label>
                                        <label class="control-label" for="typeahead">{!!$asset->access_code!!} </label>
                                      </div>
                                     <!--  <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Property Status: </label>
                                        <label class="control-label" for="typeahead">{!! $asset->property_status!!}</label>
                                      </div> -->
                                      <!-- <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Customer Email Adress: </label>
                                        <label class="control-label" for="typeahead">{!!$asset->user->email!!} </label>
                                      </div> -->
                                    </div>
                                    <!--/span-6-->
                                    
                                    <div class="span6">
                                      <div class="control-group row-sep">
                                        <label class="control-label" for="typeahead">Occupancy Status:</label>
                                        <label class="control-label" for="typeahead">{!!$asset->occupancy_status!!} </label>
                                      </div>
                                      
                                      
                                       <div class="control-group row-sep">

                                  <div id="map_canvas" style="height:250px"></div>

<script type="text/javascript">
    function loadScript(src,callback){
  
    var script = document.createElement("script");
    script.type = "text/javascript";
    if(callback)script.onload=callback;
    document.getElementsByTagName("head")[0].appendChild(script);
    script.src = src;
  }
  
  
  loadScript('http://maps.googleapis.com/maps/api/js?v=3&sensor=false&callback=initialize',
              function(){});


function initialize() {
     
   var myLatlng = new google.maps.LatLng({!!$asset->latitude!!},{!!$asset->longitude!!});
   
    var mapOptions = {
          zoom: 17,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById('map_canvas'),
            mapOptions);
    var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: ''
  });
    
  }

function log(str){
  document.getElementsByTagName('pre')[0].appendChild(document.createTextNode('['+new Date().getTime()+']\n'+str+'\n\n'));
}


</script>

                                          </div>
                                      
                                      <div class="clearfix"></div>
                                    </div>
                                    <!--/span-6--> 
                                    
                                  </div>
                                  <div class="row-fluid">
                                  
                                  
                                  <br>
                                  <div class="control-group">
                                    <label class="control-label">Outbuilding / Shed *</label>
                                    <label class="control-label">{!!$asset->outbuilding_shed==1? 'Yes' : 'No';!!}</label>
                                    <div style="clear:both"></div>
                                    <div class="control-group hidden-phone">
                                      <div class="controls">
                                        <label class="control-label" for="textarea2">Note:</label>
                                        <label class="control-label label-auto" for="textarea2">{!!$asset->outbuilding_shed_note!!}</label>
                                      </div>
                                    </div>
                                    <div class="control-group hidden-phone">
                                      <div class="controls">
                                        <label class="control-label" for="textarea2">Directions or Special Notes:</label>
                                        <label class="control-label label-auto" for="textarea2">{!!$asset->special_direction_note!!}</label>
                                      </div>
                                    </div>
                                  </div>
                                  <h4>Utility - On inside Property?</h4>
                                  <div class="control-group">
                                  <label class="control-label">Electric :</label>
                                  <label class="control-label">{!!$asset->electric_status==1? 'Yes' : 'No';!!}</label>
                                  <div style="clear:both"></div>
                                  <div class="control-group hidden-phone">
                                    <div class="controls">
                                      <label class="control-label" for="textarea2">Note:</label>
                                      <label class="control-label label-auto" for="textarea2">{!!$asset->utility_note!!}</label>
                                    </div>
                                  </div>
                                  <div class="control-group">
                                  <label class="control-label">Water *</label>
                                   <label class="control-label">{!!$asset->water_status==1? 'Yes' : 'No';!!}</label>
                                  <div class="control-group hidden-phone">
                                    <div class="controls">
                                      <label class="control-label" for="textarea2">Notes:</label>
                                      <label class="control-label label-auto" for="textarea2">{!!$asset->special_direction_note!!}</label>
                                    </div>
                                  </div>
                                  <div class="control-group">
                                    <label class="control-label">Gas *</label>
                                   <label class="control-label">{!!$asset->gas_status==1? 'Yes' : 'No';!!}</label>
                                    <div class="control-group hidden-phone">
                                      <div class="controls">
                                        <label class="control-label" for="textarea2">Notes:</label>
                                        <label class="control-label label-auto" for="textarea2">{!!$asset->special_direction_note!!}</label>
                                      </div>
                                    </div>
                                    <div class="control-group multiRadio">
                                      <label class="control-label">Swimming *</label>
                                    <label class="control-label">{!!$asset->swimming_pool!!}</label>
                                    
                                  </div>
                                         
                                </fieldset>
                              </form>
                            </div>
                          </div>
                          <!--/span--> 
                          
                        </div>
                        <!--/row-->
                        
                    </div>
                    <div class="modal-footer">
                        <div class="text-right">
                          <button type="button" class="btn btn-large btn-inverse" data-dismiss="modal">Close</button>
                        </div>
                    </div>
             	</div>
             </div>   