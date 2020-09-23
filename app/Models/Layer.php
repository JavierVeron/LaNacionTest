<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layer extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'lat', 'lon'];

    static public function calcularDistancia($lat0, $lng0, $lat1, $lng1) {
        $rlat0 = deg2rad($lat0);
        $rlng0 = deg2rad($lng0);
        $rlat1 = deg2rad($lat1);
        $rlng1 = deg2rad($lng1);
        $latDelta = $rlat1 - $rlat0;
        $lonDelta = $rlng1 - $rlng0;
        $distancia = 6371 * 2 * asin(sqrt(cos($rlat0) * cos($rlat1) * pow(sin($lonDelta / 2), 2) + pow(sin($latDelta / 2), 2)));

        return $distancia;
    }

    static public function array_sort($array, $on, $order=SORT_ASC) {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
}
