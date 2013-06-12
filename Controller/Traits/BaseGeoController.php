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
     * @return \Ivory\GoogleMap\Map 
     */
    public function getMap($setting = array()) {
        $defaults = $this->container->getParameter('ephp_geo.map');
        $params = array_merge($defaults, $setting);
        if(isset($setting['mapTypeControl'])) {
            $params['mapTypeControl'] = array_merge($defaults['mapTypeControl'], $setting['mapTypeControl']);
        }
        if(isset($setting['overviewMapControl'])) {
            $params['overviewMapControl'] = array_merge($defaults['overviewMapControl'], $setting['overviewMapControl']);
        }
        if(isset($setting['panControl'])) {
            $params['panControl'] = array_merge($defaults['panControl'], $setting['panControl']);
        }
        if(isset($setting['rotateControl'])) {
            $params['rotateControl'] = array_merge($defaults['rotateControl'], $setting['rotateControl']);
        }
        if(isset($setting['scaleControl'])) {
            $params['scaleControl'] = array_merge($defaults['scaleControl'], $setting['scaleControl']);
        }
        if(isset($setting['streetViewControl'])) {
            $params['streetViewControl'] = array_merge($defaults['streetViewControl'], $setting['streetViewControl']);
        }
        if(isset($setting['zoomControl'])) {
            $params['zoomControl'] = array_merge($defaults['zoomControl'], $setting['zoomControl']);
        }

        $map = $this->get('ivory_google_map.map');
        /* @var $map \Ivory\GoogleMap\Map */

        $map->setJavascriptVariable($params['name']);
        $map->setHtmlContainerId($params['name'] . '_canvas');
        $map->setAsync($params['async']);
        $map->setCenter($params['center'][0], $params['center'][1], true);
        $map->setLanguage($params['language']);
        $map->setStylesheetOption('width', $params['width']);
        $map->setStylesheetOption('height', $params['height']);

        if ($params['backgroundColor']) {
            $map->setMapOption('backgroundColor', $params['backgroundColor']);
        }
        if ($params['disableDefaultUI']) {
            $map->setMapOption('disableDefaultUI', $params['disableDefaultUI']);
        }
        if ($params['draggable']) {
            $map->setMapOption('draggable', $params['draggable']);
        }
        if ($params['draggableCursor']) {
            $map->setMapOption('draggableCursor', $params['draggableCursor']);
        }
        if ($params['draggingCursor']) {
            $map->setMapOption('draggingCursor', $params['draggingCursor']);
        }
        if ($params['heading']) {
            $map->setMapOption('heading', $params['heading']);
        }
        if ($params['keyboardShortcuts']) {
            $map->setMapOption('keyboardShortcuts', $params['keyboardShortcuts']);
        }
        if ($params['mapMaker']) {
            $map->setMapOption('mapMaker', $params['mapMaker']);
        }

        $map->setMapOption('mapTypeId', $params['mapTypeControl']['id']);
        $map->setMapOption('mapTypeControl', $params['mapTypeControl']['enable']);
        if ($params['mapTypeControl']['enable']) {
            $map->setMapTypeControl(
                    $params['mapTypeControl']['types'], 
                    $params['mapTypeControl']['position'], 
                    $params['mapTypeControl']['style']
            );
        }
        
        if ($params['noClear']) {
            $map->setMapOption('noClear', $params['noClear']);
        }

        $map->setMapOption('overviewMapControl', $params['overviewMapControl']['enable']);
        if ($params['overviewMapControl']['enable']) {
            $map->setOverviewMapControl(new \Ivory\GoogleMap\Controls\OverviewMapControl($params['overviewMapControl']['open']));
        }

        $map->setMapOption('panControl', $params['panControl']['enable']);
        if ($params['panControl']['enable']) {
            $map->setPanControl(new \Ivory\GoogleMap\Controls\PanControl($params['panControl']['position']));
        }

        $map->setMapOption('rotateControl', $params['rotateControl']['enable']);
        if ($params['rotateControl']['enable']) {
            $map->setPanControl(new \Ivory\GoogleMap\Controls\RotateControl($params['rotateControl']['position']));
        }
        
        $map->setMapOption('scaleControl', $params['scaleControl']['enable']);
        if ($params['scaleControl']['enable']) {
            $map->setScaleControl(new \Ivory\GoogleMap\Controls\ScaleControl($params['scaleControl']['position'], $params['scaleControl']['style']));
        }

        $map->setMapOption('streetViewControl', $params['streetViewControl']['enable']);
        if ($params['streetViewControl']['enable']) {
            $map->setStreetViewControl(new \Ivory\GoogleMap\Controls\StreetViewControl($params['streetViewControl']['position']));
        }
        
        if ($params['styles']) {
            $map->setMapOption('styles', $params['styles']);
        }
        
        if ($params['tilt']) {
            $map->setMapOption('tilt', $params['tilt']);
        }
        $map->setMapOption('zoomControl', $params['zoomControl']['enable']);
        if ($params['zoomControl']['enable']) {
            $map->setZoomControl($params['zoomControl']['position'], $params['zoomControl']['style']);
        }

        if ($params['zoomControl']['auto']) {
            $map->setAutoZoom($params['zoomControl']['auto']);
        }
        if ($params['zoomControl']['min']) {
            $map->setMapOption('minZoom', $params['zoomControl']['min']);
        }
        if ($params['zoomControl']['max']) {
            $map->setMapOption('maxZoom', $params['zoomControl']['max']);
        }
        if ($params['zoomControl']['disableDoubleClickZoom']) {
            $map->setMapOption('disableDoubleClickZoom', $params['zoomControl']['disableDoubleClickZoom']);
        }
        if ($params['zoomControl']['zoom']) {
            $map->setMapOption('zoom', $params['zoomControl']['zoom']);
        }
        if ($params['zoomControl']['scrollwheel']) {
            $map->setMapOption('scrollwheel', $params['zoomControl']['scrollwheel']);
        }

        return $map;
    }

    /**
     * Requests the ivory google map service
     *
     * @return \Ivory\GoogleMap\Overlays\Marker
     */
    public function getMarker($setting = array()) {
        $params = array_merge($this->container->getParameter('ephp_geo.marker'), $setting);
        $marker = $this->get('ivory_google_map.marker');
        /* @var $marker \Ivory\GoogleMap\Overlays\Marker */
        
        $marker->setJavascriptVariable($params['name']);
        $marker->setPosition($params['position'][0], $params['position'][1], true);
        $marker->setOption('visible', $params['visible']);
        
        if ($params['animation']) {
            $marker->setOption('animation', $params['animation']);
        }
        if ($params['clickable']) {
            $marker->setOption('clickable', $params['clickable']);
        }
        if ($params['cursor']) {
            $marker->setOption('cursor', $params['cursor']);
        }
        if ($params['draggable']) {
            $marker->setOption('draggable', $params['draggable']);
        }
        if ($params['flat']) {
            $marker->setOption('flat', $params['flat']);
        }
        if ($params['icon']) {
            $marker->setOption('icon', $params['icon']);
        }
        if ($params['optimized']) {
            $marker->setOption('optimized', $params['optimized']);
        }
        if ($params['raiseOnDrag']) {
            $marker->setOption('raiseOnDrag', $params['raiseOnDrag']);
        }
        if ($params['crossOnDrag']) {
            $marker->setOption('crossOnDrag', $params['crossOnDrag']);
        }
        if ($params['shadow']) {
            $marker->setOption('shadow', $params['shadow']);
        }
        if ($params['shape']) {
            $marker->setOption('shape', $params['shape']);
        }
        if ($params['title']) {
            $marker->setOption('title', $params['title']);
        }
        if ($params['zIndex']) {
            $marker->setOption('zIndex', $params['zIndex']);
        }
        
        return $marker;
    }

    /**
     * Requests the ivory google map service
     *
     * @return \Ivory\GoogleMap\Overlays\Circle
     */
    public function getCircle($setting = array()) {
        $params = array_merge($this->container->getParameter('ephp_geo.circle'), $setting);
        $circle = $this->get('ivory_google_map.circle');
        /* @var $circle \Ivory\GoogleMap\Overlays\Circle */
        
        $circle->setJavascriptVariable($params['name']);
        $circle->setCenter($params['center'][0], $params['center'][1], true);
        $circle->setOption('visible', $params['visible']);
        
        if ($params['clickable']) {
            $circle->setOption('clickable', $params['clickable']);
        }
        if ($params['draggable']) {
            $circle->setOption('draggable', $params['draggable']);
        }
        if ($params['editable']) {
            $circle->setOption('editable', $params['editable']);
        }
        if ($params['fillColor']) {
            $circle->setOption('fillColor', $params['fillColor']);
        }
        if ($params['fillOpacity']) {
            $circle->setOption('fillOpacity', $params['fillOpacity']);
        }
        if ($params['radius']) {
            $circle->setOption('radius', $params['radius']);
        }
        if ($params['strokeColor']) {
            $circle->setOption('strokeColor', $params['strokeColor']);
        }
        if ($params['strokeOpacity']) {
            $circle->setOption('strokeOpacity', $params['strokeOpacity']);
        }
        if ($params['strokePosition']) {
            $circle->setOption('strokePosition', $params['strokePosition']);
        }
        if ($params['strokeWeight']) {
            $circle->setOption('strokeWeight', $params['strokeWeight']);
        }
        if ($params['zIndex']) {
            $circle->setOption('zIndex', $params['zIndex']);
        }
        
        return $circle;
    }
    
}