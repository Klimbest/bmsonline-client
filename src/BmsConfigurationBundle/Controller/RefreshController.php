<?php

//src/BmsBundle/Controller/RefreshController.php

namespace BmsConfigurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class RefreshController extends Controller {

    public function refreshPageAction($comm_id, $device_id, $register_id, Request $request) {
        if ($request->isXmlHttpRequest()) {

            $em = $this->getDoctrine()->getManager();
            $registerDevice = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Device');
            $devices = $registerDevice->findAll();
            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
            $registers = $registerRepo->findAll();
            $ret = array();
            $times = array();
            foreach ($devices as $device) {
                $did = $device->getId();
                $active = $device->getActive();
                $registers = $device->getRegisters();
                $time = 0;
                foreach ($registers as $register) {
                    $lastRead = date_timestamp_get($register->getRegisterCurrentData()->getTimeOfUpdate());

                    if ($lastRead > $time) {
                        $time = $lastRead;
                    }
                    $id = $register->getId();
                    $val = $register->getRegisterCurrentData()->getFixedValue();
                    $ret[$id] = $val;
                }
                if ($active) {
                    $times[$did] = $time;
                }else{
                    $times[$did] = 0;
                }
               
            }

            $ret["times_of_update"] = $times;

            $ret["time_of_update"] = $time;

            return new JsonResponse($ret);
        } else {
            throw new\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
    }

}
