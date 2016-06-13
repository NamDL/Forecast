<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"></script>
  	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script src="http://openlayers.org/api/OpenLayers.js"></script>
	<script src="http://momentjs.com/downloads/moment.min.js"></script>
	<script src="moment.js"></script>
	<script src="moment-timezone.js"></script>
	<title>Weather Forecast</title>
<script>
	function resettingForm(){
		$(':input', "#forecastForm").each(function() {
   		 var type = this.type;
    		var tag = this.tagName.toLowerCase(); // normalize case
    		if (type == 'text' || type == 'password' || tag == 'textarea')
      			this.value = "";    
		else if (type == 'radio'){
      			radiobtn = document.getElementById("thisRad");
			radiobtn.checked = true;		
		}
    		else if (tag == 'select')
      			this.selectedIndex = 0;
  		});	
		$("#myCurrent").empty();
		$("#myHourly").empty();
		$("#firstDay").empty();
		$("#secondDay").empty();
		$("#thirdDay").empty();
		$("#fourthDay").empty();
		$("#sixthDay").empty();
		$("#fifthDay").empty();
		$("#seventhDay").empty();
		$("#makeTabsDisappear").css("display","none");
		$("#mapDiv").html("");
		for(var k=0;k<8;k++){
			title="#title"+k;
			body="#body"+k;
			$(title).empty();
			$(body).empty();
		}
		
	

	}
	function getTime(unixTime,timezone){		
		var time=moment(unixTime*1000);
		var val=time.tz(timezone).format("hh:mm A");
		return val;		
	}
	function getDays(unixTime){
		var dateVal=new Date(unixTime*1000);
		var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
		var val=days[dateVal.getDay()];
		return val;			
	}
	
	function getIconValue(val){
		var icon;
		if(val=="clear-day"){
			icon="http://cs-server.usc.edu:45678/hw/hw8/images/clear.png";
		}
		else if(val=="clear-night"){
			icon="http://cs-server.usc.edu:45678/hw/hw8/images/clear_night.png";
		}
		else if(val=="rain"){
			icon="http://cs-server.usc.edu:45678/hw/hw8/images/rain.png";
		}
		else if(val=="snow"){
			icon="http://cs-server.usc.edu:45678/hw/hw8/images/snow.png";
		}
		else if(val=="sleet"){
			icon="http://cs-server.usc.edu:45678/hw/hw8/images/sleet.png";
		}
		else if(val=="wind"){
			icon="http://cs-server.usc.edu:45678/hw/hw8/images/wind.png";
		}
		else if(val=="fog"){
			icon="http://cs-server.usc.edu:45678/hw/hw8/images/fog.png";
		}
		else if(val=="cloudy"){
			icon="http://cs-server.usc.edu:45678/hw/hw8/images/cloudy.png";
		}
		else if(val=="partly-cloudy-day"){
			icon="http://cs-server.usc.edu:45678/hw/hw8/images/cloud_day.png";
		}
		else if(val=="partly-cloudy-night"){
			icon="http://cs-server.usc.edu:45678/hw/hw8/images/cloud_night.png";
		}
		return icon;
	}
	function getMonthsDate(unixTime){
		var dateVal=new Date(unixTime*1000);
		var Month = ["Jan","Feb","Mar","Apr","May","June","July","Aug","Sep","Oct","Nov","Dec"];
		var val=Month[dateVal.getMonth()]+" "+dateVal.getDate();
		return val;
	}

	function mapCreation(lat,long){
		try{
			var lonlat = new OpenLayers.LonLat(long,lat);
			var map = new OpenLayers.Map("mapDiv");
    			var mapnik = new OpenLayers.Layer.OSM();
			var layer_cloud = new OpenLayers.Layer.XYZ("clouds","http://${s}.tile.openweathermap.org/map/clouds/${z}/${x}/${y}.png",{
	        		isBaseLayer: false,
        		    	opacity: 0.7,
	            		sphericalMercator: true
        		});
	    		var layer_precipitation = new OpenLayers.Layer.XYZ("precipitation","http://${s}.tile.openweathermap.org/map/precipitation/${z}/${x}/${y}.png",{
        			isBaseLayer: false,
            			opacity: 0.7,
            			sphericalMercator: true
        		});
	    		map.addLayers([mapnik, layer_precipitation, layer_cloud]);
			map.setCenter(lonlat.transform('EPSG:4326','EPSG:3857'),9);
		}catch(e){
			alert("Map disappeared");
		}
	}
	function getWindSpeed(speed){
		if(unit="si"){
			speed=speed+"m/s";
		}else{
			speedspeed+"mph";
		}
		return speed;
	}
	function getHumidity(humid){
		humid=((humid*100).toFixed(2))+" %";
		return humid;
	}
	function getDewPOint(dew){
		if(unit=="si"){
			dew=dew+" &degC";
		}else{
			dew=dew+" &degF";
		}
		return dew;
	}
	function getTemp(dew){
		if(unit=="si"){
			dew=dew+" &degC";
		}else{
			dew=dew+" &degF";
		}
		return dew;
	}
	function getVisibility(data){
		if(unit=="si"){
			data=(data.toFixed(2))+" km";
		}else if(unit=="us"){
			data=(data.toFixed(2))+" mi";
		}else{
			data="";
		}
		return data;		
	}
	function getPressure(data){
		if(unit=="us"){
			data=(data.toFixed(2))+" mb";
		}else{
			data=(data.toFixed(2))+" hPa";
		}
		return data;			
	}
	function callFacebook (mainStringFb,summaryStringFb,iconFb){
		FB.ui({
          			method: 'feed',
          			name: mainStringFb,
          			link: 'http://forecast.io/',
				picture: iconFb,
          			caption: 'WEATHER INFORMATION FROM FORCAST.IO',
				description :summaryStringFb,
            			},  function(response) {
                			if (response && response.post_id) {
                      				alert('Post successfully.');
                			} else {
                    				alert('Not Posted.');
                    			}
                		}
        		);
		}
	
	function onResult(json){
		$("#makeTabsDisappear").css("display","block")
		var unit=$("input[type='radio'][name='degree']:checked");
		if(unit.val()=="Celsius"){
			unit="si"
		}else{
			unit="us"
		}		
		var icons=[];
		var jsonData = JSON.parse(json);
		mapCreation(jsonData.latitude,jsonData.longitude);

//--------------------------------------------------------------------------------------------------------------------------------------------------------
		
//----------------------------------------------------------------------------------------------------------------------------------------------------

		var precip="",iconCurrent="",icon="",chanceOfRain="",windSpeed="", dewPoint="",humidity="",visibility="",precipKey="",chanceOfRainKey="",windSpeedKey="", dewPointKey="",humidityKey="",visibilityKey="",sunRise="",sunRiseKey="",sunSet="",sunSetKey="";
		var rowString="";
		var mainStringFb="Current Weather in "+$("#city").val()+","+$("#states").val();
		var temperature=(Math.round(jsonData.currently.temperature));
		if(unit=="si"){
			temperature=temperature+"&deg C";
		}else{
			temperature=temperature+"&deg F";
		}
		var summaryStringFb=jsonData.currently.summary+","+temperature;
		var iconFb=getIconValue(jsonData.currently.icon);
		precipKey="Precipitation";
		val=jsonData.currently.precipIntensity;
		if((val>=0) && (val<0.002)){
			precip="None";
		}else if((val>=0.002) && (val<0.017)){
			precip="Very Light";
		}else if((val>=0.017) && (val<0.1)){
			precip="Light";
		}else if((val>=0.1) && (val<0.4)){
			precip="Moderate";
		}else if(val>=0.4){
			precip="Heavy";
		}
		chanceOfRainKey="Chance of Rain"
		chanceOfRain=((jsonData.currently.precipProbability)*100)+"%";
		humidityKey="Humidity";
		humidity=((jsonData.currently.humidity)*100);	
		humidity=(Math.round(humidity))+"%";
		windSpeedKey="Wind Speed";
		if(unit=="us"){
			windSpeed=jsonData.currently.windSpeed+" mph";
		}else{	
			windSpeed=(jsonData.currently.windSpeed)+" m/s";
		}
		dewPointKey="Dew Point";
		if(unit=="us"){
			dewPoint=jsonData.currently.dewPoint+"&deg F";	
		}else{
			dewPoint=jsonData.currently.dewPoint+"&deg C";
		}
		visibilityKey="Visibility";
		if(unit=="us"){
			visibility=jsonData.currently.visibility+" mi";	
		}else{
			visibility=(jsonData.currently.visibility)+" km";
		}
		iconCurrent=getIconValue(jsonData.currently.icon);
		
		var firstRowVal="",temperatureVal="",lowsAndHighs="";
		firstRowVal="<span class='summaryWhite'>"+jsonData.currently.summary+" in "+$("#city").val()+","+$("#states").val()+" </span>";
		if(unit=="us"){
			temperatureVal="<span class='bigTemp'>"+(Math.round(jsonData.hourly.data[0].temperature))+" &deg".sup()+"F".sup()+"</span>";
		}else{
			temperatureVal="<span class='bigTemp'>"+(Math.round(jsonData.hourly.data[0].temperature))+" &deg".sup()+"C".sup()+" </span>";
		}
                lowsAndHighs="<span class='blue'>L:"+jsonData.daily.data[0].temperatureMin+"&deg </span>| <span class='green'>H: "+jsonData.daily.data[0].temperatureMax+"&deg </span>";
		lowsAndHighs+="<img src='http://cs-server.usc.edu:45678/hw/hw8/images/fb_icon.png' class='img-responsive' alt='iconshouldbeHEre' width='61px' height='40px' align='right' onclick='callFacebook(\""+mainStringFb+"\",\""+summaryStringFb+"\",\""+iconFb+"\")' />";
                rowString="<tr><th rowspan='3' align='right'><img src='"+iconCurrent+"' class='img-responsive' alt='iconshouldbeHEre' width='100px' height='100px' /></th><th>"+firstRowVal+"</th></tr>";
		rowString+="<tr><th id='temp'>"+temperatureVal+"</th></tr>";
		rowString+="<tr><th>"+lowsAndHighs+"</th></tr>";
		sunRiseKey="Sunrise";
		sunRise=getTime(jsonData.daily.data[0].sunriseTime,jsonData.timezone);
		sunSetKey="Sunset";
		sunSet=getTime(jsonData.daily.data[0].sunsetTime,jsonData.timezone);
		rowString+="<tr><td>"+precipKey+"</td><td>"+precip+"</td></tr>";
		rowString+="<tr><td>"+chanceOfRainKey+"</td><td>"+chanceOfRain+"</td></tr>";
		rowString+="<tr><td>"+windSpeedKey+"</td><td>"+windSpeed+"</td></tr>";
		rowString+="<tr><td>"+dewPointKey+"</td><td>"+dewPoint+"</td></tr>";
		rowString+="<tr><td>"+humidityKey+"</td><td>"+humidity+"</td></tr>";
		rowString+="<tr><td>"+visibilityKey+"</td><td>"+visibility+"</td></tr>";
		rowString+="<tr><td>"+sunRiseKey+"</td><td>"+sunRise+"</td></tr>";
		rowString+="<tr><td>"+sunSetKey+"</td><td>"+sunSet+"</td></tr>";
		$("#myCurrent").append(rowString);


//--------------------------------------------------Each hour---------------------------------------------------------------------------------

 		var row="";
		var tempHeading="";
		if(unit=="us"){
			tempHeading="Temp(&degF)";
		}else{
			tempHeading="Temp(&degC)";
		}
		row+="<tr><th>Time</th><th>Summary</th><th>Cloud Cover</th><th>"+tempHeading+"</th><th>View Details</th></tr>";
		var timeHourly="",tempHourly="",iconHourly="",cloudHourly="";
		for(var j=1;j<25;j++){
			timeHourly=getTime(jsonData.hourly.data[j].time,jsonData.timezone);
			tempHourly=(jsonData.hourly.data[j].temperature);
			iconHourly=jsonData.hourly.data[j].icon;
			cloudHourly=((parseInt(jsonData.hourly.data[j].cloudCover))*100)+" %";
			row+="<tr>";
			row+="<td>"+timeHourly+"</td>";
			row+="<td><img src='"+getIconValue(iconHourly)+"' class='img-responsive' alt='iconshouldbeHEre' width='100px' height'100px' /></td>";
			row+="<td>"+cloudHourly+"</td>";
			row+="<td>"+tempHourly+"</td>";
			row+="<td><span class='glyphicon glyphicon-plus' data-toggle='collapse' data-target='#disappear"+j+"'></span></td></tr>";
			row+="<tr class='collapse out' id='disappear"+j+"'> <td colspan='5' style='background-color:rgb(240,240,240)'>";
			row+="<div><table class='table borederless' id='innertable'><tr><th>Wind</th><th>Humidity</th><th>Visibiity</th><th>Pressure</th></tr>";
			row+="<tr><td>"+getWindSpeed(jsonData.hourly.data[j].windSpeed)+"</td><td>"+getHumidity(jsonData.hourly.data[j].humidity)+"</td><td>"+getVisibility(jsonData.hourly.data[j].visibility)+"</td><td>"+getPressure(jsonData.hourly.data[j].pressure)+"</td></tr>";			
			row+="</table></div></td></tr>";
			//row+="</tr>";
		}
		$("#myHourly").append(row);
//--------------------------------------------------Each hour---------------------------------------------------------------------------------		
		$.each(jsonData.daily.data[1], function(key,val){
			if(key=="time"){
				days=getDays(val);
				$("#firstDay").append("<tr><td>"+days+"</td></tr>");
				val=getMonthsDate(val);
				$("#firstDay").append("<tr><td>"+val+"</td></tr>");
			} else if(key=="icon"){
				icon=getIconValue(val);
				icons[1]=icon;
				$("#firstDay").append("<tr><td align='center'><img src='"+icon+"' class='img-responsive' alt='"+val+"' width='50px' height'50px' /></td></tr>");	
			}else if(key=="temperatureMin"){
				val=Math.round(val);
				$("#firstDay").append("<tr><td>Min</td></tr>");
				$("#firstDay").append("<tr><td>Temp</td></tr>");
				$("#firstDay").append("<tr><td style='font-weight:bold; font-size:40px;'>"+val+"&deg</td></tr>");
			}else if(key=="temperatureMax"){
				val=Math.round(val);
				$("#firstDay").append("<tr><td>Max</td></tr>");
				$("#firstDay").append("<tr><td>Temp</td></tr>");
				$("#firstDay").append("<tr><td style='font-weight:bold; font-size:40px;'>"+val+"&deg</td></tr>");
			}
		});
		$.each(jsonData.daily.data[2], function(key,val){
			if(key=="time"){
				days=getDays(val);
				$("#secondDay").append("<tr><td>"+days+"</td></tr>");
				val=getMonthsDate(val);
				$("#secondDay").append("<tr><td>"+val+"</td></tr>");
			} else if(key=="icon"){
				icon=getIconValue(val);
				icons[2]=icon;
				$("#secondDay").append("<tr><td align='center'><img src='"+icon+"' class='img-responsive' alt='"+val+"' width='50px' height'50px' /></td></tr>");	
			}else if(key=="temperatureMin"){
				val=Math.round(val);
				$("#secondDay").append("<tr><td>Min</td></tr>");
				$("#secondDay").append("<tr><td>Temp</td></tr>");
				$("#secondDay").append("<tr><td style='font-weight:bold; font-size:40px;'>"+val+"&deg</td></tr>");
			}else if(key=="temperatureMax"){
				val=Math.round(val);
				$("#secondDay").append("<tr><td>Max</td></tr>");
				$("#secondDay").append("<tr><td>Temp</td></tr>");
				$("#secondDay").append("<tr><td style='font-weight:bold; font-size:40px;'>"+val+"&deg</td></tr>");
			}
		});
		$.each(jsonData.daily.data[3], function(key,val){
			if(key=="time"){
				days=getDays(val);
				$("#thirdDay").append("<tr><td>"+days+"</td></tr>");
				val=getMonthsDate(val);
				$("#thirdDay").append("<tr><td>"+val+"</td></tr>");
			} else if(key=="icon"){
				icon=getIconValue(val);
				icons[3]=icon;
				$("#thirdDay").append("<tr><td align='center'><img src='"+icon+"' class='img-responsive' alt='"+val+"' width='50px' height'50px' /></td></tr>");	
			}else if(key=="temperatureMin"){
				val=Math.round(val);
				$("#thirdDay").append("<tr><td>Min</td></tr>");
				$("#thirdDay").append("<tr><td>Temp</td></tr>");
				$("#thirdDay").append("<tr><td style='font-weight:bold; font-size:40px;'>"+val+"&deg</td></tr>");
			}else if(key=="temperatureMax"){
				val=Math.round(val);
				$("#thirdDay").append("<tr><td>Max</td></tr>");
				$("#thirdDay").append("<tr><td>Temp</td></tr>");
				$("#thirdDay").append("<tr><td style='font-weight:bold; font-size:40px;'>"+val+"&deg</td></tr>");
			}
		});
		$.each(jsonData.daily.data[4], function(key,val){
			if(key=="time"){
				days=getDays(val);
				$("#fourthDay").append("<tr><td>"+days+"</td></tr>");
				val=getMonthsDate(val);
				$("#fourthDay").append("<tr><td>"+val+"</td></tr>");
			} else if(key=="icon"){
				icon=getIconValue(val);
				icons[4]=icon;
				$("#fourthDay").append("<tr><td align='center'><img src='"+icon+"' class='img-responsive' alt='"+val+"' width='50px' height'50px' /></td></tr>");	
			}else if(key=="temperatureMin"){
				val=Math.round(val);
				$("#fourthDay").append("<tr><td>Min</td></tr>");
				$("#fourthDay").append("<tr><td>Temp</td></tr>");
				$("#fourthDay").append("<tr><td style='font-weight:bold; font-size:40px;'>"+val+"&deg</td></tr>");
			}else if(key=="temperatureMax"){
				val=Math.round(val);
				$("#fourthDay").append("<tr><td>Max</td></tr>");
				$("#fourthDay").append("<tr><td>Temp</td></tr>");
				$("#fourthDay").append("<tr><td style='font-weight:bold; font-size:40px;'>"+val+"&deg</td></tr>");
			}
		});
		$.each(jsonData.daily.data[5], function(key,val){
			if(key=="time"){
				days=getDays(val);
				$("#fifthDay").append("<tr><td>"+days+"</td></tr>");
				val=getMonthsDate(val);
				$("#fifthDay").append("<tr><td>"+val+"</td></tr>");
			} else if(key=="icon"){
				icon=getIconValue(val);
				icons[5]=icon;
				$("#fifthDay").append("<tr><td align='center'><img src='"+icon+"' class='img-responsive' alt='"+val+"' width='50px' height'50px' /></td></tr>");	
			}else if(key=="temperatureMin"){
				val=Math.round(val);
				$("#fifthDay").append("<tr><td>Min</td></tr>");
				$("#fifthDay").append("<tr><td>Temp</td></tr>");
				$("#fifthDay").append("<tr><td style='font-weight:bold; font-size:40px;'>"+val+"&deg</td></tr>");
			}else if(key=="temperatureMax"){
				val=Math.round(val);
				$("#fifthDay").append("<tr><td>Max</td></tr>");
				$("#fifthDay").append("<tr><td >Temp</td></tr>");
				$("#fifthDay").append("<tr><td style='font-weight:bold; font-size:40px;'>"+val+"&deg</td></tr>");
			}
		});
		$.each(jsonData.daily.data[6], function(key,val){
			if(key=="time"){
				days=getDays(val);
				$("#sixthDay").append("<tr><td>"+days+"</td></tr>");
				val=getMonthsDate(val);
				$("#sixthDay").append("<tr><td>"+val+"</td></tr>");
			} else if(key=="icon"){
				icon=getIconValue(val);
				icons[6]=icon;
				$("#sixthDay").append("<tr><td align='center'><img src='"+icon+"' class='img-responsive' alt='"+val+"' width='50px' height'50px' /></td></tr>");	
			}else if(key=="temperatureMin"){
				val=Math.round(val);
				$("#sixthDay").append("<tr><td>Min</td></tr>");
				$("#sixthDay").append("<tr><td>Temp</td></tr>");
				$("#sixthDay").append("<tr><td style='font-weight:bold; font-size:40px;'>"+val+"&deg</td></tr>");

			}else if(key=="temperatureMax"){
				val=Math.round(val);
				$("#sixthDay").append("<tr><td>Max</td></tr>");
				$("#sixthDay").append("<tr><td>Temp</td></tr>");	
				$("#sixthDay").append("<tr><td style='font-weight:bold; font-size:40px;'>"+val+"&deg</td></tr>");

		}
		});
		$.each(jsonData.daily.data[7], function(key,val){
			if(key=="time"){
				days=getDays(val);
				$("#seventhDay").append("<tr><td>"+days+"</td></tr>");
				val=getMonthsDate(val);
				$("#seventhDay").append("<tr><td >"+val+"</td></tr>");
			} else if(key=="icon"){
				icon=getIconValue(val);
				icons[7]=icon;
				$("#seventhDay").append("<tr><td align='center'><img src='"+icon+"' class='img-responsive' alt='"+val+"' width='50px' height'50px' /></td></tr>");	
			}else if(key=="temperatureMin"){
				val=Math.round(val);
				$("#seventhDay").append("<tr><td>Min</td></tr>");
				$("#seventhDay").append("<tr><td>Temp</td></tr>");
				$("#seventhDay").append("<tr><td style='font-weight:bold; font-size:40px;'>"+val+"&deg</td></tr>");

			}else if(key=="temperatureMax"){
				val=Math.round(val);
				$("#seventhDay").append("<tr><td>Max</td></tr>");
				$("#seventhDay").append("<tr><td>Temp</td></tr>");	
				$("#seventhDay").append("<tr><td style='font-weight:bold; font-size:40px;'>"+val+"&deg</td></tr>");

		}
		});

//-----------------------------------------------------------MODALS------------------------------------------------------------------------------------------
		for (var i = 1; i < 8; i++) {
			bodyOfModal="#body"+i; 
			titleOfModal="#title"+i;
			var visibility;
			var modalHeader= "<span class='headingForModal'>Weather in "+$("#city").val()+" on "+getMonthsDate(jsonData.daily.data[i].time)+"</span>";
			$(titleOfModal).append(modalHeader);
			if(jsonData.daily.data[i].visibility){
				visibility=getVisibility(jsonData.daily.data[i].visibility);
			}else{
				visibility="Not Available";
			}
			var modalbody="<tr><td align='center' colspan='100%'><img src='"+icons[i]+"' class='img-responsive' alt='ModalImage' width='100px' height='100px' /></td></tr>";
			var summary= getDays(jsonData.daily.data[i].time)+":<span class='summaryOrange'> "+jsonData.daily.data[i].summary+"</span>";
			modalbody+="<tr><th colspan='100%' style='text-align:center; font-size:25px;'>"+summary+"</th></tr>";
			modalbody+="<tr><td style='font-weight: bold; text-align:center'>Sunrise Time</td><td style='font-weight: bold;text-align:center;'>Sunset Time</td><td style='font-weight: bold;text-align:center;'>Humidity</td></tr>"
			modalbody+="<tr><td style='text-align:center;'>"+getTime(jsonData.daily.data[i].sunriseTime,jsonData.timezone)+"</td><td style='text-align:center;'>"+getTime(jsonData.daily.data[i].sunsetTime,jsonData.timezone)+"</td><td style='text-align:center;'>"+getHumidity(jsonData.daily.data[i].humidity)+"</td></tr>";
			modalbody+="<tr><td style='font-weight: bold; text-align:center;'>Wind Speed</td><td style='font-weight: bold; text-align:center;'>Visibility</td><td style='font-weight: bold;text-align:center;'>Pressure</td></tr>"
			modalbody+="<tr><td style='text-align:center;'>"+getWindSpeed(jsonData.daily.data[i].windSpeed)+"</td><td style='text-align:center;'>"+visibility+"</td><td style='text-align:center;'>"+getPressure(jsonData.daily.data[i].pressure)+"</td></tr>";
			$(bodyOfModal).append(modalbody);  			
		}
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
	jQuery.validator.addMethod("selectNone", function(value, element) { 
    			if (element.value == "none"){ 
      				return false; 
    			} 
    				else return true; 
  			}, 
  			"Please select an option."); 

	$(document).ready(function () {
		
		$("#forecastForm").validate({
			rules: {
				myaddress: {
					required:{
						depends:function(){
							$(this).val($.trim($(this).val()));
							return true;
						}
					}
				},
				mycity: {
					required:{
						depends:function(){
							$(this).val($.trim($(this).val()));
							return true;
						}
					}
				},
				myStates:{
					required:{
						depends:function(){
							$(this).val($.trim($(this).val()));
							return true;
						}
					}
				}		
			},
			messages:{
				mycity:"Please enter the city",
				myaddress:"Please enter the address",
				myStates: "Please select a State"
			},
			errorPlacement: function(error, element) {
				if(element.attr("name") == "mycity") {
					error.css({"color":"red"});
					error.appendTo("#errorCity");
				}
				if(element.attr("name") == "myaddress") {
					error.css({"color":"red"});
					error.appendTo("#erroraddress");
				}
				if(element.attr("name") == "myStates") {
					error.css({"color":"red"});
					error.appendTo("#errormyStates");
				}
			},
			submitHandler: function(form) {
			$.ajax({
    				type: "GET",
				data:$("#forecastForm").serialize(),
    				url: 'forecast2.php',
				success: function (json) {
        				onResult(json);
    				}
			});
  			}
		});
	});
</script>
	<style>
		body{ 
			background: transparent url("http://cs-server.usc.edu:45678/hw/hw8/images/bg.jpg") no-repeat top center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover; 
		}
		label{
			color:white;
		}
		.star{
			color:red;
		}
		.url{
			color:white;
		}
		#myCurrent th{
			background-color:rgb(244,125,125);
		}
		#myCurrent tr:nth-child(odd){
  			background-color:rgb(243,222,222);
		}
		#myCurrent tr:nth-child(even){
  			background-color:rgb(249,249,249);
		}
		#myCurrent img{
			padding-left: 20px;
		}

		#myHourl{
			text-align:center;
		}
		#myHourly th{
			background-color:rgb(44,112,171);
			color:white;
		}
		#myHourly tr{
			background-color:white;
		}
	#one{
               		background-color: #327CB7;
           	}
       
           #two{
               background-color: #EF423E;
		align:left;
           }
           
           #three{
               background-color: #E88E48;
           }
           
           #four{
               background-color: #A7A52E;
           }
           
           #five{
               background-color: #986EA8;
		align:left;
           }
       
           
           #six{
               background-color: #F57B7C;
		align:left;
           }
       
           #seven{
               background-color: #D04270;
           }
	#weekly{
		background-color:black;
	}
	.borederless th, .borederless td { 
     		border-top: none !important; 
	}
	#firstDay,#secondDay,#thirdDay,#fourthDay,#fifthDay,#sixthDay,#seventhDay{
		//font-weight:bold;
		font-size:13px;
		text-align:center;
		color:white;
	}
	
	
	
	#makeTabsDisappear{
		display:none;
	}
	
	
	#mapDiv{
		height:460px;
		width: 100x;
	}
	#forecastForm{
		background-color: rgba(0,0,0,0.2);
	}
	.blue{
		color:blue;
	}
	.green{
		color:green;
	}
	.bigTemp{
		color:white;
		font-size:50px;
		
	}
	#mycurrent th{
		text-align:center;
	}
	.summaryWhite{
		color:white;
	}
	
	#innertable td{
		background-color:rgb(240,240,240);
		color:black;
		text-align:center;
	}
	#innertable th{
		background-color:white ;
		color:black;
		text-align:center;
	}
	.summaryorange{
		color:rgb(255,166,0);
	}
	.headingForModal{
		font-weight: bold;
	}
	
	.line{
		color:white;
		height:3px;
	}
	#tabsId li a{
		color: rgb(44,112,171);
		background-color:white;
		//padding: 5px,15px;
	}
	#tabsId .nav-pills > li.active > a,
	#tabsId .nav-pills > li.active > a:hover,
	#tabsId .nav-pills > li.active > a:focus{
		color:white;
		background-color:#428bca;
	}
	
		
	</style>
</head>
<body>
<script>
	window.fbAsyncInit = function() {
    	FB.init({
      		appId      : '1633757200214499',
      		xfbml      : true,
      		version    : 'v2.5'
    	});
	
  	};

  	(function(d, s, id){
     		var js, fjs = d.getElementsByTagName(s)[0];
     		if (d.getElementById(id)) {return;}
     		js = d.createElement(s); js.id = id;
     		js.src = "//connect.facebook.net/en_US/sdk.js";
     		fjs.parentNode.insertBefore(js, fjs);
   	}(document, 'script', 'facebook-jssdk'));
</script>
<h1 class="text-center"> FORECAST SEARCH</h1>
<div class="container">
<form class="form-inline" id="forecastForm" method="get">
<div class="form-group">
	<label for="address">Street Address:<span class="star">*</span></label>
	<br/>
	<input type="text" class="form-control" id="address" name="myaddress"><br/>
	<div id="erroraddress">&nbsp</div >
</div>

<div class="form-group">
	<label for="city">City:<span class="star">*</span></label> 
	<br/>
	<input type="text" class="form-control" id="city" name="mycity"><br/>
	<div id="errorCity">&nbsp</div >
</div>
<div class="form-group">
    <label for="myStates">State:<span class="star">*</span></label>
    <br/>
    <select class="form-control" name="myStates" id="states">
			<option value="">Select a State...</option>
			<option value="AL">Alabama</option>
			<option value="AK">Alaska</option>
			<option value="AZ">Arizona</option>
			<option value="AR">Arkansas</option>
			<option value="CA">California</option>
			<option value="CO">Colorado</option>
			<option value="CT">Connecticut</option>
			<option value="DE">Delaware</option>
			<option value="DC">District Of Columbia</option>
			<option value="FL">Florida</option>
			<option value="GA">Georgia</option>
			<option value="HI">Hawaii</option>
			<option value="ID">Idaho</option>
			<option value="IL">Illinois</option>
			<option value="IN">Indiana</option>
			<option value="IA">Iowa</option>
			<option value="KS">Kansas</option>
			<option value="KY">Kentucky</option>
			<option value="LA">Louisiana</option>
			<option value="ME">Maine</option>
			<option value="MD">Maryland</option>
			<option value="MA">Massachusetts</option>
			<option value="MI">Michigan</option>
			<option value="MN">Minnesota</option>
			<option value="MS">Mississippi</option>
			<option value="MO">Missouri</option>
			<option value="MT">Montana</option>
			<option value="NE">Nebraska</option>
			<option value="NV">Nevada</option>
			<option value="NH">New Hampshire</option>
			<option value="NJ">New Jersey</option>
			<option value="NM">New Mexico</option>
			<option value="NY">New York</option>
			<option value="NC">North Carolina</option>
			<option value="ND">North Dakota</option>
			<option value="OH">Ohio</option>
			<option value="OK">Oklahoma</option>
			<option value="OR">Oregon</option>
			<option value="PA">Pennsylvania</option>
			<option value="RI">Rhode Island</option>
			<option value="SC">South Carolina</option>
			<option value="SD">South Dakota</option>
			<option value="TN">Tennessee</option>
			<option value="TX">Texas</option>
			<option value="UT">Utah</option>
			<option value="VT">Vermont</option>
			<option value="VA">Virginia</option>
			<option value="WA">Washington</option>
			<option value="WV">West Virginia</option>
			<option value="WI">Wisconsin</option>
			<option value="WY">Wyoming</option>    				
	</select><br/>
	<div id="errormyStates">&nbsp</div > 
</div>
<div class="form-group">
	<label for="degree">Degree<span class="star">*</span></label>
	<br/>
	<div class="radio-inline">
		<label><input type="radio" name="degree" id="thisRad" value="Fahrenheit" checked>Fahrenheit</label>
	</div>
	<div class="radio-inline">
		<label><input type="radio" name="degree" value="Celsius">Celsius</label>
	</div>	
</div>
<div class="form-group pull-right">
	<br/>
	<div class="text-right">
		<button type="submit" class="btn btn-info" name="searchForecast"">
			<span class="glyphicon glyphicon-search"></span> Search<br/>
		</button>
		<button type="button" class="btn btn-default" name="clearForecast" onClick=resettingForm()>
			<span class="glyphicon glyphicon-refresh"></span> Clear<br/>
		</button>
	</div>
</div>
<br/>
<div class="form-group pull-right" style="text-align:right">
	<div class="col-sm-6 col-md-6" style="color:white;">
		Powered by:
	</div>
	<div class="col-sm-6 col-md-4  pull-left" style="text-align:left">
		<a class="url" href="http://forecast.io/"><img src="http://cs-server.usc.edu:45678/hw/hw8/images/forecast_logo.png" class="img-responsive" alt="Forecast.io" width="100px" height="100px"></a>
	</div>
</div>
<br/><br/>
</form>
<br/>
<span class="line"><hr/></span>
<div id="makeTabsDisappear">  
	<div>
	<ul class="nav nav-pills" role="tablist" id="tabsId">
 		<li role="presentation" class="active"><a href="#current" role="tab" data-toggle="tab">Right Now</a></li>
  		<li role="presentation"><a href="#hourly" role="tab" data-toggle="tab">Next 24 Hours</a></li>
   		<li role="presentation"><a href="#weekly" role="tab" data-toggle="tab">Next 7 Days</a></li>
    		
	</ul>
	</div>
  
  	<div class="tab-content">
    		<div role="tabpanel" class="tab-pane active" id="current">
			<div class="col-lg-6 col-sm-6 col-xs-12" style="padding: 0px">
				<table id="myCurrent" class="table borederless">
					<colwidth="60%">
					<colwidth="40%">
				</table>
			</div>
			<div class="col-lg-6 col-sm-12 col-xs-12" id="mapDiv"></div>

		</div>
    		<div role="tabpanel" class="tab-pane" id="hourly" >
			<table  class="table" id="myHourly" >
			</table>
		</div>
    		<div role="tabpanel" class="tab-pane" id="weekly">	
			<div class="col-sm-12 col-xs-12" style="background-color:black" id="">
			<div class="col-lg-2">
			</div>
			<div class="one col-xs-12 col-sm-12 col-lg-1" id="one" data-toggle="modal" data-target="#modalDay1" style="margin:5px; border-radius: 5px;">
				<table class="table borederless" id="firstDay" style="border-radius: 5px;">
				</table>
			</div>
			<div class="two col-xs-12 col-sm-12 col-lg-1" id="two" data-toggle="modal" data-target="#modalDay2" style="margin:5px; border-radius: 5px;">
				<table class="table borederless" id="secondDay" style="border-radius: 5px;">
				</table>
			</div>
			<div class="three col-xs-12 col-sm-12 col-lg-1" id="three" data-toggle="modal" data-target="#modalDay3" style="margin:5px; border-radius: 5px;">
				<table class="table borederless" id="thirdDay" style="border-radius: 5px;">
					</table>
			</div>
			<div class="four col-xs-12 col-sm-12 col-lg-1" id="four" data-toggle="modal" data-target="#modalDay4" style="margin:5px; border-radius: 5px;">
				<table class="table borederless" id="fourthDay" style="border-radius: 5px;">
				</table>
			</div>
			<div class="five col-xs-12 col-sm-12 col-lg-1" id="five" data-toggle="modal" data-target="#modalDay5" style="margin:5px; border-radius: 5px;">
				<table class="table borederless" id="fifthDay" style="border-radius: 5px;">
				</table>
			</div>
			<div class="six col-xs-12 col-sm-12 col-lg-1" id="six" data-toggle="modal" data-target="#modalDay6" style="margin:5px; border-radius: 5px;">
				<table class="table borederless" id="sixthDay" style="border-radius: 5px;">
				</table>
			</div>
			<div class="six col-xs-12 col-sm-12 col-lg-1" id="seven" data-toggle="modal" data-target="#modalDay7" style="margin:5px; border-radius: 5px;">
				<table class="table borederless" id="seventhDay" style="border-radius: 5px;">
				</table>
			
			</div>			
			</div>
		</div>    		
  	</div>

</div>

  <div class="modal fade" id="modalDay1" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="title1"></h4>
        </div>
        <div class="modal-body">
          <table class="table borederless" id="body1"></table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


<div class="modal fade" id="modalDay2" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="title2"></h4>
        </div>
        <div class="modal-body">
          <table class="table borederless" id="body2"></table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<!--Day 3-->
<div class="modal fade" id="modalDay3" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="title3"></h4>
        </div>
        <div class="modal-body">
          <table class="table borederless" id="body3"></table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<!--Day 4-->
<div class="modal fade" id="modalDay4" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="title4"></h4>
        </div>
        <div class="modal-body">
          <table class="table borederless" id="body4"></table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<!--Day 5-->
<div class="modal fade" id="modalDay5" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="title5"></h4>
        </div>
        <div class="modal-body">
          <table class="table borederless" id="body5"></table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<!--Day 6-->
<div class="modal fade" id="modalDay6" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="title6"></h4>
        </div>
        <div class="modal-body">
          <table class="table borederless" id="body6"></table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<!--Day 7-->
<div class="modal fade" id="modalDay7" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="title7"></h4>
        </div>
        <div class="modal-body">
          <table class="table borederless" id="body7"></table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>





  
</div>
</div>
</body>
</html>

