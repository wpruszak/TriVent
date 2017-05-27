<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Activity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="default_index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/list", name="default_list")
     */
    public function listAction(Request $request)
    {
        $activities = $this->getDoctrine()->getRepository(Activity::class)->findAll();

        $activityQuery = $this->getDoctrine()
            ->getRepository(Activity::class)
            ->createQueryBuilder('a')
            ->getQuery();

        $pagination = $this->get('knp_paginator')->paginate(
            $activityQuery,
            $request->get('page') ?? 1,
            12
        );

        return $this->render('default/list.html.twig', [
            'activities' => $activities,
            'pagination' => $pagination
        ]);
    }

    /**
     * @param Request $request
     *
     * @Route("/paginate", name="default_paginate")
     * @Method("POST")
     */
    public function paginationAction(Request $request)
    {
        $activityQuery = $this->getDoctrine()
            ->getRepository(Activity::class)
            ->createQueryBuilder('a')
            ->getQuery();

        $paginator = $this->get('knp_paginator')->paginate(
            $activityQuery,
            $request->get('page') ?? 1,
            12
        );
    }
}
