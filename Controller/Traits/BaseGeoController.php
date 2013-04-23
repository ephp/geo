<?php

namespace Ephp\GeoBundle\Controller\Traits;

trait BaseGeoController {

    protected function geoCode($indirizzo, $comune) {
        $result = array(
            'lat' => null,
            'lon' => null,
            'cap' => null,
            'com' => null,
        );
        if ($comune instanceof \EcoSeekr\Bundle\GeoBundle\Entity\GeoNames) {
            $result = array(
                'lat' => $comune->getLatitude(),
                'lon' => $comune->getLongitude(),
                'cap' => false,
                'com' => true,
            );
        }
        $geocoder = $this->getGeocoder();
        try {
            $response = $geocoder->geocode($indirizzo . ' - ' . $comune);
            $result = $this->getFirstResult($response);
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
    protected function getFirstResult(\Ivory\GoogleMapBundle\Model\Services\Geocoding\Result\GeocoderResponse $response) {
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
        $out['cap'] = false;
        foreach ($result->getAddressComponents() as $ac) {
            if (in_array('postal_code', $ac->getTypes())) {
                $out['cap'] = $ac->getShortName();
            }
        }
        return $out;
    }

}