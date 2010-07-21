<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title>Computing distance using latitude and longitude coordinates</title>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
  <style type="text/css">
  html,body{font-family: helvetica,arial,verdana,sans-serif;background: #333;font-size: 25px;color: #C9F29C}
  #map {width: 100%;height: 300px;}
  table {width: 100%;margin-top: 10px;background: #3A3B3B;padding: 0px}
  table tr td{padding: 3px}
  input[type="button"]       { cursor:pointer;border:1px solid #C9F29C;padding:5px;-moz-border-radius:4px;background:#eee;-webkit-border-radius:4px;border-radius: 4px}
  input[type="button"]:hover,input[type="submit"]:focus { border-color:#333;background:#ddd; }
  td.location{color: #fff;background: #5A9704}
  .error{background: #c00;color: #fff}
  .well{border: 1px solid #393}
  .corner{background: #098E11;}
  #result{background: #3C4A43;text-align: left;padding:2px;width:100%;margin:0}
  #ft p{color: #ccc;font-size: 12px;text-align: right}
  #ft p a{color: #ccc}
  </style>
</head>
<body>
<div id="map"></div>
<table>
<tr><td class="corner">&nbsp;</td><td class="location"><span>latitude</span></td><td class="location"><span>longitude</span></td></tr>
<tr><td class="location">First location</td><td><span id="lat1"></span></td><td><span id="lon1"></span></td></tr>
<tr><td class="location">Second location</td><td><span id="lat2"></span></td><td><span id="lon2"></span></td></tr>
<tr><td><input id="solve" type="button" value="compute distance" /></td><td>&nbsp;</td><td>&nbsp;</td></tr>
</table>
<div id="result"></div>
<div id="ft"><p>Written by <a href="http://thinkphp.ro">Adrian Statescu</a> using YUI and Yahoo Maps</p></div>
  <script type="text/javascript" src="http://api.maps.yahoo.com/ajaxymap?v=3.8&appid=YD-WkT3cCc_JXs3sln_XNAwD6tYavOOdAI6rA--"></script>
  <script type="text/javascript">

    //Create an object Map that will be placed in the object with ID 'map'
    var map = new YMap(document.getElementById('map')); 
  
    //get object from ID
    function $(id) {return document.getElementById(id);}

    //define var count and init with 0
    var count = 0;

    //create an array to contain the points of our polyline
    var polylinePoints = [];


    //start
    function startMap() {

         //add ability to change between Sat, Hybrid and Regular Maps
         map.addTypeControl();

         //add the zoom control
         map.addZoomLong();

         //add the Pan Control to have North, South , East and West directional control
         map.addPanControl();

         //specifying the Map starting location and zoom level
         map.drawZoomAndCenter("Bucharest", 3);

         YEvent.Capture(map, EventsList.MouseDoubleClick, reportPosition);

         function reportPosition(_e,_c) {

                  var mapCoordCenter = map.convertLatLonXY(map.getCenterLatLon());
 
                  var lat = _c.Lat;

                  var lon = _c.Lon;

                  var currentGeoPoint = new YGeoPoint(lat,lon); 

                  map.addMarker(currentGeoPoint);

                  if(count == 0) {

                       $('lat1').innerHTML = lat;

                       $('lat1').parentNode.className = 'well';

                       $('lat2').innerHTML = '';

                       $('lat2').parentNode.className = '';

                       $('lon1').innerHTML = lon;

                       $('lon2').innerHTML = '';

                       $('lon2').parentNode.className = '';

                       $('lon1').parentNode.className = 'well';

                       count = 1;

                  } else if(count == 1) {

                       $('lat2').innerHTML = lat;

                       $('lat2').parentNode.className = 'well';

                       $('lat1').parentNode.className = '';

                       $('lon2').innerHTML = lon;

                       $('lon2').parentNode.className = 'well';

                       $('lon1').parentNode.className = '';

                       count = 0;
                  }

                   /*
                  //draw our polylines 
                  polylinePoints.push(currentGeoPoint);

                  if(canDisplayPolylines()) {

                        map.addOverlay(new YPolyline(polylinePoints,'orange',4,0.8)); 
                  } 
                  */

         }

          /* 
          this.canDisplayPolylines = function() {

               return (polylinePoints.length > 1); 
          }
          */


    };//end function startMap

    //computing distance using latitude and longitude coordinates
    function distance(lat1,lon1,lat2,lon2) {

                //earth diameter in miles
                var R = 3960.0;

                //convert latitude and longitude to spherical coordinates in radians
                //phi = 90 - latitude
                var phi_1 = (90.0 - lat1)*Math.PI / 180.0;

                var phi_2 = (90.0 - lat2)*Math.PI / 180.0;

                var theta_1 = lon1 * Math.PI / 180.0;

                var theta_2 = lon2 * Math.PI / 180.0;

                //compute spherical distance from spherical coordinates
                var d = R * Math.acos(

                    Math.sin(phi_1) *

                    Math.sin(phi_2) *

                    Math.cos(theta_1 - theta_2) +

                    Math.cos(phi_1) *

                    Math.cos(phi_2)
                 );

                //display resuls in miles and km
                var output = formatOutput(d) + " miles or " + formatOutput(1.609344*d) + ' kilometers';

           return output;

    }//end function

 
    function addEvent(elem,evType,fn,useCapture) {

             if(elem.addEventListener) {

                return elem.addEventListener(evType,fn,useCapture);
 
             } else if(elem.attachEvent) {

                var r = elem.attachEvent('on'+evType,fn);

                return r;

             } else {

                elem['on'+evType] = fn;
             } 
    }  

    function validate(num,name,obj){

             if(isNaN(num)) {

                obj.parentNode.className = 'error';                

                alert('Invalid input '+name);

                obj.parentNode.className = ''; 

                return 1;   
             }

          return 0;
    }  


    addEvent($('solve'),'click',function(e){

             var lat1 = parseFloat($('lat1').innerHTML);

             var lon1 = parseFloat($('lon1').innerHTML);

             var lat2 = parseFloat($('lat2').innerHTML);

             var lon2 = parseFloat($('lon2').innerHTML);

             //validation

             var errorCount = 0;

             errorCount += validate(lat1,"the first latitude",$('lat1'));  

             errorCount += validate(lat2,"the second latitude",$('lat2'));

             errorCount += validate(lon1,"the first longitude",$('lon1'));  

             errorCount += validate(lon2,"the second longitude",$('lon2'));

             if(errorCount > 0) {return;}

             var response = distance(lat1,lon1,lat2,lon2);

             $('result').innerHTML = 'd = ' + response;

    },false);

    function formatOutput(num){

             if(num > 10) {return parseInt(num);}

        return num;
    }
    
    window.onload = startMap; 

  </script>
</body>
</html>