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
     * @Route("/page/{page_id}/new", name="inputbutton_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $inputButton = new InputButton();
        $page = $this->getDoctrine()->getManager()->getRepository('VisualizationBundle:Page')->find($request->get('page_id'));
        $inputButton->setPage($page);
        $images = self::getImages();
        $form = $this->createForm(InputButtonType::class, $inputButton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inputButton);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $inputButton->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:inputbutton:form.html.twig', [
            'images' => $images,
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
        $images = self::getImages();
        $form = $this->createForm(InputButtonType::class, $inputButton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inputButton);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $inputButton->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:inputbutton:form.html.twig', [
            'images' => $images,
            'inputButton' => $inputButton,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a InputButton entity.
     *
     * @Route("/{id}/delete", name="inputbutton_delete")
     * @param InputButton $inputButton
     * @return RedirectResponse
     */
    public function deleteAction(InputButton $inputButton)
    {
        $page_id = $inputButton->getPage()->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($inputButton);
        $em->flush();

        return $this->redirectToRoute('page_show', ['id' => $page_id]);
    }

    /**
     * Copy a InputButton entity.
     *
     * @Route("/{id}/copy", name="inputbutton_copy")
     * @param InputButton $inputButton
     * @return RedirectResponse
     */
    public function copyAction(InputButton $inputButton)
    {
        $inputButton->getPage()->getId();
        $inputButton_new = clone $inputButton;

        $em = $this->getDoctrine()->getManager();
        $em->persist($inputButton_new);
        $em->flush();

        return $this->redirectToRoute('inputbutton_edit', ['id' => $inputButton_new->getId()]);
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