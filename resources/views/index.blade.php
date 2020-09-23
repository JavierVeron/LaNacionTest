<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<link rel="stylesheet" href="css/app.css">
<title>LN TEST - La Nación</title>
</head>
<body>
<div class="bgcolor-bloodorange padding">
<p align="center"><img src="images/logo.png" border="0" alt="Logo"></p>
</div>
<div class="background_header">
<div class="text-center"><img src="images/envios-diarios.jpg" border="0" alt="Envíos Diarios"></div>
</div>
<div class="bgcolor-bloodorange" style="padding:50px;">
<div class="container">
<div class="row">
<div class="col">
<h5 class="text-center" style="color:#FFFFFF;">Agregá, Editá, Eliminá y Buscá los Puntos de Interés. Hacé click en el Mapa y comenzá a jugar!</h5>
</div>
</div>
<div class="row">
<div class="col-md-9">
<div id="map"></div>
</div>
<div class="col-md-3">
<div id="form">
<div><button type="button" class="close" aria-label="Close" onclick="cerrarForm();"><span aria-hidden="true">&times;</span></button></div>
<form class="mt-5">
<h5 style="color:#FFFFFF;">Agregar/Editar Puntos</h5>
<input type="hidden" class="form-control" id="id" name="id" value="">
<div class="form-group">
<label for="titulo" style="color:#FFFFFF;">Nombre</label>
<input type="text" class="form-control" id="nombre" name="nombre" title="Nombre">
</div>
<div class="form-group">
<label for="descripcion" style="color:#FFFFFF;">Categoría</label>
<select class="form-control" id="categoria" name="categoria" title="Categoría">
<option value='Bar'>Bar</option>
<option value='Fast Food'>Fast Food</option>
<option value='Restaurant'>Restaurant</option>
</select>
</div>
<div class="form-row">
<div class="col">
<label for="lat" style="color:#FFFFFF;">Latitud</label>
<input type="text" class="form-control" id="lat" name="lat" placeholder="Latitud" title="Latitud">
</div>
<div class="col">
<label for="lon" style="color:#FFFFFF;">Longitud</label>
<input type="text" class="form-control" id="lon" name="lon" placeholder="Longitud" title="Longitud">
</div>
</div>
<div class="form-row">
<p class="text-center"><a id="copiar_coords" href='javascript:copiarCoordenadas();' style="color:#FFFFFF; text-decoration:none;"><small>[Copiar Coordenadas]</small></a></p>
</div>
<button type="button" class="btn btn-primary form-control mt-4" id="btn_agregar">Agregar</button>
<button type="button" class="btn btn-primary form-control mt-4" id="btn_editar">Editar</button>
<button type="button" class="btn btn-info form-control mt-5" id="btn_buscar">Buscar Puntos Cercanos</button>
<button type="button" class="btn btn-danger form-control mt-3" id="btn_eliminar">Eliminar</button>
</form>
</div>
<div id="search_form">
<div><button type="button" class="close" aria-label="Close" onclick="cerrarSearchForm();"><span aria-hidden="true">&times;</span></button></div>
<form>
<h5 style="color:#FFFFFF;">Buscar Puntos Cercanos</h5>
<div class="form-group">
<label for="cant" style="color:#FFFFFF;">Total de Resultados</label>
<input type="number" class="form-control" id="cant" name="cant" title="Cantidad" value="5" min="0" max="1000">
</div>
<button type="button" class="btn btn-primary form-control mt-4" id="btn_buscarPuntos">Buscar</button>
<div id="resultado_search" class="mt-5"></div>
</form>
</div>
<div id="resultado" class="mt-5"></div>
</div>
</div>
</div>
</div>
<div class="bgcolor-teal padding">
<p align="center"><img src="images/logo.png" border="0" alt="Logo"></p>
<p align="center">HOP © 2020. Todos los derechos reservados. Términos y condiciones | Politica de privacidad</p>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<div id="map"></div>
<script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVFiAyw0JVxhfqN59GneotnpydIEuaSTQ&callback=initMap&libraries=drawing"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="js/funciones.js"></script>
<script type="text/javascript">
cerrarForm();
var map;
var markers = [];

function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {center: new google.maps.LatLng(-34.6158037,-58.5033381), zoom: 12});
	map.data.loadGeoJson('/lntest/public/obtenerPuntos');

	map.data.setStyle({
		visible: true,
	  	clickeable: true
	});

	map.addListener('click', function(event) {
		abrirForm();
		placeMarkerAndPanTo(event.latLng, map);

		if ($("#id").val() == "") {
			toggleEditar();
			toggleEliminar();
		} else {
			toggleAgregar();
		}

		$("#lat").val(event.latLng.lat());
		$("#lon").val(event.latLng.lng());
	});

	map.data.addListener('click', function(event) {
		$("#id").val(event.feature.getId());
		$("#nombre").val(event.feature.getProperty('nombre'));
		$("#categoria").empty();

		var opciones = ["Bar", "Fast Food", "Restaurant"];

		$.each(opciones, function(key, value) {
  			if (value == event.feature.getProperty('categoria')) {
				var selected = " selected='selected'";
			} else {
				var selected = "";
			}

			$("#categoria").append("<option value='" + value + "'" + selected + ">" + value + "</option>");
  		});
	   		
	   	$("#lat").val(event.latLng.lat());
	   	$("#lon").val(event.latLng.lng());

	   	abrirForm();
   		toggleAgregar();
	});
}
</script>
</body>
</html>