<?php

namespace WriteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use BmsConfigurationBundle\Entity\RegisterWriteData;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
            $exe = "ssh pi@" . $vpn . " ./bin/addToWrite.sh " . $register_id . " " . $value . " " . $this->getUser();
            $process = new Process($exe);
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
