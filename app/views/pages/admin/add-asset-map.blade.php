
<div id="map_canvas" style="height:250px"></div>

<script type="text/javascript">
    function loadScript(src,callback){
  
    var script = document.createElement("script");
    script.type = "text/javascript";
    if(callback)script.onload=callback;
    document.getElementsByTagName("head")[0].appendChild(script);
    script.src = src;
  }
  
  
  loadScript('//maps.googleapis.com/maps/api/js?v=3&sensor=false&callback=initialize',
              function(){});


function initialize() {
   
    


  

 // $("#state_id option:contains('{{$state}}')").each(function () {

 //                        if($(this).html()=='{{$state}}'){
 //                            $(this).attr('selected', 'selected');
 //                        }
 //                    });
 //   $('#state_id').trigger('liszt:updated');
   // $('#state_id').trigger('change');
 // var options = '';
 //    var  state_id = $('#state_id').val();
 //    $.ajax({
 //            type: 'Post',
 //            url: baseurl + '/get-cities-by-state-id',
 //            data: {
 //                state_id: state_id
 //            },
 //            cache: false,
 //            success: function(response) {
 //                var obj = JSON.parse(response);
 //                for (var i = 0; i < obj.length; i++) {
 //                    options += '<option value="' + obj[i].id + '">' + obj[i].name + '</option>';
 //                }
 //                $("#city_id").html(options);

 //                $('#city_id').trigger('liszt:updated');
 //                 $("#city_id option:contains('{{$city}}')").each(function () {

 //                        if($(this).html()=='{{$city}}'){
 //                            $(this).attr('selected', 'selected');
 //                        }
 //                    });
  
 //            }
 //        });





    var myLatlng = new google.maps.LatLng({{$latitude}},{{$longitude}});

    var mapOptions = {
          zoom: 17,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById('map_canvas'),
            mapOptions);
  
    var newc = map.getStreetView();


    console.log(newc);

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
