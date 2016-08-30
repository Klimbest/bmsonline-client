<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use VisualizationBundle\Entity\InputNumber;
use VisualizationBundle\Form\InputNumberType;

/**
 * InputNumber controller.
 *
 * @Route("/inputnumber")
 */
class InputNumberController extends Controller
{
    /**
     * Lists all InputNumber entities.
     *
     * @Route("/", name="inputnumber_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $inputNumbers = $em->getRepository('VisualizationBundle:InputNumber')->findAll();

        return $this->render('inputnumber/index.html.twig', array(
            'inputNumbers' => $inputNumbers,
        ));
    }

    /**
     * Creates a new InputNumber entity.
     *
     * @Route("/new", name="inputnumber_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $inputNumber = new InputNumber();
        $form = $this->createForm('VisualizationBundle\Form\InputNumberType', $inputNumber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inputNumber);
            $em->flush();

            return $this->redirectToRoute('inputnumber_show', array('id' => $inputNumber->getId()));
        }

        return $this->render('inputnumber/new.html.twig', array(
            'inputNumber' => $inputNumber,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a InputNumber entity.
     *
     * @Route("/{id}", name="inputnumber_show")
     * @Method("GET")
     */
    public function showAction(InputNumber $inputNumber)
    {
        $deleteForm = $this->createDeleteForm($inputNumber);

        return $this->render('inputnumber/show.html.twig', array(
            'inputNumber' => $inputNumber,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing InputNumber entity.
     *
     * @Route("/{id}/edit", name="inputnumber_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, InputNumber $inputNumber)
    {
        $deleteForm = $this->createDeleteForm($inputNumber);
        $editForm = $this->createForm('VisualizationBundle\Form\InputNumberType', $inputNumber);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inputNumber);
            $em->flush();

            return $this->redirectToRoute('inputnumber_edit', array('id' => $inputNumber->getId()));
        }

        return $this->render('inputnumber/edit.html.twig', array(
            'inputNumber' => $inputNumber,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a InputNumber entity.
     *
     * @Route("/{id}", name="inputnumber_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, InputNumber $inputNumber)
    {
        $form = $this->createDeleteForm($inputNumber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($inputNumber);
            $em->flush();
        }

        return $this->redirectToRoute('inputnumber_index');
    }

    /**
     * Creates a form to delete a InputNumber entity.
     *
     * @param InputNumber $inputNumber The InputNumber entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(InputNumber $inputNumber)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('inputnumber_delete', array('id' => $inputNumber->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
