<?php

namespace Ephp\GeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ephp\GeoBundle\Entity\GeoNames
 *
 * @ORM\Table(name="geo_names",
 *                    indexes={
 *                      @ORM\Index(name="geo_feature_code", columns={"feature_code"}),
 *                      @ORM\Index(name="geo_lat", columns={"latitude"}),
 *                      @ORM\Index(name="geo_lon", columns={"longitude"}),
 *                      @ORM\Index(name="geo_pop", columns={"population"})
 *                    })
 * @ORM\Entity(repositoryClass="Ephp\GeoBundle\Entity\GeoNamesRepository")
 */
class GeoNames
{
    /**
     * @var bigint $geonameid
     *
     * @ORM\Column(name="geonameid", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $geonameid;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=200)
     */
    private $name;
    
    /**
     * @var string $asciiname
     *
     * @ORM\Column(name="asciiname", type="string", length=200)
     */
    private $asciiname;
    
    /**
     * @var string $alternatenames
     *
     * @ORM\Column(name="alternatenames", type="text")
     */
    private $alternatenames;

    /**
     * @var decimal $latitude
     *
     * @ORM\Column(name="latitude", type="decimal", precision=15, scale=10)
     */
    private $latitude;

    /**
     * @var decimal $longitude
     *
     * @ORM\Column(name="longitude", type="decimal", precision=15, scale=10)
     */
    private $longitude;

    /**
     * @var string $feature_class
     *
     * @ORM\Column(name="feature_class", type="string", length=1)
     */
    private $feature_class;

    /**
     * @var string $feature_code
     *
     * @ORM\Column(name="feature_code", type="string", length=10)
     */
    private $feature_code;

    /**
     * @var string $country_code
     *
     * @ORM\Column(name="country_code", type="string", length=2)
     */
    private $country_code;
    
    /**
     * @var string $ccs
     *
     * @ORM\Column(name="ccs", type="string", length=60)
     */
    private $ccs;

    /**
     * @var string $admin1_code
     *
     * @ORM\Column(name="admin1_code", type="string", length=20)
     */
    private $admin1_code;

    /**
     * @var string $admin2_code
     *
     * @ORM\Column(name="admin2_code", type="string", length=80)
     */
    private $admin2_code;

    /**
     * @var string $admin3_code
     *
     * @ORM\Column(name="admin3_code", type="string", length=20)
     */
    private $admin3_code;

    /**
     * @var string $admin4_code
     *
     * @ORM\Column(name="admin4_code", type="string", length=20)
     */
    private $admin4_code;

    /**
     * @var bigint $elevation
     *
     * @ORM\Column(name="elevation", type="bigint")
     */
    private $elevation;

    /**
     * @var bigint $dem
     *
     * @ORM\Column(name="dem", type="bigint")
     */
    private $dem;

    /**
     * @var bigint $geonameid
     *
     * @ORM\Column(name="population", type="bigint")
     */
    private $population;
    
    /**
     * @var string $timezone
     *
     * @ORM\Column(name="timezone", type="string", length=32)
     */
    private $timezone;

    /**
     * @var date $modification_date
     *
     * @ORM\Column(name="modification_date", type="date")
     */
    private $modification_date;


    public function getNomeComune() {
        return $this->getName(). ' ('.$this->getAdmin2Code().')';
    }
    
    public function __toString() {
        return $this->getGeonameid();
    }
}