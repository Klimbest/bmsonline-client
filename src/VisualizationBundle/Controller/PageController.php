<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use VisualizationBundle\Entity\EventLink;
use VisualizationBundle\Entity\Page;
use VisualizationBundle\Form\PageType;

/**
 * Page controller.
 *
 * @Route("/page")
 */
class PageController extends Controller
{
    /**
     * Creates a new Page entity.
     *
     * @Route("/new", name="page_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse | Response
     */
    public function newAction(Request $request)
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);

            $eventLink = new EventLink();
            $eventLink->setPage($page);
            $em->persist($eventLink);

            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $page->getId()]);
        }

        return $this->render('VisualizationBundle:page:form.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a Page entity.
     *
     * @Route("/{id}", name="page_show")
     * @Method("GET")
     * @param Page $page
     * @return Response
     */
    public function showAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $parameters['pages'] = $em->getRepository('VisualizationBundle:Page')->findAll();
        $parameters['registers'] = $em->getRepository('VisualizationBundle:PanelVariable')->findForPage($page->getId());
        $parameters['charts'] = $em->getRepository('VisualizationBundle:GadgetChart')->findForPage($page->getId());
        $parameters['active_page'] = $page;
        $parameters['labels'] = true;
        return $this->render('VisualizationBundle:page:show.html.twig', $parameters);
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     * @Route("/{id}/edit", name="page_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Page $page
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Page $page)
    {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $page->getId()]);
        }

        return $this->render('VisualizationBundle:page:form.html.twig', [
            'page' => $page,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a Page entity.
     *
     * @Route("/{id}/delete", name="page_delete")
     * @param Page $page
     * @return RedirectResponse
     */
    public function deleteAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        foreach($page->getPanelsImage() as $element){
            $em->remove($element);
        }
        foreach($page->getPanelsText() as $element){
            $em->remove($element);
        }
        foreach($page->getPanelsVariable() as $element){
            $em->remove($element);
        }

        foreach($page->getInputsButton() as $element){
            $em->remove($element);
        }
        foreach($page->getInputsNumber() as $element){
            $em->remove($element);
        }
        foreach($page->getInputsRange() as $element){
            $em->remove($element);
        }

        foreach($page->getGadgetsClock() as $element){
            $em->remove($element);
        }
        foreach($page->getGadgetsProgressBar() as $element){
            $em->remove($element);
        }

        $em->remove($page);
        $em->flush();

        return $this->redirectToRoute('visualization');
    }

    private function generateCharts()
    {

    }

}