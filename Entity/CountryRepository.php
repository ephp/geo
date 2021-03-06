<?php

namespace JF\GeoBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CountryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CountryRepository extends EntityRepository {
    
    /**
     * Restituisce un oggetto queryBuilder per fare la Select nel Form
     * 
     * @return QueryBuilder
     */
    public function getCountry() {
        $q = $this->createQueryBuilder('g');
        $q->where('g.population > 500'); 
        $q->orderBy('g.country', 'ASC');
        return $q;
    }
    
    /**
     * Restituisce il nome della nazione
     * 
     * @param int $geoId 
     * @return string 
     */
    public function getNomeNazione($geoId) {
        $q = $this->createQueryBuilder('g');
        $q->select('g.country');
        $q->where('g.geonameid =:geoId');
        $q->setParameter('geoId', $geoId);
        $dql = $q->getQuery();
        $results = $dql->getOneOrNullResult();

        return $results['country']; 
    }

}
