<!DOCTYPE html>
<html> 
<head> 
   <meta http-equiv="content-type" content="text/html; charset=UTF-8"/> 
   <title>Google Maps GDirections</title> 
   <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false" 
           type="text/javascript"></script> 
</head> 
<body onunload="GUnload()"> 

   <div id="map" style="width: 400px; height: 300px"></div> 

   // <script type="text/javascript"> 

   // var map = new GMap2(document.getElementById("map"));
   // var directions = new GDirections(map);
   // var isCreateHeadPoint = true;
   // var headMarker, tailMarker;

   // map.setCenter(new GLatLng(51.50, -0.12), 12);

   // GEvent.addListener(map, "click", function(overlay,point) {
   //    if (isCreateHeadPoint) {
   //       // add the head marker
   //       headMarker = new GMarker(point);
   //       map.addOverlay(headMarker);
   //       isCreateHeadPoint = false;
   //    } 
   //    else {
   //       // add the tail marker
   //       tailMarker = new GMarker(point);
   //       map.addOverlay(tailMarker);
   //       isCreateHeadPoint = true;
   //       // create a path from head to tail
   //       directions.load("from:" + headMarker.getPoint().lat()+ ", " + 
   //                       headMarker.getPoint().lng() + 
   //                       " to:" + tailMarker.getPoint().lat() + "," + 
   //                       tailMarker.getPoint().lng(), 
   //                       { getPolyline: true, getSteps: true }); 
   //    }
   // });
   // </script> 
   <?php 
$from = "7759 Dryden Way,Orlando,Florida";
$to = "1620 Mayflower Court, Winter Park 32792";
$from = urlencode($from);
$to = urlencode($to);
$data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
$data = json_decode($data);
$time = 0;
$distance = 0;
foreach($data->rows[0]->elements as $road) {
    $time += $road->duration->value;
    $distance += $road->distance->value;
}
echo "To: ".$data->destination_addresses[0];
echo "<br/>";
echo "From: ".$data->origin_addresses[0];
echo "<br/>";
echo "Time: ".$time." seconds";
echo "<br/>";
echo "Distance: ".$distance." meters";
?>
</body> 
</html>