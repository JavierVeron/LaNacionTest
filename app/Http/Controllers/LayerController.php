<?php

namespace App\Http\Controllers;

use App\Models\Layer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LayerController extends Controller
{
    public function obtenerPuntos() {
        $layers = DB::select("SELECT id, nombre, categoria, lat, lon FROM layers ORDER BY id");
        $features = array();

        foreach ($layers as $layer) {
            $feature = array();
            $feature["type"] = "Feature";
            $feature["id"] = $layer->id;
            $feature["properties"]["nombre"] = $layer->nombre;
            $feature["properties"]["categoria"] = $layer->categoria;
            $feature["geometry"]["type"] = "Point";
            $feature["geometry"]["coordinates"] = array($layer->lon, $layer->lat);
            $features[] = $feature;
        }

        $geojson = array("type" => "FeatureCollection", "features" => $features);

        return response()->json($geojson);
    }

    public function agregar(Request $request) {
        if ($request->nombre == "") {
            return response()->json(['status' => 'error', 'message' => 'Debe agregar un valor en el campo Nombre!']);       
        }

        if ($request->categoria == "") {
            return response()->json(['status' => 'error', 'message' => 'Debe agregar un valor en el campo Categoría!']);       
        }

        if ($request->lat == "") {
            return response()->json(['status' => 'error', 'message' => 'Debe agregar un valor en el campo Latitud!']);       
        } elseif (!is_numeric($request->lat)) {
            return response()->json(['status' => 'error', 'message' => 'La Latitud del Punto debe ser un valor numérico!']);
        }

        if ($request->lon == "") {
            return response()->json(['status' => 'error', 'message' => 'Debe agregar un valor en el campo Longitud!']);       
        } elseif (!is_numeric($request->lon)) {
            return response()->json(['status' => 'error', 'message' => 'La Longitud del Punto debe ser un valor numérico!']);
        }

        $punto = DB::insert("INSERT INTO layers (nombre, categoria, lat, lon, created_at) VALUES (?, ?, ?, ?, '" .date("Y-m-d H:i:s") ."')", [$request->nombre, $request->categoria, $request->lat, $request->lon]);

        if ($punto) {
            return response()->json(['status' => 'ok', 'message' => 'El Punto se ha agregado con éxito!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No se pudo agrego el Punto especificado!']);
        }
    }

    public function editar(Request $request) {
        if ($request->id == "") {
            return response()->json(['status' => 'error', 'message' => 'Debe agregar el Id del Punto!']);       
        } elseif (!is_numeric($request->id)) {
            return response()->json(['status' => 'error', 'message' => 'El Id del Punto debe ser un valor numérico!']);
        }

        if ($request->nombre == "") {
            return response()->json(['status' => 'error', 'message' => 'Debe agregar un valor en el campo Nombre!']);  
        }

        if ($request->categoria == "") {
            return response()->json(['status' => 'error', 'message' => 'Debe agregar un valor en el campo Categoría!']);  
        }

        if ($request->lat == "") {
            return response()->json(['status' => 'error', 'message' => 'Debe agregar un valor en el campo Latitud!']);       
        } elseif (!is_numeric($request->lat)) {
            return response()->json(['status' => 'error', 'message' => 'La Latitud del Punto debe ser un valor numérico!']);
        }

        if ($request->lon == "") {
            return response()->json(['status' => 'error', 'message' => 'Debe agregar un valor en el campo Longitud!']);       
        } elseif (!is_numeric($request->lon)) {
            return response()->json(['status' => 'error', 'message' => 'La Longitud del Punto debe ser un valor numérico!']);
        }

        $punto = DB::update("UPDATE layers SET nombre = ?, categoria = ?, lat = ?, lon = ?, updated_at = '" .date("Y-m-d H:i:s") ."' WHERE id = ?", [$request->nombre, $request->categoria, $request->lat, $request->lon, $request->id]);

        if ($punto) {
            return response()->json(['status' => 'ok', 'message' => 'El Punto se ha modificado con éxito!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No se pudo actualizar el Punto especificado!']);
        }
    }

    public function eliminar($id) {
        if ($id == "") {
            return response()->json(['status' => 'error', 'message' => 'Debe agregar el Id del Punto!']);       
        } elseif (!is_numeric($id)) {
            return response()->json(['status' => 'error', 'message' => 'El Id del Punto debe ser un valor numérico!']);
        }

        $punto = DB::delete("DELETE FROM layers WHERE id = " .$id);

        if ($punto) {
            return response()->json(['status' => 'ok', 'message' => 'El Punto se ha eliminado con éxito!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No existe el Punto especificado!']);
        }
    }

    public function buscarPuntos(Request $request) {
        if ($request->lat == "") {
            return response()->json(['status' => 'error', 'message' => 'Debe agregar un valor en el campo Latitud!']);       
        } elseif (!is_numeric($request->lat)) {
            return response()->json(['status' => 'error', 'message' => 'La Latitud del Punto debe ser un valor numérico!']);
        }

        if ($request->lon == "") {
            return response()->json(['status' => 'error', 'message' => 'Debe agregar un valor en el campo Longitud!']);       
        } elseif (!is_numeric($request->lon)) {
            return response()->json(['status' => 'error', 'message' => 'La Longitud del Punto debe ser un valor numérico!']);
        }

        if ($request->cant == "") {
            return response()->json(['status' => 'error', 'message' => 'Debe agregar un valor en el campo Cantidad!']);       
        } elseif (!is_numeric($request->cant)) {
            return response()->json(['status' => 'error', 'message' => 'La Cantidad de los Puntos debe ser un valor numérico!']);
        }
    
        $layers = DB::select("SELECT id, nombre, lat, lon FROM layers ORDER BY id");

        if (count($layers) > 0) {
            $puntos = array();

            foreach ($layers as $layer) {
                if (($request->lat != $layer->lat) && ($request->lon != $layer->lon)) {
                    $distancia = Layer::calcularDistancia($request->lat, $request->lon, $layer->lat, $layer->lon);
                    $puntos[] = array("id" => $layer->id, "nombre" => $layer->nombre, "lat" => $layer->lat, "lon" => $layer->lon, "distancia" => $distancia);
                }
            }

            if (count($puntos) > 0) {
                $puntos_ordenados = Layer::array_sort($puntos, "distancia", SORT_ASC);
                $resultados = array();
                $i = 0;

                foreach ($puntos_ordenados as $po) {
                    if ($i < $request->cant) {
                        $resultados[] = $po;
                    } else {
                        break;
                    }

                    $i++;
                }
            } else {
                return response()->json(['status' => 'error', 'message' => 'No se encontraron resultados!']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'No se encontraron resultados!']);
        }

        return response()->json(['status' => 'ok', 'data' => $resultados]);
    }
}