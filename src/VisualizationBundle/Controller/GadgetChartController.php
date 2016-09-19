<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use VisualizationBundle\Entity\GadgetChart;
use VisualizationBundle\Form\GadgetChartType;

/**
 * GadgetChart controller.
 *
 * @Route("/gadgetchart")
 */
class GadgetChartController extends Controller
{

    /**
     * Creates a new GadgetChart entity.
     *
     * @Route("/page/{page_id}/new", name="gadgetchart_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $gadgetChart = new GadgetChart();
        $page = $this->getDoctrine()->getManager()->getRepository('VisualizationBundle:Page')->find($request->get('page_id'));
        $gadgetChart->setPage($page);
        $form = $this->createForm(GadgetChartType::class, $gadgetChart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gadgetChart);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $gadgetChart->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:gadgetchart:form.html.twig', [
            'gadgetChart' => $gadgetChart,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing GadgetChart entity.
     *
     * @Route("/{id}/edit", name="gadgetchart_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param GadgetChart $gadgetChart
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, GadgetChart $gadgetChart)
    {
        $form = $this->createForm(GadgetChartType::class, $gadgetChart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gadgetChart);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $gadgetChart->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:gadgetchart:form.html.twig', [
            'gadgetChart' => $gadgetChart,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a GadgetChart entity.
     *
     * @Route("/{id}/delete", name="gadgetchart_delete")
     * @param GadgetChart $gadgetChart
     * @return RedirectResponse
     */
    public function deleteAction(GadgetChart $gadgetChart)
    {
        $page_id = $gadgetChart->getPage()->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($gadgetChart);
        $em->flush();

        return $this->redirectToRoute('page_show', ['id' => $page_id]);
    }

    /**
     * Copy a GadgetChart entity.
     *
     * @Route("/{id}/copy", name="gadgetchart_copy")
     * @param GadgetChart $gadgetChart
     * @return RedirectResponse
     */
    public function copyAction(GadgetChart $gadgetChart)
    {
        $gadgetChart->getPage()->getId();
        $gadgetChart_new = clone $gadgetChart;

        $em = $this->getDoctrine()->getManager();
        $em->persist($gadgetChart_new);
        $em->flush();

        return $this->redirectToRoute('gadgetchart_edit', ['id' => $gadgetChart_new->getId()]);
    }
}