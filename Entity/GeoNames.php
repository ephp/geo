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

    /**
     * Set geonameid
     *
     * @param integer $geonameid
     * @return GeoNames
     */
    public function setGeonameid($geonameid)
    {
        $this->geonameid = $geonameid;
    
        return $this;
    }

    /**
     * Get geonameid
     *
     * @return integer 
     */
    public function getGeonameid()
    {
        return $this->geonameid;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return GeoNames
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set asciiname
     *
     * @param string $asciiname
     * @return GeoNames
     */
    public function setAsciiname($asciiname)
    {
        $this->asciiname = $asciiname;
    
        return $this;
    }

    /**
     * Get asciiname
     *
     * @return string 
     */
    public function getAsciiname()
    {
        return $this->asciiname;
    }

    /**
     * Set alternatenames
     *
     * @param string $alternatenames
     * @return GeoNames
     */
    public function setAlternatenames($alternatenames)
    {
        $this->alternatenames = $alternatenames;
    
        return $this;
    }

    /**
     * Get alternatenames
     *
     * @return string 
     */
    public function getAlternatenames()
    {
        return $this->alternatenames;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return GeoNames
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return GeoNames
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set feature_class
     *
     * @param string $featureClass
     * @return GeoNames
     */
    public function setFeatureClass($featureClass)
    {
        $this->feature_class = $featureClass;
    
        return $this;
    }

    /**
     * Get feature_class
     *
     * @return string 
     */
    public function getFeatureClass()
    {
        return $this->feature_class;
    }

    /**
     * Set feature_code
     *
     * @param string $featureCode
     * @return GeoNames
     */
    public function setFeatureCode($featureCode)
    {
        $this->feature_code = $featureCode;
    
        return $this;
    }

    /**
     * Get feature_code
     *
     * @return string 
     */
    public function getFeatureCode()
    {
        return $this->feature_code;
    }

    /**
     * Set country_code
     *
     * @param string $countryCode
     * @return GeoNames
     */
    public function setCountryCode($countryCode)
    {
        $this->country_code = $countryCode;
    
        return $this;
    }

    /**
     * Get country_code
     *
     * @return string 
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * Set ccs
     *
     * @param string $ccs
     * @return GeoNames
     */
    public function setCcs($ccs)
    {
        $this->ccs = $ccs;
    
        return $this;
    }

    /**
     * Get ccs
     *
     * @return string 
     */
    public function getCcs()
    {
        return $this->ccs;
    }

    /**
     * Set admin1_code
     *
     * @param string $admin1Code
     * @return GeoNames
     */
    public function setAdmin1Code($admin1Code)
    {
        $this->admin1_code = $admin1Code;
    
        return $this;
    }

    /**
     * Get admin1_code
     *
     * @return string 
     */
    public function getAdmin1Code()
    {
        return $this->admin1_code;
    }

    /**
     * Set admin2_code
     *
     * @param string $admin2Code
     * @return GeoNames
     */
    public function setAdmin2Code($admin2Code)
    {
        $this->admin2_code = $admin2Code;
    
        return $this;
    }

    /**
     * Get admin2_code
     *
     * @return string 
     */
    public function getAdmin2Code()
    {
        return $this->admin2_code;
    }

    /**
     * Set admin3_code
     *
     * @param string $admin3Code
     * @return GeoNames
     */
    public function setAdmin3Code($admin3Code)
    {
        $this->admin3_code = $admin3Code;
    
        return $this;
    }

    /**
     * Get admin3_code
     *
     * @return string 
     */
    public function getAdmin3Code()
    {
        return $this->admin3_code;
    }

    /**
     * Set admin4_code
     *
     * @param string $admin4Code
     * @return GeoNames
     */
    public function setAdmin4Code($admin4Code)
    {
        $this->admin4_code = $admin4Code;
    
        return $this;
    }

    /**
     * Get admin4_code
     *
     * @return string 
     */
    public function getAdmin4Code()
    {
        return $this->admin4_code;
    }

    /**
     * Set elevation
     *
     * @param integer $elevation
     * @return GeoNames
     */
    public function setElevation($elevation)
    {
        $this->elevation = $elevation;
    
        return $this;
    }

    /**
     * Get elevation
     *
     * @return integer 
     */
    public function getElevation()
    {
        return $this->elevation;
    }

    /**
     * Set dem
     *
     * @param integer $dem
     * @return GeoNames
     */
    public function setDem($dem)
    {
        $this->dem = $dem;
    
        return $this;
    }

    /**
     * Get dem
     *
     * @return integer 
     */
    public function getDem()
    {
        return $this->dem;
    }

    /**
     * Set population
     *
     * @param integer $population
     * @return GeoNames
     */
    public function setPopulation($population)
    {
        $this->population = $population;
    
        return $this;
    }

    /**
     * Get population
     *
     * @return integer 
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * Set timezone
     *
     * @param string $timezone
     * @return GeoNames
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    
        return $this;
    }

    /**
     * Get timezone
     *
     * @return string 
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set modification_date
     *
     * @param \DateTime $modificationDate
     * @return GeoNames
     */
    public function setModificationDate($modificationDate)
    {
        $this->modification_date = $modificationDate;
    
        return $this;
    }

    /**
     * Get modification_date
     *
     * @return \DateTime 
     */
    public function getModificationDate()
    {
        return $this->modification_date;
    }
}