<?php

namespace BmsAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    /**
     * @Route("/", name="bms_admin")
     */
    public function indexAction() {

        $userManager = $this->get('fos_user.user_manager');
        
        $users = $userManager->findUsers();

        return $this->render('BmsAdminBundle::index.html.twig', array('users' => $users));
    }

    public function manageUserAction(Request $request){
        
        $form = $this->createForm(PostType::class, $post);
        
    }
    
}
