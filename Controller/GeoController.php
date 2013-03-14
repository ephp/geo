<?php

namespace Ephp\GeoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GeoController extends Controller {

    /**
     * @Route("/geo/get/comune", name="geo_search_comune_database")
     * @Template()
     */
    public function geoSearchComuneDatabaseAction() {
        $request = $this->getRequest();
        $nome = $request->get('nome');
        $maxRows = $request->get('maxRows');
        $em = $this->getEM();
        $_geo_names = $em->getRepository('Ephp\GeoBundle\Entity\GeoNames');
        $comuni = $_geo_names->cercaComune($nome);
        $out = array();
        foreach ($comuni as $comune) {
            $out[] = array(
                'id' => $comune->getGeonameid(),
                'nome' => $comune->getName(),
                'admin2_code' => $comune->getAdmin2Code(),
                'latitude' => $comune->getLatitude(),
                'longitude' => $comune->getLongitude(),
            );
        }
        echo json_encode($out);
        exit;
    }
    
    
    
    /**
     * @Route("/geo/get/nazione", name="geo_search_nazione_database", defaults={"_format"="json"}))
     * @Template()
     */
    public function geoSearchNazioneDatabaseAction() {
        $em = $this->getEM();
        $_geo_names = $em->getRepository('Ephp\GeoBundle\Entity\GeoNames');
        $nazioni = $_geo_names->cercaNazione();
        $out = array();
        foreach ($nazioni as $nazione) {
            $out[] = array(
                'id' => $nazione->getGeonameid(),
                'nome' => $nazione->getName(),
                'admin2_code' => $nazione->getAdmin2Code(),
                'latitude' => $nazione->getLatitude(),
                'longitude' => $nazione->getLongitude(),
            );
        }
        echo json_encode($out);
        exit;
    }
    
    /**
     * @Route("/geo/get/place", name="geo_search_database")
     * @Template()
     */
    public function geoSearchDatabaseAction() {
        $request = $this->getRequest();
        $nome = $request->get('nome');
        $maxRows = $request->get('maxRows');
        $em = $this->getEM();
        $_geo_names = $em->getRepository('Ephp\GeoBundle\Entity\GeoNames');
        $comuni = $_geo_names->cercaTutto($nome);
        $out = array();
        $in = array();
        foreach ($comuni as $comune) {
            $nome = $comune->getName() . ' (' . $comune->getAdmin2Code() . ')';
            if (!in_array($nome, $in)) {
                $in[] = $nome;
                $out[] = array(
                    'id' => $comune->getGeonameid(),
                    'nome' => $comune->getName(),
                    'admin2_code' => $comune->getAdmin2Code(),
                    'latitude' => $comune->getLatitude(),
                    'longitude' => $comune->getLongitude(),
                );
            }
        }
        echo json_encode($out);
        exit;
    }

    /**
     * @Route("/geo/search/comune", name="geo_search_comune")
     * @Template()
     */
    public function geoSearchComuneAction() {
        $request = $this->getRequest();
        if (!$request->getSession()->get('posizione_json', false)) {
            $latitude = $request->get('latitude');
            $longitude = $request->get('longitude');
            $em = $this->getEM();
            $_geo_names = $em->getRepository('Ephp\GeoBundle\Entity\GeoNames');
            $out = $_geo_names->getComuneProvincia($latitude, $longitude);
            $comune = $out['comune'];
            $provincia = $out['provincia'];
            switch ($request->get('output', 'json')) {
                case 'nome':
                    echo $comune->getName();
                    exit;
                case 'nome_e_provincia':
                    echo "{$comune->getName()} ({$comune->getAdmin2Code()})";
                    exit;
                case 'json':
                default:
                    $out = array(
                        'id' => $comune->getGeonameid(),
                        'nome' => $comune->getName(),
                        'admin2_code' => $comune->getAdmin2Code(),
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    );
                    $request->getSession()->set('posizione', array('lat' => $latitude, 'lon' => $longitude, 'luogo' => $comune->getName() . ' (' . $comune->getAdmin2Code() . ')', 'provincia' => $provincia->getName(), 'sigla_provincia' => $comune->getAdmin2Code()));
                    $request->getSession()->set('posizione_json', $out);
                    echo json_encode($out);
                    exit;
                    break;
            }
        } else {
            echo json_encode($request->getSession()->get('posizione_json'));
            exit;
        }
    }

    /**
     * @Route("/geo/id/comune", name="geo_search_comune_database_id")
     * @Template()
     */
    public function geoSearchComuneDatabaseIdAction() {
        $request = $this->getRequest();
        $id = $request->get('id');
        $em = $this->getEM();
        $_geo_names = $em->getRepository('Ephp\GeoBundle\Entity\GeoNames');
        $comune = $_geo_names->find($id);
        $out = array(
            'id' => $comune->getGeonameid(),
            'nome' => $comune->getName(),
            'admin2_code' => $comune->getAdmin2Code(),
            'latitude' => $comune->getLatitude(),
            'longitude' => $comune->getLongitude(),
        );
        echo json_encode($out);
        exit;
    }

    /**
     * @Route("/geo/search/comune_provincia", name="geo_search_comune_provincia")
     * @Template()
     */
    public function geoSearchComuneProvinciaAction() {
        $request = $this->getRequest();
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $em = $this->getEM();
        $_geo_names = $em->getRepository('Ephp\GeoBundle\Entity\GeoNames');
        $out = $_geo_names->getComuneProvincia($latitude, $longitude);
        $comune = $out['comune'];
        $provincia = $out['provincia'];
        switch ($request->get('output', 'json')) {
            case 'nome':
                echo $comune->getName();
                exit;
            case 'nome_e_provincia':
                echo "{$comune->getName()} ({$comune->getAdmin2Code()})";
                exit;
            case 'json':
            default:
                $out = array(
                    'comune' => array(
                        'id' => $comune->getGeonameid(),
                        'nome' => $comune->getName(),
                        'admin2_code' => $comune->getAdmin2Code(),
                        'latitude' => $comune->getLatitude(),
                        'longitude' => $comune->getLongitude(),
                    ),
                    'provincia' => array(
                        'id' => $comune->getGeonameid(),
                        'nome' => $comune->getName(),
                        'latitude' => $comune->getLatitude(),
                        'longitude' => $comune->getLongitude(),
                    )
                );
                echo json_encode($out);
                exit;
                break;
        }
    }

    /**
     * @Route("/geo/get/provincia", name="geo_search_provincia_database")
     * @Template()
     */
    public function geoSearchProvinciaDatabaseAction() {
        $request = $this->getRequest();
        $nome = $request->get('nome');
        $maxRows = $request->get('maxRows');
        $em = $this->getEM();
        $_geo_names = $em->getRepository('Ephp\GeoBundle\Entity\GeoNames');
        $province = $_geo_names->cercaProvincia($nome);
        $out = array();
        foreach ($province as $provincia) {            
            $out[] = array(
                'id' => $provincia->getGeonameid(),
                'nome' => $provincia->getName(),
                'latitude' => $provincia->getLatitude(),
                'longitude' => $provincia->getLongitude(),
                'sigla' => $provincia->getAdmin2Code(),
            );
        }
        echo json_encode($out);
        exit;
    }

    /**
     * @Route("/geo/id/provincia", name="geo_search_provincia_database_id")
     * @Template()
     */
    public function geoSearchProvinciaDatabaseIdAction() {
        $request = $this->getRequest();
        $id = $request->get('id');
        $em = $this->getEM();
        $_geo_names = $em->getRepository('Ephp\GeoBundle\Entity\GeoNames');
        $provincia = $_geo_names->find($id);
        $out = array(
            'id' => $provincia->getGeonameid(),
            'nome' => $provincia->getName(),
            'latitude' => $provincia->getLatitude(),
            'longitude' => $provincia->getLongitude(),
        );
        echo json_encode($out);
        exit;
    }

    /**
     * @Route("/geo/search/provincia", name="geo_search_provincia")
     * @Template()
     */
    public function geoSearchProvinciaAction() {
        $request = $this->getRequest();
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $em = $this->getEM();
        $_geo_names = $em->getRepository('Ephp\GeoBundle\Entity\GeoNames');
        $comune = $_geo_names->getProvincia($latitude, $longitude);
        switch ($request->get('output', 'json')) {
            case 'nome':
                echo $comune->getName();
                exit;
            case 'json':
            default:
                $out = array(
                    'id' => $comune->getGeonameid(),
                    'nome' => $comune->getName(),
                    'latitude' => $comune->getLatitude(),
                    'longitude' => $comune->getLongitude(),
                );
                echo json_encode($out);
                exit;
                break;
        }
    }

    /**
     * @return \Doctrine\ORM\EntityManager 
     */
    private function getEm() {
        return $this->getDoctrine()->getEntityManager();
    }

    /**
     * Requests the ivory google map geocoder
     *
     * @return Ivory\GoogleMapBundle\Model\Services\Geocoding\Geocoder $geocoder
     */
    private function getGeocoder() {
        return $this->get('ivory_google_map.geocoder');
    }

    /**
     * @Route("/geo/coordinate", name="geo_geocode")
     * @Template()
     */
    public function geoCoordinateAction() {
        $request = $this->getRequest();
        $indirizzo = $request->get('indirizzo');
        $comune = $request->get('comune');
        $geocoder = $this->getGeocoder();
//        $provider = new \Geocoder\Provider\GoogleMapsProvider(new \Geocoder\HttpAdapter\BuzzHttpAdapter());
//        $geocoder->registerProvider($provider);
        try {
            $response = $geocoder->geocode($indirizzo . ' - ' . $comune);
            $result = $this->getFirstResult($response);
            echo json_encode($result);
            exit;
        } catch (\Exception $e) {
            try {
                $response = $geocoder->geocode($comune);
                $result = $this->getFirstResult($response);
                echo json_encode($result);
                exit;
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

    /**
     *
     * @param Ivory\GoogleMapBundle\Model\Services\Geocoding\Result\GeocoderResponse $response
     * @return array
     */
    private function getFirstResult(\Ivory\GoogleMapBundle\Model\Services\Geocoding\Result\GeocoderResponse $response) {
        $out = array();
//        $result = new \Ivory\GoogleMapBundle\Model\Services\Geocoding\Result\GeocoderResult();
        $r = $response->getResults();
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
