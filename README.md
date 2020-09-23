Instalación:
-----------
1- Descargar de GIT el proyecto
2- Crear una BD llamada "lntest"
3- Ejecutar desde la consola "php artisan migrate"
4- Ingresar a http://localhost/lntest/public/ para poder usar al aplicación


Manual API 
----------
Nombre: Obtener Puntos
URL: http://localhost/lntest/public/obtenerPuntos
Método: GET
Descripción: Obtiene los Puntos cargados

Nombre: Agregar Punto
URL: http://localhost/lntest/public/agregar
Método: POST
Parámetros: nombre=[STRING], categoria=[Bar/Fast Food/Restaurant], lat=[COORDENADA LATITUD], lon=[COORDENADA LONGITUD]
Descripción: Agrega un nuevo Punto
Ejemplo: http://localhost/lntest/public/agregar?nombre=McDonalds&categoria=Fast Food&lat=-34.6088485&lon=-58.4755809

Nombre: Editar Punto
URL: http://localhost/lntest/public/editar
Método: POST
Parámetros: id=[Id del Punto], nombre=[STRING], categoria=[Bar/Fast Food/Restaurant], lat=[COORDENADA LATITUD], lon=[COORDENADA LONGITUD]
Descripción: Edita el Punto especificado
Ejemplo: http://localhost/lntest/public/editar?id=1&nombre=McDonalds&categoria=Fast Food&lat=-34.6088485&lon=-58.4755809

Nombre: Eliminar Punto
URL: http://localhost/lntest/public/eliminar/[id]
Método: GET
Parámetros: Id del Punto (valor numérico)
Descripción: Elimina el Punto especificado
Ejemplo: http://localhost/lntest/public/eliminar/1

Nombre: Buscar Puntos Cercanos
URL: http://localhost/lntest/public/buscarPuntos
Método: GET
Parámetros: lat=[COORDENADA LATITUD], lon=[COORDENADA LONGITUD], cant=[Valor númerico positivo]
Descripción: Buscar los Puntos Cercanos a partir de una coordenada y cantidad especificada
Ejemplo: http://localhost/lntest/public/buscarPuntos?lat=-34.610826475774736&lon=-58.470774381445324&cant=2