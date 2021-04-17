var _element = null;
var coords = null;
var marker = null;
var map = null;

// function initialize() {
//   var mapOptions = {
//     center: new google.maps.LatLng(-9.4167977, -40.50351599999999), // -33.8688, 151.2195),
//     panControl: false,
//     scaleControl: false,
//     streetViewControl: false,
//     zoomControl: false,
//     mapTypeControl: false,
//     zoom: 13
//   };
//   var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

//   var input = document.getElementById('pac-input');

//   var types = document.getElementById('type-selector');
//   map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
//   map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

//   var autocompleteOptions = {
//     types: ['(cities)']
//   };
//   var autocomplete = new google.maps.places.Autocomplete(input, autocompleteOptions);
//   autocomplete.bindTo('bounds', map);

//   var infowindow = new google.maps.InfoWindow();
//   var marker = new google.maps.Marker({
//     map: map,
//     anchorPoint: new google.maps.Point(0, -29)
//   });

//   google.maps.event.addListener(autocomplete, 'place_changed', function () {
//     infowindow.close();
//     marker.setVisible(false);
//     var place = autocomplete.getPlace();
//     //     alert(place.toSource());
//     if (!place.geometry) {
//       return;
//     }

//     // If the place has a geometry, then present it on a map.
//     if (place.geometry.viewport) {
//       map.fitBounds(place.geometry.viewport);
//     } else {
//       map.setCenter(place.geometry.location);
//       map.setZoom(17); // Why 17? Because it looks good.
//     }
//     marker.setIcon( /** @type {google.maps.Icon} */ ({
//       url: place.icon,
//       size: new google.maps.Size(71, 71),
//       origin: new google.maps.Point(0, 0),
//       anchor: new google.maps.Point(17, 34),
//       scaledSize: new google.maps.Size(35, 35)
//     }));
//     marker.setPosition(place.geometry.location);
//     marker.setVisible(true);

//     var address = '';
//     if (place.address_components) {
//       address = [
//         (place.address_components[0] && place.address_components[0].short_name || ''),
//         (place.address_components[1] && place.address_components[1].short_name || ''),
//         (place.address_components[2] && place.address_components[2].short_name || '')
//       ].join(' ');
//     }
//     infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
//     infowindow.open(map, marker);

//     $.post("config/location", {
//       city: place.address_components[0].long_name,
//       state: place.address_components[2].long_name,
//       state_short: place.address_components[2].short_name,
//       country: place.address_components[3].long_name,
//       country_short: place.address_components[3].short_name
//     }, function (data) {
//       //       alert(data);
//       $("#block-map .text-info").text(data);
//     });

//     //     alert(city+state+state_short+country+country_short);
//     //     alert(place.address_components.toSource());
//     //~ alert(place.toSource());
//     //~ console.log(place);
//   });

//   // Sets a listener on a radio button to change the filter type on Places
//   // Autocomplete.
//   function setupClickListener(id, types) {
//     var radioButton = document.getElementById(id);
//     google.maps.event.addDomListener(radioButton, 'click', function () {
//       autocomplete.setTypes(types);
//     });
//   }

//   setupClickListener('changetype-all', []);
//   setupClickListener('changetype-address', ['address']);
//   setupClickListener('changetype-establishment', ['establishment']);
//   setupClickListener('changetype-geocode', ['geocode']);
// }

function inicializeLMap() {
  var location = [-9.4167977, -40.50351599999999]; // Localização padrão
  var typingTimer; // timer para controle do input

  if (coords != null) {
    location = [coords.latitude, coords.longitude];
  } 

  map = L.map('map').setView(location, 16);
  L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png').addTo(map);

  marker = L.marker(location, {draggable: true}).addTo(map);
  marker.on('dragend', function () {
    coords.latitude = marker.getLatLng().lat;
    coords.longitude = marker.getLatLng().lng;
    map.setView([coords.latitude, coords.longitude]);
    searchAddress();
  });

  $('#pac-input').on('keyup', function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(searchLocation, 1000);
  });

  $('#pac-input').on('keydown', function () {
    clearTimeout(typingTimer);
  });
}

function updateLocation(position) {
  coords = {
    latitude: position.coords.latitude,
    longitude: position.coords.longitude,
  };
}

function searchLocation() {
  var address = $('#pac-input').val();
  $.get('https://nominatim.openstreetmap.org/search?format=json&q=' + address, function(data) {
    coords = {latitude: data[0].lat, longitude: data[0].lon};
    marker.setLatLng([coords.latitude, coords.longitude]);
    map.setView([coords.latitude, coords.longitude], 16);
  }).done(function () {
    searchAddress();
  });
}

function searchAddress() {
  $.get('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + coords.latitude + '&lon=' + coords.longitude, function(reverse){
    $.post("config/location", {
      city: reverse.address.city_district,
      state: reverse.address.state,
      state_short: "", // TODO: Tratamento do estado para retorno da sua sigla
      country: reverse.address.country,
      country_short: reverse.address.country_code
    }, function (data) {
      $("#block-map .text-info").text(data);
    });
    $.post("config/street", {street: reverse.address.road, _method: "put"}, function (data) {
      $("#institution-address").text(data.street);
    });
  });
}

$(document).ready(function () {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(updateLocation);
  }
  $("#register").submit(function () {
    var password = $(this).find("[type='password']").val();
    if (password == "") {
      $(".alternate").toggleClass("visible-none");
    }
    return false;
  });

  $("#btn-back").click(function () {
    $(".alternate").toggleClass("visible-none");
  });

  $(".block-config-item").click(function () {
    $(this).next("tr").toggleClass("visible-none");
  });

  var options = {
    beforeSend: function () {},
    uploadProgress: function (event, position, total, percentComplete) {},
    success: function () {},
    complete: function (response) {
      response = response.responseText;
      if (response != "error") {
        $(_element).html(response);
        $(".block-config-item").next("tr").addClass("visible-none");
      } else {
        $("#error").html("Erro");
      }
    }
  };

  //  $("form").ajaxForm(options);
  //  $("form").submit(function(){
  //     _element = $(this).closest("tr").prev("tr.block-config-item").find("td:eq(1)");
  //     return false;
  //  });

  $("#block-map").click(function () {
    $("#block-map").unbind("click").click(function () {
      $(this).next("tr").toggleClass("visible-none");
    });
    // initialize();
    inicializeLMap();
  });
});