<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
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
     * Creates a new InputButton entity.
     *
     * @Route("/new", name="inputbutton_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $inputButton = new InputButton();
        $form = $this->createForm(InputButtonType::class, $inputButton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inputButton);
            $em->flush();

            return $this->redirectToRoute('inputbutton_show', ['id' => $inputButton->getId()]);
        }

        return $this->render('VisualizationBundle:inputbutton:form.html.twig', [
            'inputButton' => $inputButton,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing InputButton entity.
     *
     * @Route("/{id}/edit", name="inputbutton_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param InputButton $inputButton
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, InputButton $inputButton)
    {
        $form = $this->createForm(InputButtonType::class, $inputButton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inputButton);
            $em->flush();

            return $this->redirectToRoute('inputbutton_edit', ['id' => $inputButton->getId()]);
        }

        return $this->render('VisualizationBundle:inputbutton:form.html.twig', [
            'inputButton' => $inputButton,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a InputButton entity.
     *
     * @Route("/{id}", name="inputbutton_delete")
     * @param InputButton $inputButton
     * @return RedirectResponse
     */
    public function deleteAction(InputButton $inputButton)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($inputButton);
        $em->flush();

        return $this->redirectToRoute('inputbutton_index');
    }

}