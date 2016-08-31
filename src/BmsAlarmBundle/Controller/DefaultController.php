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
     * @Route("/clear", name="bms_alarm_clear_history")
     */
    public function clearHistoryAction()
    {
        $em = $this->getDoctrine()->getManager();
        $alarmRepo = $this->getDoctrine()->getRepository('BmsAlarmBundle:AlarmHistory');
        $alarms = $alarmRepo->findAll();

        foreach ($alarms as $alarm) {
            $em->remove($alarm);
        }
        $em->flush();

        return $this->redirectToRoute('bms_alarm_index');
    }

}
