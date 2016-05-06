<?php

namespace BmsAlarmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="bms_alarm_index")
     */
    public function indexAction()
    {
        $alarmRepo = $this->getDoctrine()->getRepository('BmsAlarmBundle:AlarmHistory');
        $alarms = $alarmRepo->getAllOrderByTime();
        
        return $this->render('BmsAlarmBundle:Default:index.html.twig', ['alarms' => $alarms]);
    }
}
