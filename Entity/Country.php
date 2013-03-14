<?php

namespace Ephp\GeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="geo_countries")
 * @ORM\Entity(repositoryClass="Ephp\GeoBundle\Entity\CountryRepository")
 */
class Country
{

    /**
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="GeoNames")
     * @ORM\JoinColumn(name="geonameid", referencedColumnName="geonameid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $geonameid;

    /**
     * @var string
     *
     * @ORM\Column(name="iso", type="string", length=2, unique=true)
     */
    private $iso;

    /**
     * @var string
     *
     * @ORM\Column(name="iso3", type="string", length=3, unique=true)
     */
    private $iso3;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_numeric", type="string", length=3, unique=true)
     */
    private $iso_numeric;

    /**
     * @var string
     *
     * @ORM\Column(name="fips", type="string", length=2, nullable=true)
     */
    private $fips;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=64)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="capital", type="string", length=64, nullable=true)
     */
    private $capital;

    /**
     * @var integer
     *
     * @ORM\Column(name="area", type="integer", nullable=true)
     */
    private $area;

    /**
     * @var integer
     *
     * @ORM\Column(name="population", type="integer")
     */
    private $population;

    /**
     * @var string
     *
     * @ORM\Column(name="continent", type="string", length=2)
     */
    private $continent;

    /**
     * @var string
     *
     * @ORM\Column(name="tld", type="string", length=5, nullable=true)
     */
    private $tld;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_code", type="string", length=3, nullable=true)
     */
    private $currency_code;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_name", type="string", length=16, nullable=true)
     */
    private $currency_name;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=8, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code_format", type="string", length=255, nullable=true)
     */
    private $postal_code_format;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code_regex", type="string", length=255, nullable=true)
     */
    private $postal_code_regex;

    /**
     * @var string
     *
     * @ORM\Column(name="languages", type="string", length=255, nullable=true)
     */
    private $languages;

    /**
     * @var string
     *
     * @ORM\Column(name="neighbours", type="string", length=255, nullable=true)
     */
    private $neighbours;

    /**
     * @var string
     *
     * @ORM\Column(name="equivalent_fips_code", type="string", length=2, nullable=true)
     */
    private $equivalent_fips_code;

    /**
     * Set iso
     *
     * @param string $iso
     * @return Country
     */
    public function setIso($iso)
    {
        $this->iso = $iso;
    
        return $this;
    }

    /**
     * Get iso
     *
     * @return string 
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * Set iso3
     *
     * @param string $iso3
     * @return Country
     */
    public function setIso3($iso3)
    {
        $this->iso3 = $iso3;
    
        return $this;
    }

    /**
     * Get iso3
     *
     * @return string 
     */
    public function getIso3()
    {
        return $this->iso3;
    }

    /**
     * Set iso_numeric
     *
     * @param string $isoNumeric
     * @return Country
     */
    public function setIsoNumeric($isoNumeric)
    {
        $this->iso_numeric = $isoNumeric;
    
        return $this;
    }

    /**
     * Get iso_numeric
     *
     * @return string 
     */
    public function getIsoNumeric()
    {
        return $this->iso_numeric;
    }

    /**
     * Set fips
     *
     * @param string $fips
     * @return Country
     */
    public function setFips($fips)
    {
        $this->fips = $fips;
    
        return $this;
    }

    /**
     * Get fips
     *
     * @return string 
     */
    public function getFips()
    {
        return $this->fips;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set capital
     *
     * @param string $capital
     * @return Country
     */
    public function setCapital($capital)
    {
        $this->capital = $capital;
    
        return $this;
    }

    /**
     * Get capital
     *
     * @return string 
     */
    public function getCapital()
    {
        return $this->capital;
    }

    /**
     * Set area
     *
     * @param integer $area
     * @return Country
     */
    public function setArea($area)
    {
        $this->area = $area;
    
        return $this;
    }

    /**
     * Get area
     *
     * @return integer 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set population
     *
     * @param integer $population
     * @return Country
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
     * Set continent
     *
     * @param string $continent
     * @return Country
     */
    public function setContinent($continent)
    {
        $this->continent = $continent;
    
        return $this;
    }

    /**
     * Get continent
     *
     * @return string 
     */
    public function getContinent()
    {
        return $this->continent;
    }

    /**
     * Set tld
     *
     * @param string $tld
     * @return Country
     */
    public function setTld($tld)
    {
        $this->tld = $tld;
    
        return $this;
    }

    /**
     * Get tld
     *
     * @return string 
     */
    public function getTld()
    {
        return $this->tld;
    }

    /**
     * Set currency_code
     *
     * @param string $currencyCode
     * @return Country
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currency_code = $currencyCode;
    
        return $this;
    }

    /**
     * Get currency_code
     *
     * @return string 
     */
    public function getCurrencyCode()
    {
        return $this->currency_code;
    }

    /**
     * Set currency_name
     *
     * @param string $currencyName
     * @return Country
     */
    public function setCurrencyName($currencyName)
    {
        $this->currency_name = $currencyName;
    
        return $this;
    }

    /**
     * Get currency_name
     *
     * @return string 
     */
    public function getCurrencyName()
    {
        return $this->currency_name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Country
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set postal_code_format
     *
     * @param string $postalCodeFormat
     * @return Country
     */
    public function setPostalCodeFormat($postalCodeFormat)
    {
        $this->postal_code_format = $postalCodeFormat;
    
        return $this;
    }

    /**
     * Get postal_code_format
     *
     * @return string 
     */
    public function getPostalCodeFormat()
    {
        return $this->postal_code_format;
    }

    /**
     * Set postal_code_regex
     *
     * @param string $postalCodeRegex
     * @return Country
     */
    public function setPostalCodeRegex($postalCodeRegex)
    {
        $this->postal_code_regex = $postalCodeRegex;
    
        return $this;
    }

    /**
     * Get postal_code_regex
     *
     * @return string 
     */
    public function getPostalCodeRegex()
    {
        return $this->postal_code_regex;
    }

    /**
     * Set languages
     *
     * @param string $languages
     * @return Country
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
    
        return $this;
    }

    /**
     * Get languages
     *
     * @return string 
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Set geonameid
     *
     * @param integer $geonameid
     * @return Country
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
     * Set neighbours
     *
     * @param string $neighbours
     * @return Country
     */
    public function setNeighbours($neighbours)
    {
        $this->neighbours = $neighbours;
    
        return $this;
    }

    /**
     * Get neighbours
     *
     * @return string 
     */
    public function getNeighbours()
    {
        return $this->neighbours;
    }

    /**
     * Set equivalent_fips_code
     *
     * @param string $equivalentFipsCode
     * @return Country
     */
    public function setEquivalentFipsCode($equivalentFipsCode)
    {
        $this->equivalent_fips_code = $equivalentFipsCode;
    
        return $this;
    }

    /**
     * Get equivalent_fips_code
     *
     * @return string 
     */
    public function getEquivalentFipsCode()
    {
        return $this->equivalent_fips_code;
    }
}