<?php

namespace AppBundle\Factory;

use AppBundle\Entity\Activity;
use Doctrine\ORM\EntityManager;

class ActivityFactory
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * ActivityFactory constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param array $activityData
     * @return Activity
     */
    public function buildActivity(array $activityData)
    {
        $activity = new Activity();
        $activity->setTitle($activityData['title']);
        $activity->setDescription($activityData['description']);
        $activity->setLink($activityData['link']);
        $activity->setPlace($activityData['place']);
        $activity->setPubDate($activityData['pubDate']);
        if (!is_bool($activityData['dateStart'])){
            $activity->setDateFrom($activityData['dateStart']);
        }
        if (!is_bool($activityData['dateEnd'])){
            $activity->setDateTo($activityData['dateEnd']);
        }

        return $activity;
    }

    /**
     * @param array $activityData
     * @return Activity
     */
    public function createActivity(array $activityData)
    {
        $activity = $this->buildActivity($activityData);
        $this->em->persist($activity);
        $this->em->flush();

        return $activity;
    }
}