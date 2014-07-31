<?php

namespace JF\GeoBundle\Utility;

class Geo {

    /**
     * raggio medio della terra espresso in km
     */
    const TERRA = 6372.795477598;

    /**
     * Costruisce una static map di google
     * 
     * @param type $lat latitudine
     * @param type $lon longitudiine
     * @param type $x larghezza in pixel
     * @param type $y altezza in pixel
     * @param type $zoom zoom della mappa
     * @param type $color colore del segnalino
     * @param type $type tipo della mappa (roadmap, ...)
     * @return type
     */
    public static function staticMap($lat, $lon, $x, $y, $zoom, $color = '337FC5', $type = 'roadmap') {
        return "http://maps.google.com/maps/api/staticmap?center={$lat},{$lon}&zoom={$zoom}&size={$x}x{$y}&maptype={$type}&sensor=true&markers=color:0x{$color}|{$lat},{$lon}&markers=size:tiny";
    }

    /**
     * Calcola la distanza in km fra due punti geolocalizzati espressi in radianti
     * 
     * @param array $da array contenente (lat, lon)
     * @param array $a array contenente (lat, lon)
     * @return float distanza in km
     */
    public static function getDistanzaRad($da, $a) {
        $da['lat'] = floatval($da['lat']);
        $da['lon'] = floatval($da['lon']);
        $a['lat'] = floatval($a['lat']);
        $a['lon'] = floatval($a['lon']);
        return acos(
                        (
                        sin($da['lat']) *
                        sin($a['lat'])
                        ) + (
                        cos($da['lat']) *
                        cos($a['lat']) *
                        cos($da['lon'] - $a['lon'])
                        )
                ) * self::TERRA;
    }

    /**
     * Calcola la distanza in km fra due punti geolocalizzati espressi in gradi
     * 
     * @param array $da array contenente (lat, lon)
     * @param array $a array contenente (lat, lon)
     * @return float distanza in km
     */
    public static function getDistanza($da, $a) {
        return (
                acos(
                        (
                        sin(
                                deg2rad($da['lat'])
                        ) * sin(
                                deg2rad($a['lat'])
                        )
                        ) + (
                        cos(
                                deg2rad($da['lat'])
                        ) * cos(
                                deg2rad($a['lat'])
                        ) * cos(
                                deg2rad($da['lon'] - $a['lon'])
                        )
                        )
                )
                ) * 6372.795477598;
    }

    /**
     * Formatta mettendo l'unità di misura più adatta per una distanza
     * 
     * @param type $distanza
     * @return string
     */
    public static function km($distanza) {
        $distanza = floatval($distanza);
        if ($distanza < 0) {
            return 'n.d.';
        }
        if ($distanza < 1) {
            $distanza = $distanza * 1000;
            if ($distanza < 1) {
                return '1m';
            }
            if ($distanza < 100) {
                return round($distanza) . 'm';
            }
            return round($distanza, -1) . 'm';
        }
        if ($distanza < 10) {
            return round($distanza, 1) . 'km';
        }
        return round($distanza) . 'km';
    }

    public static $offset = 268435456;
    public static $radius = 85445659.44705395; /* $offset / pi(); */

    static function LonToX($lon) {
        return round(self::$offset + self::$radius * $lon * pi() / 180);
    }

    static function LatToY($lat) {
        return round(self::$offset - self::$radius * log((1 + sin($lat * pi() / 180)) / (1 - sin($lat * pi() / 180))) / 2);
    }

    static function XToLon($x) {
        return ((round($x) - self::$offset) / self::$radius) * 180 / pi();
    }

    static function YToLat($y) {
        return (pi() / 2 - 2 * atan(exp((round($y) - self::$offset) / self::$radius))) * 180 / pi();
    }

    static function adjustLonByPixels($lon, $delta, $zoom) {
        return self::XToLon(self::LonToX($lon) + ($delta << (21 - $zoom)));
    }

    static function adjustLatByPixels($lat, $delta, $zoom) {
        return self::YToLat(self::LatToY($lat) + ($delta << (21 - $zoom)));
    }

}
