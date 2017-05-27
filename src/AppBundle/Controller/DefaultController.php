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
//        $entityManager = $this->getDoctrine()->getManager();
//        $activity = new Activity();
//        $activity->setDateFrom(new \DateTime());
//        $activity->setDateTo(new \DateTime());
//        $activity->setDescription('Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi. Aliquam erat ac ipsum. Integer aliquam purus. Quisque lorem tortor fringilla sed, vestibulum id, eleifend justo vel bibendum sapien massa ac turpis faucibus orci luctus non, consectetuer lobortis quis, varius in, purus. Integer ultrices posuere cubilia Curae, Nulla ipsum dolor lacus, suscipit adipiscing. Cum sociis natoque penatibus et ultrices volutpat. Nullam wisi ultricies a, gravida vitae, dapibus risus ante sodales lectus blandit eu, tempor diam pede cursus vitae, ultricies eu, faucibus quis, porttitor eros cursus lectus, pellentesque eget, bibendum a, gravida ullamcorper quam. Nullam viverra consectetuer. Quisque cursus et, porttitor risus. Aliquam sem. In hendrerit nulla quam nunc, accumsan congue. Lorem ipsum primis in nibh vel risus. Sed vel lectus. Ut sagittis, ipsum dolor quam.');
//        $activity->setLink('#');
//        $activity->setTitle('Lorem ipsum dolor tit emet.');
//        $activity->setPubDate(new \DateTime());
//        $activity->setPlace('84-240 Reda, Obwodowa 7/4');
//
//        $entityManager->persist($activity);
//        $entityManager->flush();

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
