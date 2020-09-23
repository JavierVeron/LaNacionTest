function abrirForm() {
  $("#form").show();
  $("#search_form").hide();
  $("#btn_agregar").show();
  $("#btn_editar").show();
  $("#btn_buscar").show();
  $("#btn_eliminar").show();
  $("#resultado").html("").show();
  $("#resultado_search").html("");
}

function cerrarForm() {
  $("#id").val("");
  $("#nombre").val("");
  $("#categoria").val("");
  $("#lat").val("");
  $("#lon").val("");
  $("#resultado").html("").show();
  $("#resultado_search").html("");
  $("#form").hide();
  $("#search_form").hide();
}

function abrirSearchForm() {
  $("#form").hide();
  $("#search_form").show();
}

function cerrarSearchForm() {
  $("#search_form").hide();
}

function toggleForm() {
	$("#form").show();
}

function toggleAgregar() {
	$("#btn_agregar").toggle();
}

function toggleEditar() {
	$("#btn_editar").toggle();
}

function toggleBuscar() {
	$("#btn_buscar").toggle();
}

function toggleEliminar() {
	$("#btn_eliminar").toggle();
}

$("#btn_agregar").click(function() {
  $.ajax({
    type:'POST',
    url:'agregar',
    data:{nombre:$("#nombre").val(), categoria:$("#categoria").val(), lat:$("#lat").val(), lon:$("#lon").val()},
    success:function(data) {
      if (data.status == "ok") {
        var color = "class='alert alert-success' role='alert'";
      } else {
        var color = "class='alert alert-danger' role='alert'";
      }
    
      $("#resultado").html("<p " + color + "><small>" + data.message + "</small></p>").delay(5000).fadeOut(500);
    }
  });

  updateMap();
  cerrarForm();
});

$("#btn_editar").click(function() {
  $.ajax({
    type:'POST',
    url:'editar',
    data:{id:$("#id").val(), nombre:$("#nombre").val(), categoria:$("#categoria").val(), lat:$("#lat").val(), lon:$("#lon").val()},
    success:function(data) {
      if (data.status == "ok") {
        var color = "class='alert alert-success' role='alert'";
      } else {
        var color = "class='alert alert-danger' role='alert'";
      }
    
      $("#resultado").html("<p " + color + "><small>" + data.message + "</small></p>").delay(5000).fadeOut(500);
    }
  });

  map.data.forEach(function(feature) {
    map.data.remove(feature);
  });

  updateMap();
  cerrarForm();
});

$("#btn_buscar").click(function() {
  abrirSearchForm();
});

$("#btn_buscarPuntos").click(function() {
  $.ajax({
    type:'POST',
    url:'buscarPuntos',
    data:{lat:$("#lat").val(), lon:$("#lon").val(), cant:$("#cant").val()},
    success:function(data) {
      if (data.status == "ok") {
        var salida = "";
        $.each(data.data, function(key, value) {
          salida+= "<p><a href='javascript:panTo(" + value.lat + ", " + value.lon + ");' title='Ir al Mapa'><img src='https://img.icons8.com/ios-filled/72/pointer.png' alt='Pointer' border='0' width='24' align='absmiddle'></a> " + value.nombre + "</p>\n";
        });

        $("#resultado_search").html(salida);
      } else {
        $("#resultado_search").html("<p class='alert alert-info' role='alert'><small>" + data.message + "</small></p>");
      }
    }
  });
})

$("#btn_eliminar").click(function() {
  $.ajax({
    type:'GET',
    url:'eliminar/' + $("#id").val(),
    data:{},
    success:function(data) {
    	if (data.status == "ok") {
    		var color = "class='alert alert-success' role='alert'";
      } else {
    		var color = "class='alert alert-danger' role='alert'";
    	}
      		
      $("#resultado").html("<p " + color + "><small>" + data.message + "</small></p>").delay(5000).fadeOut(500);
    }
  });

  updateMap();
  cerrarForm();
});

function updateMap() {
  map.data.forEach(function(feature) {
    map.data.remove(feature);
  });

  map.data.loadGeoJson('/lntest/public/obtenerPuntos');
}

function placeMarkerAndPanTo(latLng, map) {
  deleteMarkers();
  addMarker(latLng);
  map.panTo(latLng);
}

function panTo(lat, long) {
  var latLng = new google.maps.LatLng(lat, long);
  map.panTo(latLng);
}

function addMarker(location) {
  const marker = new google.maps.Marker({
    position: location,
    map: map
  });
  markers.push(marker);
}

function setMapOnAll(map) {
  for (let i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

function clearMarkers() {
  setMapOnAll(null);
}

function deleteMarkers() {
  if (markers.length > 0) {
    clearMarkers();
    markers = [];
  }
}

function copiarCoordenadas() {
  var $temp = $("<input>")
  $("body").append($temp);
  $temp.val($("#lat").val() + ", " + $("#lon").val()).select();
  document.execCommand("copy");
  $temp.remove();
  $("#copiar_coords").css("font-style", "italic");
}