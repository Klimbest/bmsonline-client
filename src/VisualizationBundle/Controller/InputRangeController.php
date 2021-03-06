<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
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
     * Creates a new InputRange entity.
     *
     * @Route("/page/{page_id}/new", name="inputrange_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $inputRange = new InputRange();
        $page = $this->getDoctrine()->getManager()->getRepository('VisualizationBundle:Page')->find($request->get('page_id'));
        $inputRange->setPage($page);
        $form = $this->createForm(InputRangeType::class, $inputRange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inputRange);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $inputRange->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:inputrange:form.html.twig', [
            'inputRange' => $inputRange,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing InputRange entity.
     *
     * @Route("/{id}/edit", name="inputrange_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param InputRange $inputRange
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, InputRange $inputRange)
    {
        $form = $this->createForm(InputRangeType::class, $inputRange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inputRange);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $inputRange->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:inputrange:form.html.twig', [
            'inputRange' => $inputRange,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a InputRange entity.
     *
     * @Route("/{id}/delete", name="inputrange_delete")
     * @param InputRange $inputRange
     * @return RedirectResponse
     */
    public function deleteAction(InputRange $inputRange)
    {
        $page_id = $inputRange->getPage()->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($inputRange);
        $em->flush();

        return $this->redirectToRoute('page_show', ['id' => $page_id]);
    }

    /**
     * Copy a InputRange entity.
     *
     * @Route("/{id}/copy", name="inputrange_copy")
     * @param InputRange $inputRange
     * @return RedirectResponse
     */
    public function copyAction(InputRange $inputRange)
    {
        $inputRange->getPage()->getId();
        $inputRange_new = clone $inputRange;

        $em = $this->getDoctrine()->getManager();
        $em->persist($inputRange_new);
        $em->flush();

        return $this->redirectToRoute('inputrange_edit', ['id' => $inputRange_new->getId()]);
    }
}