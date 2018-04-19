<?php

namespace AppBundle\Repository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends \Doctrine\ORM\EntityRepository {

    public function findEventByTitle($motcle) {
        $query = $this->createQueryBuilder('u')
                ->select('u')
                ->where('u.titre like :titre')
                ->orderBy('u.titre', 'ASC')
                ->setParameter('titre', '%' . $motcle . '%')
                ->getQuery();

        return $query->getResult();
    }

    public function sortByMinPrice() {
        $query = $this->createQueryBuilder('u')
                ->select('u')
                ->orderBy('u.prix', 'ASC')
                ->getQuery();

        return $query->getResult();
    }

    public function sortByMaxPrice() {
        $query = $this->createQueryBuilder('u')
                ->select('u')
                ->orderBy('u.prix', 'DESC')
                ->getQuery();

        return $query->getResult();
    }

    public function sortByParticipants() {
        $query = $this->createQueryBuilder('u')
                ->select('u')
                ->orderBy('u.nbrParticipants', 'DESC')
                ->getQuery();

        return $query->getResult();
    }

    public function sortByNearest() {
//        SELECT id, titre, ( 3959 * acos( cos( radians(48.76254099999999) ) * cos( radians( lat ) ) * cos( radians( lng ) -
//        radians(2.408875899999998) ) + sin( radians(48.76254099999999) ) * sin( radians( lat ) ) ) ) AS distance FROM atl_event
//        HAVING distance < 25 ORDER BY distance LIMIT 0, 20

        $lng = 2.408875899999998;
        $lat = 48.76254099999999;

        $query = $this
                ->createQueryBuilder('u')
                ->select('u.id, u.titre, u.description, u.dateDebut, u.prix, u.nbrMax, u.nbrParticipants, u.image')
                ->addSelect(
                        '( 3959 * acos(cos(radians(' . $lat . '))' .
                        '* cos( radians( u.lat ) )' .
                        '* cos( radians( u.lng )' .
                        '- radians(' . $lng . ') )' .
                        '+ sin( radians(' . $lat . ') )' .
                        '* sin( radians( u.lat ) ) ) ) as distance'
                )
                ->orderBy('distance', 'ASC')
                //->having('distance <= 30 ')
                //    ->setParameter('radius', 30)
                //  ->orderBy('distance', 'ASC')
                //->setMaxResults(100)
                ->getQuery();
        return $query->getResult();
    }

}
