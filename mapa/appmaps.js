var map,location_timeout,navigatorLAT,navigatorLNG,markerGPS;
var marker_navigatorLAT, marker_navigatorLNG;
//var fetchPins = 3000;
//var limitPins = 6000;
//var indexPins = 0;
var foundPins = 0;
var mapZoom = 13;
var distanceUpdatePins = 40;
var markers = [];
var loader = $('#loader');	
var infoRequest = $('#infoRequest');

function getLocation() {
	location_timeout = setTimeout("showError(1)", 6000);
	
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        showError(1);
    }
}
function showPosition(position) {
	clearTimeout(location_timeout);	
    navigatorLAT = position.coords.latitude;
	navigatorLNG = position.coords.longitude;	
	console.log("showPosition, lat: " + navigatorLAT + ", lng: " + navigatorLNG);	
	initializeMap();
}
function showError(error) {
    clearTimeout(location_timeout);	
	//console.log("showError...");	
	//alert("Não foi possível determinar sua localização. Iremos começar em Fortaleza / Ceará")
	navigatorLAT = -3.76999;
	navigatorLNG = -38.52562;
	mapZoom = 12;
	initializeMap();
}

function initializeMap() {
	
	console.log("initializeMap...");
	var input = $("#pac-input")[0];
	var mapOptions = {
		center: new google.maps.LatLng(navigatorLAT, navigatorLNG),
		zoom: mapZoom,
		zoomControl: true,
		zoomControlOptions: {
			position: google.maps.ControlPosition.LEFT_CENTER
		},
		mapTypeControl: true,
		mapTypeControlOptions: {
			position: google.maps.ControlPosition.RIGHT_BOTTOM
		},
		streetViewControl: true,
		streetViewControlOptions: {
			position: google.maps.ControlPosition.RIGHT_TOP
		}
	};
	map = new google.maps.Map($("#map-canvas")[0], mapOptions);
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
	map.controls[google.maps.ControlPosition.LEFT_TOP].push(loader[0]);
	map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(infoRequest[0]);  
  
	markerGPS = new google.maps.Marker({
		map:map,
		draggable:false,
		animation: google.maps.Animation.DROP,
		position: new google.maps.LatLng(navigatorLAT, navigatorLNG),		
		icon: 'assets/pin_gps.png'
	});	
	
	var infowindow = new google.maps.InfoWindow();
	var marker = new google.maps.Marker({
		map: map,
		anchorPoint: new google.maps.Point(0, -29)
	});
	
	var autocomplete = new google.maps.places.Autocomplete(input, 
		{ componentRestrictions: {'country': 'br'} } 
	);
	autocomplete.bindTo('bounds', map);
	autocomplete.addListener('place_changed', function() {
		
		markerGPS.setMap(null);
		infowindow.close();
		marker.setVisible(false);
		var place = autocomplete.getPlace();
		if (!place.geometry) {
			window.alert("Autocomplete's returned place contains no geometry");
			return;
		}

		// If the place has a geometry, then present it on a map.
		if (place.geometry.viewport) {
			map.fitBounds(place.geometry.viewport);
		} else {
			map.setCenter(place.geometry.location);
			map.setZoom(15);  // Why 17? Because it looks good.
		}
		marker.setIcon(/** @type {google.maps.Icon} */({
			url: place.icon,
			size: new google.maps.Size(71, 71),
			origin: new google.maps.Point(0, 0),
			anchor: new google.maps.Point(17, 34),
			scaledSize: new google.maps.Size(35, 35)
		}));
		marker.setPosition(place.geometry.location);
		marker.setVisible(true);

		var address = '';
		if (place.address_components) {
			address = [
				(place.address_components[0] && place.address_components[0].short_name || ''),
				(place.address_components[1] && place.address_components[1].short_name || ''),
				(place.address_components[2] && place.address_components[2].short_name || '')
			].join(' ');
		}

		infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
		infowindow.open(map, marker);
	});

  	$("#pac-input").show();
	
	
	request();
	
	map.addListener('idle', function(){
		var c = map.getCenter();
		var distance = getDistance(c.lat(), c.lng());
		
		
		if(distance > distanceUpdatePins){
			navigatorLAT = c.lat();
			navigatorLNG = c.lng();
			clearRequest();
		}
			
		
		console.log(c.lat(), c.lng(), distance);
		
		markerGPS.setMap(map);
		markerGPS.setPosition(new google.maps.LatLng(c.lat(), c.lng()));
		
	});	
	
}

function clearRequest(){
	//indexPins = 0;
	for(var i=0;i<markers.length;i++){
		markers[i].setMap(null);
	}
	markers = [];
	request();
}

function request(){	

	console.log('request...');

	//if(limitPins == indexPins)
		//return;
		
	$.ajax({
		url: "load.php",
		type: "POST",
		dataType: "json",
		data: { lat: navigatorLAT, lng: navigatorLNG },
		//data: { lat: navigatorLAT, lng: navigatorLNG, page: indexPins++, limit: fetchPins },
		success: function(data){			
			populate(data);
			//foundPins += data.length;
			//if(data.length == fetchPins)
			//	request();
		}
	});

}



function populate(data){
	
	console.log("populate...");
	infoRequest.show();
	infoRequest.html("<b>" + data.length + " Instituições encontradas</b><br><i>Distância / Raio 100Km</i>");	
	
	var infoWidth = parseInt(infoRequest.css('width'), 10);
	var documentWidth = $(document).width();
	infoRequest.css('left', (documentWidth / 2) - (infoWidth / 2));
	
	for(var i=0,len=data.length; i<len; i++){
		
		/*var infoWindow = new google.maps.InfoWindow({
			content: "<h4><b>" + data[i].nm_local + "</b></h4>" +
			"<p>Contatos:<br> " + data[i].ds_contato + "</p>" +
			"<button type='button' class='btn btn-primary' onclick='openInfoModal(" + data[i].ci_local + ")'><i class='fa fa-street-view'></i> Mais Detalhes</button>"
		});*/
		var marker = new google.maps.Marker({
			map:map,
			draggable:false,
			position: new google.maps.LatLng(data[i].nr_lat, data[i].nr_lng),
			title: data[i].nm_local
		});
		//marker.set("infoWindow", infoWindow);
		marker.set("id", data[i].ci_local);
		marker.addListener('click', function() {
			//this.get("infoWindow").open(map, this);
			var id = this.get("id");
			
			marker_navigatorLAT = this.getPosition().lat();
			marker_navigatorLNG = this.getPosition().lng();
			
			openInfoModal(id);
		});
		markers.push(marker);
	}
	
}

function openInfoModal(id){
	$('.modal-id-local').html('');
	$('#modalInfo .modal-title').html('Aguarde...');
	$('#modalInfo .modal-descr').html('');
	$('#modalInfo .modal-endereco').html('');
	$('#modalInfo .modal-modalidades').html('');
	$('#modalInfo .modal-contato').html('');
	$('#modalInfo .modal-view').html('');
	$('#modalInfo .modal-date-create').html('');
	$('#modalInfo .modal-date-edit').html('');
	$('#modalInfo .modal-title').html();
	$('#modalInfo .carousel-inner').html('<div class="item active"></div>');
	$('#modalInfo').modal();	
	$.ajax({
		url: "load_local.php",
		type: "POST",
		dataType: "json",
		data: { id: id },
		success: function(data){	
			var local = data.local;
			var fotos = data.fotos;
			console.log(data);
			$('.modal-id-local').html(id);
			$('#modalInfo .modal-title').html(local.nm_local);
			$('#modalInfo .modal-descr').html(local.ds_local);
			$('#modalInfo .modal-endereco').html(local.endereco + ', ' + local.nm_municipio + ' - ' + local.sg_estado);
			$('#modalInfo .modal-modalidades').html(data.modalidades);
			$('#modalInfo .modal-contato').html(local.ds_contato);
			$('#modalInfo .modal-view').html(local.nr_view);
			$('#modalInfo .modal-date-create').html(local.date_create);
			$('#modalInfo .modal-date-edit').html(local.date_edit);

			var count = 1;
			var strFotos = '';
			
			strFotos = '<div class="item active text-center">';
			for(var i=0;i<fotos.length;i++){
				if(count == 4){
					strFotos += '</div><div class="item text-center">';
					count = 1;
				}
				
				strFotos += '<a href="img.php?hash=' + fotos[i].ds_hash + '" data-lightbox="fotos-local"><img src="img.php?hash=' + fotos[i].ds_hash + '&thumb=1" style="display:unset; width:140px; padding:5px;"></a><div class="clearfix visible-sm-block visible-md-block visible-lg-block"></div>';					
				
				count++;
			}
			strFotos += '</div>';
			
			$('#modalInfo .carousel-inner').html(strFotos);

		
		}
	});
	
}

//function openCommentModal(id){
//	$('#modalComment').modal();
//}

//function openDenunciaModal(id){
//	$('#modalDenuncia').modal();
//}

//function openGestorModal(id){
//	$('#modalGestor').modal();
//}


$(function(){
	
	$(document).ajaxStart(function(){
		loader.show();
	});	
	$(document).ajaxStop(function(){
		loader.hide();
	});
	
	
	
	//openInfoModal(1);
	getLocation();
	
});

function myNavFunc(){
    if( (navigator.platform.indexOf("iPhone") != -1) 
        || (navigator.platform.indexOf("iPod") != -1)
        || (navigator.platform.indexOf("iPad") != -1))
         window.open("maps://maps.google.com/maps?daddr=" + marker_navigatorLAT + "," + marker_navigatorLNG + "&amp;ll=");
    else
         window.open("http://maps.google.com/maps?daddr=" + marker_navigatorLAT + "," + marker_navigatorLNG + "&amp;ll=");
}


function getDistance(latitude2, longitude2){
	var latitude1 = navigatorLAT;
	var longitude1 = navigatorLNG;   
	var theta = longitude1 - longitude2;
	var distance = (Math.sin(deg2rad(latitude1)) * Math.sin(deg2rad(latitude2)))+
               (Math.cos(deg2rad(latitude1)) * Math.cos(deg2rad(latitude2)) * Math.cos(deg2rad(theta)));
	distance = Math.acos(distance); 
	distance = rad2deg(distance); 
	distance = distance * 60 * 1.1515; //Mi
	distance = distance * 1.609344; //Km
	return (Math.round(distance,2)); 
}
function rad2deg(angle){
	return angle * 57.29577951308232;
}
function deg2rad(angle){
	return angle * 0.017453292519943295;
}