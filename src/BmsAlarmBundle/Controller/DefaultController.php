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

    /**
     * @Route("/clear", name="clear_alarm_history")
     */
    public function clearAction()
    {
        $alarmRepo = $this->getDoctrine()->getRepository('BmsAlarmBundle:AlarmHistory');
        $alarms = $alarmRepo->findAll();
        $em = $this->getDoctrine()->getManager();
        foreach ($alarms as $alarm) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($alarm);
        }
        $em->flush();

        return $this->redirectToRoute('bms_alarm_index');
    }

}
