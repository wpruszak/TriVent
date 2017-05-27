<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Activity;
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
        return $this->render('default/list.html.twig', [
            'activities' => $activities
        ]);
    }
}
