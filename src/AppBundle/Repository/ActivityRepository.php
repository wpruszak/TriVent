<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ActivityRepository extends EntityRepository
{
    /**
     * @return Query
     */
    public function getActivitiesQuery()
    {
        $qb = $this->createQueryBuilder('a');
        $qb->where('a.dateFrom >= :dateNow')
            ->andWhere('a.dateFrom <= :inMonth')
            ->setParameter('dateNow', new \DateTime())
            ->setParameter('inMonth', (new \DateTime())->modify('+ 1 month'))
            ->orderBy('a.dateFrom', 'ASC');

        return $qb->getQuery();
    }
}
