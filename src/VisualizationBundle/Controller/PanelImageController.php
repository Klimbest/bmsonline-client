<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use VisualizationBundle\Entity\EventChangeSource;
use VisualizationBundle\Entity\PanelImage;
use VisualizationBundle\Form\PanelImageType;
use VisualizationBundle\Form\EventLinkType;
use VisualizationBundle\Form\EventChangeSourceType;

/**
 * PanelImage controller.
 *
 * @Route("/panelimage")
 */
class PanelImageController extends Controller
{

    /**
     * Creates a new PanelImage entity.
     *
     * @Route("/page/{page_id}/new", name="panelimage_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $panelImage = new PanelImage();
        $page = $this->getDoctrine()->getManager()->getRepository('VisualizationBundle:Page')->find($request->get('page_id'));
        $panelImage->setPage($page);
        $images = self::getImages();
        $form = $this->createForm(PanelImageType::class, $panelImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelImage);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $panelImage->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:panelimage:form.html.twig', [
            'images' => $images,
            'panelImage' => $panelImage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing PanelImage entity.
     *
     * @Route("/{id}/edit", name="panelimage_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param PanelImage $panelImage
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, PanelImage $panelImage)
    {
        $images = self::getImages();
        $form = $this->createForm(PanelImageType::class, $panelImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelImage);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $panelImage->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:panelimage:form.html.twig', [
            'images' => $images,
            'panelImage' => $panelImage,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a PanelImage entity.
     *
     * @Route("/{id}/delete", name="panelimage_delete")
     * @param PanelImage $panelImage
     * @return RedirectResponse
     */
    public function deleteAction(PanelImage $panelImage)
    {
        $page_id = $panelImage->getPage()->getId();
        $em = $this->getDoctrine()->getManager();

        $events = $panelImage->getEventsChangeSource();
        foreach ($events as $event) {
            $em->remove($event);
        }

        $em->remove($panelImage);
        $em->flush();

        return $this->redirectToRoute('page_show', ['id' => $page_id]);
    }

    /**
     * Copy a PanelImage entity.
     *
     * @Route("/{id}/copy", name="panelimage_copy")
     * @param PanelImage $panelImage
     * @return RedirectResponse
     */
    public function copyAction(PanelImage $panelImage)
    {
        $panelImage->getPage()->getId();
        $panelImage_new = clone $panelImage;
        $events = $panelImage->getEventsChangeSource();
        $em = $this->getDoctrine()->getManager();
        $em->persist($panelImage_new);
        foreach ($events as $event) {
            $new_event = clone $event;
            $new_event->setPanelImage($panelImage_new);
            $em->persist($new_event);
        }

        $em->flush();

        return $this->redirectToRoute('panelimage_edit', ['id' => $panelImage_new->getId()]);
    }

    /**
     * List all PanelImage events.
     *
     * @Route("/{id}/events", name="panelimage_events")
     * @param PanelImage $panelImage
     * @return Response
     */
    public function eventsAction(PanelImage $panelImage)
    {
        return $this->render('VisualizationBundle:panelimage:events.html.twig', [
            'panelImage' => $panelImage,
        ]);
    }

    /**
     * Edit EventLink for PanelImage.
     *
     * @Route("/{id}/eventlink/edit", name="panelimage_eventlink_edit")
     * @param Request $request
     * @param PanelImage $panelImage
     * @return Response
     */
    public function eventLinkEditAction(Request $request, PanelImage $panelImage)
    {
        $form = $this->createForm(EventLinkType::class, $panelImage, ['data_class' => PanelImage::class]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelImage);
            $em->flush();

            return $this->redirectToRoute('panelimage_events', ['id' => $panelImage->getId()]);
        }

        return $this->render('VisualizationBundle:events:form_event_link.html.twig', [
            'element' => $panelImage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Add EvencChangeSource for PanelImage.
     *
     * @Route("/{id}/eventchangesource/add", name="panelimage_eventchangesource_add")
     * @param Request $request
     * @param PanelImage $panelImage
     * @return Response
     */
    public function eventChangeSourceAddAction(Request $request, PanelImage $panelImage)
    {
        $event = new EventChangeSource();
        $event->setPanelImage($panelImage);
        $images = self::getImages();
        $form = $this->createForm(EventChangeSourceType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('panelimage_events', ['id' => $panelImage->getId()]);
        }

        return $this->render('VisualizationBundle:events:form_event_change_source.html.twig', [
            'images' => $images,
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Add EvencChangeSource for PanelImage.
     *
     * @Route("/eventchangesource/{id}/edit", name="panelimage_eventchangesource_edit")
     * @param Request $request
     * @param EventChangeSource $event
     * @return Response
     */
    public function eventChangeSourceEditAction(Request $request, EventChangeSource $event)
    {
        $images = self::getImages();
        $form = $this->createForm(EventChangeSourceType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('panelimage_events', ['id' => $event->getPanelImage()->getId()]);
        }

        return $this->render('VisualizationBundle:events:form_event_change_source.html.twig', [
            'images' => $images,
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete EventShowHide for PanelImage.
     *
     * @Route("/{id}/eventchangesource/delete/{event_id}", name="panelimage_eventchangesource_delete")
     * @param Request $request
     * @param PanelImage $panelImage
     * @return Response
     */
    public function eventShowHideDeleteAction(Request $request, PanelImage $panelImage)
    {
        $event_id = $request->get('event_id');
        $event = $this->getDoctrine()->getRepository("VisualizationBundle:EventChangeSource")->find($event_id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('panelimage_events', ['id' => $panelImage->getId()]);
    }

    /**
     * @return array
     */
    private function getImages()
    {
        $finder = new Finder();
        $finder2 = new Finder();
        $finder->files()->in($this->getParameter('kernel.root_dir') . '/../web/images/system/');
        $images_system = [];
        foreach ($finder as $dir) {
            array_push($images_system, $dir->getRelativePathname());
        }
        $finder2->files()->in($this->getParameter('kernel.root_dir') . '/../web/images/user/');
        $images_user = [];
        foreach ($finder2 as $dir) {
            array_push($images_user, $dir->getRelativePathname());
        }
        $images['system'] = $images_system;
        $images['user'] = $images_user;

        return $images;
    }

    /**
     * @Route("/image/add ", name="send_image_to_server", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function addImageAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $imagesDir = $this->getParameter('kernel.root_dir') . '/../web';
            //get data
            $fileName = $request->get("fileName");
            //save original file
            $img = $request->files->get("file");

            $relativePath = '/images/user/';
            $img->move($imagesDir . $relativePath, $fileName);

            $imagePath = $relativePath . $fileName;

            $ret["fileName"] = $fileName;
            $ret["url"] = $imagePath;
            $ret["href"] = $this->generateUrl('remove_image_from_server', ['image_name' => $fileName]);

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/image/delete/{image_name}", name="remove_image_from_server")
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteImageFromServerAction(Request $request)
    {
        $imagesDir = $this->getParameter('kernel.root_dir') . '/../web/images/';
        $imageName = $request->get("image_name");

        $finder = new Finder();
        $finder->files()->name($imageName)->in($this->getParameter('kernel.root_dir') . '/../web/images/');
        foreach ($finder as $file) {
            $relativePath = $file->getRelativePathname();
        }
        $fs = new Filesystem();

        try {
            $fs->remove($imagesDir . $relativePath);
        } catch (IOExceptionInterface $e) {
            echo "An error occurred while creating your directory at " . $e->getPath();
        }
        return $this->redirect($request->headers->get('referer'));
    }

}