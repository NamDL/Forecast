<?php
$geoCode="AIzaSyAxCTC6A8oB5JpAxFKdVu9MjYE2eRQM8kg";
$url="https://maps.google.com/maps/api/geocode/xml?address=".$_GET["myaddress"].",".$_GET["mycity"].",".$_GET["myStates"]."&key=".$geoCode."&sensor=false";
$url=preg_replace('/\s+/', '+', $url);
$resp=file_get_contents($url);
$xml= simplexml_load_string($resp);
if($xml->status =="ZERO_RESULTS"){
	 $jsonStr=0;
}else{
	$lat= $xml->result->geometry->location->lat;
	$lng= $xml->result->geometry->location->lng;
	$apikey="8995aa592ee5783dc854f4c85b5902fd";
	if(isset($_GET["degree"])){
		if($_GET["degree"]==="Fahrenheit")
			$unit="us";
		else
			$unit="si";
	}
	$foreUrl="https://api.forecast.io/forecast/".$apikey."/".$lat.",".$lng."?units=".$unit."&exclude=flags";
	$jsonStr=file_get_contents($foreUrl);
}
echo $jsonStr;
?>


