<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Activity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
            $request->get('page') ? $request->get('page') : 1,
            12
        );

        return $this->render('default/list.html.twig', [
            'activities' => $activities,
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/details/{id}", name="activity_details")
     * @ParamConverter("activity", class="AppBundle:Activity")
     *
     * @param $activity
     * @return JsonResponse
     */
    public function detailsAction(Activity $activity)
    {
        $subtitle = $activity->getDateFrom()->format('d-m-Y');
        if ($activity->getDateTo() !== null) {
            $subtitle .= ' - ' . $activity->getDateTo()->format('d-m-Y');
        }

        return new JsonResponse([
            'data' => [
                'title' => $activity->getTitle(),
                'description' => $activity->getDescription(),
                'place' => $activity->getPlace(),
                'subtitle' => $subtitle,
                'link' => $activity->getLink()
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
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

        $pagination = $this->get('knp_paginator')->paginate(
            $activityQuery,
            $request->get('page') ? $request->get('page') : 1,
            12
        );

        $params = ['pagination' => $pagination];

        return new JsonResponse([
            'list' => $this->renderView('default/list_partial.html.twig', $params),
            'pagination_widget' => $this->renderView('default/pagination_widget.html.twig', $params)
        ]);
    }
}
