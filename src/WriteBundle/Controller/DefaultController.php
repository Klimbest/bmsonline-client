<?php

namespace WriteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use BmsConfigurationBundle\Entity\RegisterWriteData;
use Symfony\Component\Process\Process;

class DefaultController extends Controller {

    /**
     * @Route("/", name="write_register", options={"expose"=true})
     */
    public function writeAction(Request $request) {
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

            $host = $request->getHost();
            $h = explode(".", $host);
            $exe = "bash ../../_bin/orderToRPi.sh 'bin/addToWrite' " . $h[0] . $register . " " . $value . " " . $this->getUser();
            var_dump($exe);
            $process = new Process($exe);
            //$process->disableOutput();
            $process->run();

            return new JsonResponse();
        } else {
            throw new AccessDeniedHttpException();
        }
    }

}
