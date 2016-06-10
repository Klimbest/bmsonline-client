<?php

namespace WriteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="write_register", options={"expose"=true})
     */
    public function writeAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {            
            
            return new JsonResponse();
        } else {
            throw new AccessDeniedHttpException();
        }
    }
}
