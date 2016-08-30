<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use VisualizationBundle\Entity\InputButton;
use VisualizationBundle\Form\InputButtonType;

/**
 * InputButton controller.
 *
 * @Route("/inputbutton")
 */
class InputButtonController extends Controller
{
    /**
     * Lists all InputButton entities.
     *
     * @Route("/", name="inputbutton_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $inputButtons = $em->getRepository('VisualizationBundle:InputButton')->findAll();

        return $this->render('inputbutton/index.html.twig', array(
            'inputButtons' => $inputButtons,
        ));
    }

    /**
     * Creates a new InputButton entity.
     *
     * @Route("/new", name="inputbutton_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $inputButton = new InputButton();
        $form = $this->createForm('VisualizationBundle\Form\InputButtonType', $inputButton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inputButton);
            $em->flush();

            return $this->redirectToRoute('inputbutton_show', array('id' => $inputButton->getId()));
        }

        return $this->render('inputbutton/new.html.twig', array(
            'inputButton' => $inputButton,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a InputButton entity.
     *
     * @Route("/{id}", name="inputbutton_show")
     * @Method("GET")
     */
    public function showAction(InputButton $inputButton)
    {
        $deleteForm = $this->createDeleteForm($inputButton);

        return $this->render('inputbutton/show.html.twig', array(
            'inputButton' => $inputButton,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing InputButton entity.
     *
     * @Route("/{id}/edit", name="inputbutton_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, InputButton $inputButton)
    {
        $deleteForm = $this->createDeleteForm($inputButton);
        $editForm = $this->createForm('VisualizationBundle\Form\InputButtonType', $inputButton);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inputButton);
            $em->flush();

            return $this->redirectToRoute('inputbutton_edit', array('id' => $inputButton->getId()));
        }

        return $this->render('inputbutton/edit.html.twig', array(
            'inputButton' => $inputButton,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a InputButton entity.
     *
     * @Route("/{id}", name="inputbutton_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, InputButton $inputButton)
    {
        $form = $this->createDeleteForm($inputButton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($inputButton);
            $em->flush();
        }

        return $this->redirectToRoute('inputbutton_index');
    }

    /**
     * Creates a form to delete a InputButton entity.
     *
     * @param InputButton $inputButton The InputButton entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(InputButton $inputButton)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('inputbutton_delete', array('id' => $inputButton->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
