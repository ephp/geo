<?php

namespace Ephp\GeoBundle\Model\Traits;

use Doctrine\ORM\Mapping as ORM;

trait BaseGeo {

    /**
     * @var Comune $comune
     *
     * @ORM\ManyToOne(targetEntity="Ephp\GeoBundle\Entity\GeoNames")
     * @ORM\JoinColumn(name="comune_id", referencedColumnName="geonameid")
     */
    protected $comune;

    /**
     * @var string $cap
     *
     * @ORM\Column(name="cap", type="string", length=5, nullable=true)
     */
    protected $cap;

    /**
     * @var string $indirizzo
     *
     * @ORM\Column(name="indirizzo", type="string", length=255)
     */
    protected $indirizzo;

    /**
     * @var decimal $latitudine
     *
     * @ORM\Column(name="latitudine", type="decimal", precision=15, scale=10)
     */
    protected $latitudine;

    /**
     * @var decimal $longitudine
     *
     * @ORM\Column(name="longitudine", type="decimal", precision=15, scale=10)
     */
    protected $longitudine;

    /**
     * @var decimal latitudinerad
     *
     * @ORM\Column(name="latitudinerad", type="decimal", precision=15, scale=13)
     */
    protected $latitudinerad;

    /**
     * @var decimal $longitudinerad
     *
     * @ORM\Column(name="longitudinerad", type="decimal", precision=15, scale=13)
     */
    protected $longitudinerad;

    /**
     * Set comune_id
     *
     * @param \Ephp\GeoBundle\Entity\GeoNames $comune
     */
    public function setComune($comune) {
        $this->comune = $comune;
    }

    /**
     * Get comune_id
     *
     * @return \Ephp\GeoBundle\Entity\GeoNames 
     */
    public function getComune() {
        return $this->comune;
    }

    /**
     * Set cap
     *
     * @param string $cap
     */
    public function setCap($cap) {
        $this->cap = $cap;
    }

    /**
     * Get cap
     *
     * @return string 
     */
    public function getCap() {
        return $this->cap;
    }

    /**
     * Set indirizzo
     *
     * @param string $indirizzo
     */
    public function setIndirizzo($indirizzo) {
        $this->indirizzo = $indirizzo;
    }

    /**
     * Get indirizzo
     *
     * @return string 
     */
    public function getIndirizzo() {
        return $this->indirizzo;
    }

    /**
     * Set latitudine
     *
     * @param decimal $latitudine
     */
    public function setLatitudine($latitudine) {
        $this->latitudine = $latitudine;
    }

    /**
     * Get latitudine
     *
     * @return decimal 
     */
    public function getLatitudine() {
        return $this->latitudine;
    }

    /**
     * Set longitudine
     *
     * @param decimal $longitudine
     */
    public function setLongitudine($longitudine) {
        $this->longitudine = $longitudine;
    }

    /**
     * Get longitudine
     *
     * @return decimal 
     */
    public function getLongitudine() {
        return $this->longitudine;
    }

    /**
     * Set latitudine_rad
     *
     * @param decimal $latitudine_rad
     */
    public function setLatitudinerad($latitudine_rad) {
        $this->latitudinerad = $latitudine_rad;
    }

    /**
     * Get latitudine_rad
     *
     * @return decimal 
     */
    public function getLatitudinerad() {
        return $this->latitudinerad;
    }

    /**
     * Set longitudine_rad
     *
     * @param decimal $longitudine_rad
     */
    public function setLongitudinerad($longitudine_rad) {
        $this->longitudinerad = $longitudine_rad;
    }

    /**
     * Get longitudine_rad
     *
     * @return decimal 
     */
    public function getLongitudinerad() {
        return $this->longitudinerad;
    }

    public function getIndirizzoCompleto() {
        return $this->getComune() ?
                "{$this->getIndirizzo()} - {$this->getCap()} - {$this->getComune()->getNomeComune()}" :
                "{$this->getIndirizzo()} - {$this->getCap()}";
    }

    public function getNomeComune() {
        return $this->getComune() ? "{$this->getComune()->getNomeComune()}" : '';
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->latitudinerad = deg2rad($this->latitudine);
        $this->longitudinerad = deg2rad($this->longitudine);
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        $this->latitudinerad = deg2rad($this->latitudine);
        $this->longitudinerad = deg2rad($this->longitudine);
    }

}