<?php

namespace Ephp\GeoBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * GeoNamesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GeoNamesRepository extends EntityRepository {

    /**
     * Restituisce tutte le citta controllando la popolazione minima
     * 
     * @param int $geoId Id della nazione
     * 
     * @author Barno <barno77@gmail.com> :)
     */
    public function getCitta($geoId) {

        $iso = $this->getManager()->getRepository('Ephp\GeoBundle\Entity\Country')->findOneBy(array('geonameid' => $geoId));

        $q = $this->createQueryBuilder('g');
        $q->select('g.geonameid', 'g.asciiname', 'g.admin1_code', 'g.admin2_code');
        $q->where('g.country_code =:iso');

        $q->andWhere("g.population>=:pop");

        $q->andWhere($q->expr()->in('g.feature_code', array('PPL'
                    , 'PPLA'
                    , 'PPLA2'
                    , 'PPLA3'
                    , 'PPLA4'
                    , 'PPLC'
                    , 'PPLF'
                    , 'PPLG'
                    , 'PPLL'
                    , 'PPLS'
                    , 'PPLX')));

        $q->orderBy('g.name', 'ASC');
        $q->setParameter('iso', $iso->getIso());
        $pop = $iso->getArea() > 2000 && $iso->getPopulation() > 35000 ? max($iso->getPopulation() / 100000, $iso->getPopulation() * 15 / $iso->getArea()) : 0;
        $q->setParameter('pop', $pop);
        $dql = $q->getQuery();
        $results = $dql->execute();

        return $results;
    }

    /**
     * 
     * @param int $geoId
     * @return type
     */
    public function getNomeCitta($geoId) {
        $q = $this->createQueryBuilder('g');
        $q->select('g.asciiname', 'g.admin1_code', 'g.admin2_code');
        $q->where('g.geonameid =:geoId');
        $q->setParameter('geoId', $geoId);
        $dql = $q->getQuery();
        $results = $dql->getOneOrNullResult();
        $area = "";
        if ($results['admin1_code'] && !intval($results['admin1_code'])) {
            $area = $results['admin1_code'];
        } else if ($results['admin2_code'] && !intval($results['admin2_code'])) {
            $area = $results['admin2_code'];
        }

        return $results["asciiname"] . " (" . $area . ")";
    }

    /**
     * Restituisce il comune
     *
     * @param float $latitude
     * @param float $longitude
     * @return Ephp\GeoBundle\Entity\GeoNames
     * @throws NoResultException 
     */
    public function getComune($latitude, $longitude) {
        $config = $this->getManager()->getConfiguration();
        $config->addCustomNumericFunction('DEGREES', 'DoctrineExtensions\Query\Mysql\Degrees');
        $config->addCustomNumericFunction('ACOS', 'DoctrineExtensions\Query\Mysql\Acos');
        $config->addCustomNumericFunction('SIN', 'DoctrineExtensions\Query\Mysql\Sin');
        $config->addCustomNumericFunction('RADIANS', 'DoctrineExtensions\Query\Mysql\Radians');
        $config->addCustomNumericFunction('COS', 'DoctrineExtensions\Query\Mysql\Cos');

        try {
            $q = $this->getManager()->createQuery("
SELECT geo.admin3_code, (
    DEGREES(
        ACOS(
            (
                SIN(
                    RADIANS(:latitude)
                ) * SIN(
                    RADIANS(geo.latitude)
                )
            ) + (
                COS(
                    RADIANS(:latitude)
                ) * COS(
                    RADIANS(geo.latitude)
                ) * COS(
                    RADIANS(:longitude - geo.longitude)
                )
            )
        )        
    ) * 111.18957696
) as distanza 
  FROM Ephp\GeoBundle\Entity\GeoNames geo 
 WHERE geo.admin3_code != ''
   AND geo.longitude BETWEEN (:longitude - 0.1) AND (:longitude + 0.1)  
   AND geo.latitude BETWEEN (:latitude - 0.1) AND (:latitude + 0.1)  
 ORDER BY distanza
               ");
            $q->setParameter('latitude', $latitude);
            $q->setParameter('longitude', $longitude);
            $service = $q->getArrayResult();

            $admin3_code = $service[0]['admin3_code'];

            $comune = $this->findOneBy(array('admin3_code' => $admin3_code, 'feature_code' => 'ADM3'));

            return $comune;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Restituisce il comune
     *
     * @param float $latitude
     * @param float $longitude
     * @return Ephp\GeoBundle\Entity\GeoNames
     * @throws NoResultException 
     */
    public function getComuneProvincia($latitude, $longitude) {
        $config = $this->getManager()->getConfiguration();
        $config->addCustomNumericFunction('DEGREES', 'DoctrineExtensions\Query\Mysql\Degrees');
        $config->addCustomNumericFunction('ACOS', 'DoctrineExtensions\Query\Mysql\Acos');
        $config->addCustomNumericFunction('SIN', 'DoctrineExtensions\Query\Mysql\Sin');
        $config->addCustomNumericFunction('RADIANS', 'DoctrineExtensions\Query\Mysql\Radians');
        $config->addCustomNumericFunction('COS', 'DoctrineExtensions\Query\Mysql\Cos');

        try {
            $q = $this->getManager()->createQuery("
SELECT geo.admin3_code, geo.admin2_code, (
    DEGREES(
        ACOS(
            (
                SIN(
                    RADIANS(:latitude)
                ) * SIN(
                    RADIANS(geo.latitude)
                )
            ) + (
                COS(
                    RADIANS(:latitude)
                ) * COS(
                    RADIANS(geo.latitude)
                ) * COS(
                    RADIANS(:longitude - geo.longitude)
                )
            )
        )        
    ) * 111.18957696
) as distanza 
  FROM Ephp\GeoBundle\Entity\GeoNames geo 
 WHERE geo.admin3_code != ''
   AND geo.longitude BETWEEN (:longitude - 0.1) AND (:longitude + 0.1)  
   AND geo.latitude BETWEEN (:latitude - 0.1) AND (:latitude + 0.1)  
 ORDER BY distanza
               ");
            $q->setParameter('latitude', $latitude);
            $q->setParameter('longitude', $longitude);
            $service = $q->getArrayResult();

            $admin3_code = $service[0]['admin3_code'];
            $admin2_code = $service[0]['admin2_code'];

            $comune = $this->findOneBy(array('admin3_code' => $admin3_code, 'feature_code' => 'ADM3'));
            $provincia = $this->findOneBy(array('admin2_code' => $admin2_code, 'feature_code' => 'ADM2'));

            return array('comune' => $comune, 'provincia' => $provincia);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Restituisce la provincia
     *
     * @param float $latitude
     * @param float $longitude
     * @return Ephp\GeoBundle\Entity\GeoNames
     * @throws NoResultException 
     */
    public function getProvincia($latitude, $longitude) {
        $config = $this->getManager()->getConfiguration();
        $config->addCustomNumericFunction('DEGREES', 'DoctrineExtensions\Query\Mysql\Degrees');
        $config->addCustomNumericFunction('ACOS', 'DoctrineExtensions\Query\Mysql\Acos');
        $config->addCustomNumericFunction('SIN', 'DoctrineExtensions\Query\Mysql\Sin');
        $config->addCustomNumericFunction('RADIANS', 'DoctrineExtensions\Query\Mysql\Radians');
        $config->addCustomNumericFunction('COS', 'DoctrineExtensions\Query\Mysql\Cos');

        try {
            $q = $this->getManager()->createQuery("
SELECT geo.admin2_code, (
    DEGREES(
        ACOS(
            (
                SIN(
                    RADIANS(:latitude)
                ) * SIN(
                    RADIANS(geo.latitude)
                )
            ) + (
                COS(
                    RADIANS(:latitude)
                ) * COS(
                    RADIANS(geo.latitude)
                ) * COS(
                    RADIANS(:longitude - geo.longitude)
                )
            )
        )        
    ) * 111.18957696
) as distanza 
  FROM Ephp\GeoBundle\Entity\GeoNames geo 
 WHERE geo.admin2_code != ''
   AND geo.longitude BETWEEN (:longitude - 0.1) AND (:longitude + 0.1)  
   AND geo.latitude BETWEEN (:latitude - 0.1) AND (:latitude + 0.1)  
 ORDER BY distanza
               ");
            $q->setParameter('latitude', $latitude);
            $q->setParameter('longitude', $longitude);
            $service = $q->getArrayResult();

            $admin2_code = $service[0]['admin2_code'];

            $provincia = $this->findOneBy(array('admin2_code' => $admin2_code, 'feature_code' => 'ADM2'));

            return $provincia;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Restituisce il comune
     *
     * @param float $latitude
     * @param float $longitude
     * @return Ephp\GeoBundle\Entity\GeoNames
     * @throws NoResultException 
     */
    public function ricercaRegione($regione, $nazione = null) {

        try {
            $q = $this->createQueryBuilder('c')
                    ->where('c.feature_code = :fc')
                    ->setParameter('fc', 'ADM1');
            if ($nazione) {
                $q->andWhere('c.country_code = :cc')
                        ->setParameter('cc', $nazione);
            }
            $q->andWhere('c.name LIKE :regione OR c.asciiname LIKE :regione')
                    ->setParameter('regione', $regione . '%');
            $q->orderBy('c.population', 'ASC');
            $comuni = $q->getQuery()->execute();
            return array_shift($comuni);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    /**
     * Restituisce il comune
     *
     * @param float $latitude
     * @param float $longitude
     * @return Ephp\GeoBundle\Entity\GeoNames
     * @throws NoResultException 
     */
    public function ricercaProvincia($provincia, $regione = null, $nazione = null) {

        try {
            $q = $this->createQueryBuilder('c')
                    ->where('c.feature_code = :fc')
                    ->setParameter('fc', 'ADM2');
            if ($nazione) {
                $q->andWhere('c.country_code = :cc')
                        ->setParameter('cc', $nazione);
            }
            if ($regione) {
                $qr = $this->createQueryBuilder('c')
                        ->where('c.feature_code = :fc')
                        ->setParameter('fc', 'ADM1');
                if ($nazione) {
                    $qr->andWhere('c.country_code = :cc')
                            ->setParameter('cc', $nazione);
                }
                $qr->andWhere('c.name LIKE :regione')
                        ->setParameter('regione', '%' . $regione . '%');
                $reg = $qr->getQuery()->getOneOrNullResult();
                /* @var $reg GeoNames */
                if($reg) {
                    $q->andWhere('c.admin1_code = :reg')
                            ->setParameter('reg', $reg->getAdmin1Code());
                }
            }
            $q->andWhere('c.name LIKE :comune')
                    ->setParameter('comune', $provincia . '%');
            $q->orderBy('c.population', 'ASC');
//            \Ephp\UtilityBundle\Utility\Debug::pr($q->getQuery()->getSQL(), true);
//            \Ephp\UtilityBundle\Utility\Debug::pr($q->getQuery()->getParameters());
            $comuni = $q->getQuery()->execute();
            return array_shift($comuni);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    /**
     * Restituisce il comune
     *
     * @param float $latitude
     * @param float $longitude
     * @return Ephp\GeoBundle\Entity\GeoNames
     * @throws NoResultException 
     */
    public function ricercaComune($comune, $provincia = null, $regione = null, $nazione = null) {

        try {
            $q = $this->createQueryBuilder('c')
                    ->where('c.feature_code = :fc')
                    ->setParameter('fc', 'ADM3');
            if ($nazione) {
                $q->andWhere('c.country_code = :cc')
                        ->setParameter('cc', $nazione);
            }
            if ($regione) {
                $qr = $this->createQueryBuilder('c')
                        ->where('c.feature_code = :fc')
                        ->setParameter('fc', 'ADM1');
                if ($nazione) {
                    $qr->andWhere('c.country_code = :cc')
                            ->setParameter('cc', $nazione);
                }
                $qr->andWhere('c.name LIKE :regione')
                        ->setParameter('regione', '%' . $regione . '%');
                $reg = $qr->getQuery()->getOneOrNullResult();
                /* @var $reg GeoNames */
                if($reg) {
                    $q->andWhere('c.admin1_code = :reg')
                            ->setParameter('reg', $reg->getAdmin1Code());
                }
            }
            if ($provincia) {
                $q->andWhere('c.admin2_code = :prov')
                        ->setParameter('prov', $provincia);
            }
            $q->andWhere('c.name LIKE :comune')
                    ->setParameter('comune', $comune . '%');
            $q->orderBy('c.population', 'ASC');
            $comuni = $q->getQuery()->execute();
            return array_shift($comuni);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Restituisce il comune
     *
     * @param float $latitude
     * @param float $longitude
     * @return Ephp\GeoBundle\Entity\GeoNames
     * @throws NoResultException 
     */
    public function cercaComune($nome, $nazione = 'IT') {

        try {
            $q = $this->getManager()->createQuery("
SELECT geo
  FROM Ephp\GeoBundle\Entity\GeoNames geo 
 WHERE geo.feature_code = 'ADM3'
   AND geo.country_code = :nazione
   AND geo.name LIKE :nome
 ORDER BY geo.population DESC
               ");
            $q->setParameter('nome', '%' . $nome . '%');
            $q->setParameter('nazione', $nazione );
            $comuni = $q->execute();
            return $comuni;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /*
     * Restituisce il comune
     *
     * @param float $latitude
     * @param float $longitude
     * @return Ephp\GeoBundle\Entity\GeoNames
     * @throws NoResultException 
     */

    public function cercaTutto($nome) {

        try {
            $q = $this->getManager()->createQuery("
SELECT geo
  FROM Ephp\GeoBundle\Entity\GeoNames geo 
 WHERE geo.admin2_code != ''
   AND geo.feature_code IN ('ADM3', 'ADM4', 'ADMD', 'LTER', 'PCL', 'PCLD', 'PCLF', 
                            'PCLI', 'PCILX', 'PCLS', 'PRSH', 'TERR', 'ZN', 'ZNB', 
                            'PPL', 'PPLA', 'PPLA2', 'PPLA3', 'PPLA4', 'PPLC', 'PPLF',
                            'PPLG', 'PPLL', 'PPLQ', 'PPLR', 'PPLS', 'PPLW', 'PPLX')
   AND geo.name LIKE :nome
 ORDER BY geo.population DESC
               ");
            $q->setParameter('nome', $nome . '%');
            $comuni = $q->execute();
            return $comuni;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /*
     * Restituisce il comune
     *
     * @param float $latitude
     * @param float $longitude
     * @return Ephp\GeoBundle\Entity\GeoNames
     * @throws NoResultException 
     */

    public function cercaProvincia($nome) {

        try {
            $q = $this->getManager()->createQuery("
SELECT geo
  FROM Ephp\GeoBundle\Entity\GeoNames geo 
 WHERE geo.feature_code = 'ADM2'
   AND geo.name LIKE :nome
 ORDER BY geo.population DESC
               ");
            $q->setParameter('nome', '%' . $nome . '%');
            $province = $q->execute();
            return $province;
        } catch (\Exception $e) {
            throw $e;
        }
    }

}

// update geo_names set population = (SELECT population FROM geo_names_fixture WHERE geo_names.geonameid = geo_names_fixture.geonameid)