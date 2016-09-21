<?php

namespace WriteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use BmsConfigurationBundle\Entity\RegisterWriteData;
use Symfony\Component\Process\Process;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="write_register", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function writeAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $value = $request->get("value");
            $register_id = $request->get("register_id");
            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
            $register = $registerRepo->find($register_id);
            $em = $this->getDoctrine()->getManager();
            $write = new RegisterWriteData();
            $write->setRegister($register)
                ->setGetToProcess(0)
                ->setValue($value)
                ->setSuccessWrite(0)
                ->setTimeOfUpdate(new \DateTime())
                ->setUsername($this->getUser());

            $em->persist($write);
            $em->flush();
            $vpn = $this->getParameter('vpn');
            $query = "INSERT INTO register_write_data('register_id', 'value', 'get_to_process', username) VALUES(" . $register_id . ", " . $value . ", 1, " . $this->getUser() . ")";
//            $h[0] . " " . $register_id . " " . $value . " " . $this->getUser()
            $exe = "ssh pi@" . $vpn . " mysql -u root -p modbus KbScanner -e " . $query;
            $process = new Process($exe);
            //$process->disableOutput();
            $process->start();
            while ($process->isRunning()) {
                // waiting for process to finish
            }

            $ret['output'] = $process->getOutput();
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

}
