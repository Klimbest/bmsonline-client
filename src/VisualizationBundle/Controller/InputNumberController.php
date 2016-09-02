<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
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
     * Creates a new InputNumber entity.
     *
     * @Route("/page/{page_id}/new", name="inputnumber_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $inputNumber = new InputNumber();
        $page = $this->getDoctrine()->getManager()->getRepository('VisualizationBundle:Page')->find($request->get('page_id'));
        $inputNumber->setPage($page);
        $form = $this->createForm(InputNumberType::class, $inputNumber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inputNumber);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $inputNumber->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:inputnumber:form.html.twig', [
            'inputNumber' => $inputNumber,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing InputNumber entity.
     *
     * @Route("/{id}/edit", name="inputnumber_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param InputNumber $inputNumber
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, InputNumber $inputNumber)
    {
        $form = $this->createForm(InputNumberType::class, $inputNumber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inputNumber);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $inputNumber->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:inputnumber:form.html.twig', [
            'inputNumber' => $inputNumber,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a InputNumber entity.
     *
     * @Route("/{id}/delete", name="inputnumber_delete")
     * @param InputNumber $inputNumber
     * @return RedirectResponse
     */
    public function deleteAction(InputNumber $inputNumber)
    {
        $page_id = $inputNumber->getPage()->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($inputNumber);
        $em->flush();

        return $this->redirectToRoute('page_show', ['id' => $page_id]);
    }

}