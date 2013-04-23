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
     * Requests the ivory google map geocoder
     *
     * @return \Ivory\GoogleMapBundle\Model\Services\Geocoding\Geocoder $geocoder
     */
    protected function getGeocoder() {
        return $this->get('ivory_google_map.geocoder');
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

}