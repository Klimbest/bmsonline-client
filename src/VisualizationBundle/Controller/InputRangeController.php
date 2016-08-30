<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use VisualizationBundle\Entity\InputRange;
use VisualizationBundle\Form\InputRangeType;

/**
 * InputRange controller.
 *
 * @Route("/inputrange")
 */
class InputRangeController extends Controller
{
    /**
     * Lists all InputRange entities.
     *
     * @Route("/", name="inputrange_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $inputRanges = $em->getRepository('VisualizationBundle:InputRange')->findAll();

        return $this->render('inputrange/index.html.twig', array(
            'inputRanges' => $inputRanges,
        ));
    }

    /**
     * Creates a new InputRange entity.
     *
     * @Route("/new", name="inputrange_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $inputRange = new InputRange();
        $form = $this->createForm('VisualizationBundle\Form\InputRangeType', $inputRange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inputRange);
            $em->flush();

            return $this->redirectToRoute('inputrange_show', array('id' => $inputRange->getId()));
        }

        return $this->render('inputrange/new.html.twig', array(
            'inputRange' => $inputRange,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a InputRange entity.
     *
     * @Route("/{id}", name="inputrange_show")
     * @Method("GET")
     */
    public function showAction(InputRange $inputRange)
    {
        $deleteForm = $this->createDeleteForm($inputRange);

        return $this->render('inputrange/show.html.twig', array(
            'inputRange' => $inputRange,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing InputRange entity.
     *
     * @Route("/{id}/edit", name="inputrange_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, InputRange $inputRange)
    {
        $deleteForm = $this->createDeleteForm($inputRange);
        $editForm = $this->createForm('VisualizationBundle\Form\InputRangeType', $inputRange);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inputRange);
            $em->flush();

            return $this->redirectToRoute('inputrange_edit', array('id' => $inputRange->getId()));
        }

        return $this->render('inputrange/edit.html.twig', array(
            'inputRange' => $inputRange,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a InputRange entity.
     *
     * @Route("/{id}", name="inputrange_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, InputRange $inputRange)
    {
        $form = $this->createDeleteForm($inputRange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($inputRange);
            $em->flush();
        }

        return $this->redirectToRoute('inputrange_index');
    }

    /**
     * Creates a form to delete a InputRange entity.
     *
     * @param InputRange $inputRange The InputRange entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(InputRange $inputRange)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('inputrange_delete', array('id' => $inputRange->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
