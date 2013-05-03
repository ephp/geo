<?php

namespace Ephp\GeoBundle\Controller\Traits;

trait BaseGeoController {

    protected function geoCode($indirizzo, $comune) {
        $result = array(
            'lat' => null,
            'lon' => null,
        );
        if ($comune instanceof \Ephp\GeoBundle\Entity\GeoNames) {
            /* @var $comune \Ephp\GeoBundle\Entity\GeoNames */
            $result['lat'] = $comune->getLatitude();
            $result['lon'] = $comune->getLongitude();
            $comune = $comune->getAsciiname();
        }
        $geocoder = $this->getGeocoder();
        try {
            $response = $geocoder->geocode($indirizzo . ' - ' . $comune);
            $geo = $this->getFirstResult($response);
            if ($geo['lat']) {
                $result['lat'] = $geo['lat'];
            }
            if ($geo['lon']) {
                $result['lon'] = $geo['lon'];
            }
        } catch (\Exception $e) {
            
        }
        return $result;
    }

    /**
     *
     * @param \Ivory\GoogleMapBundle\Model\Services\Geocoding\Result\GeocoderResponse $response
     * @return array
     */
    protected function getFirstResult(\Ivory\GoogleMap\Services\Geocoding\Result\GeocoderResponse $response) {
        $out = array();
        if ($response->getStatus() == 'OVER_QUERY_LIMIT') {
            throw new \Exception('STOP');
        }
        $r = $response->getResults();
        if (count($r) == 0) {
            throw new \Exception('No dati geolocalizzati');
        }
        $result = $r[0];

        $out['lat'] = $result->getGeometry()->getLocation()->getLatitude();
        $out['lon'] = $result->getGeometry()->getLocation()->getLongitude();
        foreach ($result->getAddressComponents() as $ac) {
            if (in_array('postal_code', $ac->getTypes())) {
                $out['cap'] = $ac->getShortName();
            }
        }
        return $out;
    }

    /**
     * Requests the ivory google map geocoder
     *
     * @return \Ivory\GoogleMapBundle\Model\Services\Geocoding\Geocoder $geocoder
     */
    protected function getGeocoder() {
        return $this->get('ivory_google_map.geocoder');
    }

    /**
     * Requests the ivory google map service
     *
     * @return \Ivory\GoogleMapBundle\Model\Map 
     */
    private function map() {
        return $this->get('ivory_google_map.map');
    }

    /**
     * Requests the ivory google map service
     *
     * @return \Ivory\GoogleMapBundle\Model\Map 
     */
    public function getMap($dist, $lat = 41.87194, $lon = 12.56738, $name = 'map', $zoom = 7) {
        $map = $this->map();
        $map->setJavascriptVariable($name);
        $map->setHtmlContainerId($name . '_canvas');
        $map->setAsync(false);
        $map->setAutoZoom(false);
        $map->setCenter($lat, $lon, true);
        $map->setMapOption('zoom', $zoom);
        $map->setMapOption('scrollwheel', false);
        $map->setMapOption('mapTypeControl', false);
        $map->setMapOption('streetViewControl', false);
        $map->setMapOption('mapTypeId', \Ivory\GoogleMap\MapTypeId::ROADMAP);
        $map->setMapOption('zoomControl', true);
        $map->setZoomControl(\Ivory\GoogleMap\Controls\ControlPosition::RIGHT_BOTTOM, \Ivory\GoogleMap\Controls\ZoomControlStyle::LARGE);
        $map->setMapOption('panControl', true);
        $map->setPanControl(\Ivory\GoogleMap\Controls\ControlPosition::RIGHT_BOTTOM);
        $map->setStylesheetOption('width', '100%');
        $map->setStylesheetOption('height', '100%');
        $map->setLanguage('it');
        $marker = $this->getMarker($lat, $lon);
        $circle = $this->getCircle($dist, $lat, $lon);
        $circle->setOption('fillOpacity', 0.10);
        $circle->setOption('strokeOpacity', 0.20);
        $map->addMarker($marker);
        $map->addCircle($circle);
        return $map;
    }

    /**
     * Requests the ivory google map service
     *
     * @return \Ivory\GoogleMapBundle\Model\Overlays\Marker
     */
    public function getMarker($lat = 41.87194, $lon = 12.56738, $name = 'marker') {
        $marker = $this->get('ivory_google_map.marker');
        $marker->setJavascriptVariable($name);
        $marker->setPosition($lat, $lon, true);
        $marker->setOption('clickable', true);
        $marker->setOption('draggable', true);
        $marker->setOption('flat', true);
        return $marker;
    }

    /**
     * Requests the ivory google map service
     *
     * @return \Ivory\GoogleMapBundle\Model\Overlays\Circle
     */
    public function getCircle($dist, $lat = 41.87194, $lon = 12.56738, $name = 'circle') {
        $circle = $this->get('ivory_google_map.circle');
        $circle->setJavascriptVariable($name);
        $circle->setCenter($lat, $lon, true);
        $circle->setRadius($dist * 1000);
        $circle->setOption('clickable', true);
        $circle->setOption('strokeWeight', 2);
        $circle->setOption('fillOpacity', 0.25);

        return $circle;
    }

}