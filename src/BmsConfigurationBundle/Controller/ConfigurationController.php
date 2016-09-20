<?php

//src/BmsBundle/Controller/BmsController.php

namespace BmsConfigurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use BmsConfigurationBundle\Form\CommunicationTypeType;
use BmsConfigurationBundle\Form\DeviceType;
use BmsConfigurationBundle\Form\RegisterType;
use BmsConfigurationBundle\Entity\CommunicationType;
use BmsConfigurationBundle\Entity\Device;
use BmsConfigurationBundle\Entity\Register;
use BmsConfigurationBundle\Entity\RegisterCurrentData;
use BmsConfigurationBundle\Entity\TechnicalInformation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ConfigurationController extends Controller
{

    /**
     * @Route("/", name="bms_configuration_index", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function bmsConfigurationIndexAction(Request $request)
    {
        $session = $request->getSession();
        $target = $session->get('target');

        $technicalInformationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:TechnicalInformation');
        $sync = $technicalInformationRepo->findOneBy(['name' => 'dataToSync'])->getStatus();

        $communicationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:CommunicationType');
        $communicationTypes = $communicationRepo->getActive();
        $comm_id = $session->get('comm_id');
        $device_id = $session->get('device_id');

        $session->remove('comm_id');
        $session->remove('device_id');

        if ($request->isXmlHttpRequest()) {
            $template = $this->get('templating')->render('BmsConfigurationBundle::target.html.twig',
                ['comms' => $communicationTypes, 'target' => $target, 'sync' => $sync]);

            return new JsonResponse(['ret' => $template]);
        } else {
            return $this->render('BmsConfigurationBundle::index.html.twig',
                ['comms' => $communicationTypes, 'target' => $target, 'comm_id' => $comm_id, 'device_id' => $device_id, 'sync' => $sync]);
        }
    }

    /**
     * @Route("/{comm_id}", name="bms_configuration_communication_type", requirements={"comm_id" = "\d+"}, options={"expose"=true})
     * @param $comm_id
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function configureCommunicationTypeAction($comm_id, Request $request)
    {
        //ustawienie połączenia na bazę danego obiektu
        $em = $this->getDoctrine()->getManager();
        $communicationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:CommunicationType');
        //pobiera aktywne porty(do menu)
        $communicationTypes = $communicationRepo->getActive();
        //pobiera zasoby do zawartości strony
        $comm = $communicationRepo->find($comm_id);

        $form = $this->createForm(CommunicationTypeType::class, $comm, [
            'action' => $this->generateUrl('bms_configuration_communication_type', ['comm_id' => $comm_id]),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $comm->setUpdated(new \DateTime());
            $em->persist($comm);
            $em->flush();
            $this->setDataToSync();
            return $this->redirectToRoute('bms_configuration_index');
        } else if ($request->isXmlHttpRequest()) {
            $template = $this->get('templating')->render('BmsConfigurationBundle::communicationType.html.twig',
                ['comms' => $communicationTypes, 'comm' => $comm, 'form' => $form->createView()]
            );
            return new JsonResponse(['ret' => $template]);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/{comm_id}/{device_id}", name="bms_configuration_device" ,requirements={"comm_id" = "\d+", "device_id" = "\d+"}, options={"expose"=true})
     * @param $comm_id
     * @param $device_id
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function configureDeviceAction($comm_id, $device_id, Request $request)
    {
        //ustawienie połączenia na bazę danego obiektu
        $em = $this->getDoctrine()->getManager();
        $communicationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:CommunicationType');
        $deviceRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Device');
        $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
        //pobiera aktywne porty(do menu)
        $communicationTypes = $communicationRepo->getActive();
        //pobiera zasoby do zawartości strony
        $device = $deviceRepo->find($device_id);
        $registers = $registerRepo->getAllOrderByAdr($device_id);
        $form = $this->createForm(DeviceType::class, $device,
            ['action' => $this->generateUrl('bms_configuration_device', ['comm_id' => $comm_id, 'device_id' => $device_id]),
                'method' => 'POST'
            ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($device->getActive() == 0) {
                $device->setScanState(-1);
                $regs = $device->getRegisters();
                foreach ($regs as $r) {
                    $r->getRegisterCurrentData()->setRealValueHex(null)->setRealValue(null)->setFixedValue(null);
                }
            }
            $em->flush();
            $session = $request->getSession();
            $session->set('comm_id', $comm_id);
            $this->setDataToSync();

            return $this->redirectToRoute('bms_configuration_index');
        } else if ($request->isXmlHttpRequest()) {
            $template = $this->get('templating')->render('BmsConfigurationBundle::device.html.twig',
                ['comms' => $communicationTypes, 'device' => $device, 'registers' => $registers, 'form' => $form->createView()]
            );

            return new JsonResponse(['ret' => $template]);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/{comm_id}/{device_id}/{register_id}", name="bms_configuration_register", requirements={"comm_id" = "\d+", "device_id" = "\d+", "register_id" = "\d+"}, options={"expose"=true})
     * @param $comm_id
     * @param $device_id
     * @param $register_id
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function configureRegisterAction($comm_id, $device_id, $register_id, Request $request)
    {
        //ustawienie połączenia na bazę danego obiektu
        $em = $this->getDoctrine()->getManager();
        $communicationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:CommunicationType');
        $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
        //pobiera aktywne porty(do menu)
        $communicationTypes = $communicationRepo->getActive();
        //pobiera zasoby do zawartości strony
        $register = $registerRepo->find($register_id);
        $form = $this->createForm(RegisterType::class, $register,
            ['action' => $this->generateUrl('bms_configuration_register', ['comm_id' => $comm_id, 'device_id' => $device_id, 'register_id' => $register_id]),
                'method' => 'POST'
            ]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($register);
            if ($register->getBitRegister() == 1) {
                $bitRegisters = $register->getBitRegisters();
                foreach ($bitRegisters as $br) {
                    $em->persist($br);
                }
            } else {
                $bitRegisters = $register->getBitRegisters();
                foreach ($bitRegisters as $br) {
                    $register->removeBitRegister($br);
                    $em->remove($br);
                }
            }
            $em->flush();
            $session = $request->getSession();
            $session->set('comm_id', $comm_id);
            $session->set('device_id', $device_id);

            $em->getConnection()->exec("ALTER TABLE bit_register AUTO_INCREMENT = 1;");

            $this->setDataToSync();
            return $this->redirectToRoute('bms_configuration_index');
        } else if ($request->isXmlHttpRequest()) {

            $template = $this->get('templating')
                ->render('BmsConfigurationBundle::register.html.twig', ['comms' => $communicationTypes, 'register' => $register, 'form' => $form->createView()]);

            return new JsonResponse(['ret' => $template]);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/{comm_id}/add_device", name="bms_configuration_add_device", requirements={"comm_id" = "\d+"}, options={"expose"=true})
     * @param $comm_id
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addDeviceAction($comm_id, Request $request)
    {
        $communicationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:CommunicationType');
        //pobiera aktywne porty(do menu)
        $communicationTypes = $communicationRepo->getActive();
        //pobiera zasoby do zawartości strony
        $comm = $communicationRepo->find($comm_id);
        $device = new Device();
        $form = $this->createForm(DeviceType::class, $device, [
            'action' => $this->generateUrl('bms_configuration_add_device', ['comm_id' => $comm_id]),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $device->setCommunicationType($comm);
            $em = $this->getDoctrine()->getManager();
            $em->persist($device);
            $em->flush();

            $session = $request->getSession();
            $session->set('comm_id', $comm_id);
            $this->setDataToSync();
            return $this->redirectToRoute('bms_configuration_index');
        } else if ($request->isXmlHttpRequest()) {
            $template = $this->get('templating')
                ->render('BmsConfigurationBundle::newDevice.html.twig', ['comms' => $communicationTypes, 'comm' => $comm, 'form' => $form->createView()]);
            return new JsonResponse(['ret' => $template]);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/{comm_id}/{device_id}/add_register", name="bms_configuration_add_register", requirements={"comm_id" = "\d+", "device_id" = "\d+"}, options={"expose"=true})
     * @param $comm_id
     * @param $device_id
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addRegisterAction($comm_id, $device_id, Request $request)
    {
        //ustawienie połączenia na bazę danego obiektu
        $em = $this->getDoctrine()->getManager();
        $communicationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:CommunicationType');
        $deviceRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Device');
        //pobiera aktywne porty(do menu)
        $communicationTypes = $communicationRepo->getActive();
        //pobiera zasoby do zawartości strony
        $comm = $communicationRepo->find($comm_id);
        $device = $deviceRepo->find($device_id);
        $register = new Register();
        $registerCD = new RegisterCurrentData();
        $form = $this->createForm(RegisterType::class, $register,
            ['action' => $this->generateUrl('bms_configuration_add_register', ['comm_id' => $comm_id, 'device_id' => $device_id]),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $register->setDevice($device);
            $em->persist($register);
            if ($register->getBitRegister() == 1) {
                $bitRegisters = $register->getBitRegisters();
                foreach ($bitRegisters as $br) {
                    $br->setRegister($register);
                    $em->persist($br);
                }
            }
            $em->persist($register);
            $em->flush();
            $registerCD->setRegister($register);
            $registerCD->setTimeOfUpdate(new \DateTime());
            $em->persist($registerCD);
            $em->flush();
            $register->setRegisterCurrentData($registerCD);
            $em->persist($register);
            $em->flush();
            $session = $request->getSession();
            $session->set('comm_id', $comm_id);
            $session->set('device_id', $device_id);

            $em->getConnection()->exec("ALTER TABLE bit_register AUTO_INCREMENT = 1;");
            $this->setDataToSync();
            return $this->redirectToRoute('bms_configuration_index');
        } else if ($request->isXmlHttpRequest()) {
            $template = $this->get('templating')
                ->render('BmsConfigurationBundle::newRegister.html.twig',
                    ['comms' => $communicationTypes, 'comm' => $comm, 'device' => $device, 'form' => $form->createView()]
                );
            return new JsonResponse(['ret' => $template]);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/{comm_id}/{device_id}/delete", name="bms_configuration_del_device", requirements={"comm_id" = "\d+", "device_id" = "\d+"}, options={"expose"=true})
     * @param $comm_id
     * @param $device_id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delDeviceAction($comm_id, $device_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $deviceRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Device');
        $device = $deviceRepo->find($device_id);
        $registers = $device->getRegisters();
        foreach ($registers as $r) {
            $rCD = $r->getRegisterCurrentData();
            $em->remove($rCD);
            $em->remove($r);
        }
        $em->flush();
        $em->remove($device);
        $em->flush();
        $em->getConnection()->exec("ALTER TABLE device AUTO_INCREMENT = 1;");
        $em->getConnection()->exec("ALTER TABLE register AUTO_INCREMENT = 1;");

        $session = $request->getSession();
        $session->set('comm_id', $comm_id);
        $this->setDataToSync();

        return $this->redirectToRoute('bms_configuration_index');
    }

    /**
     * @Route("/{comm_id}/{device_id}/{register_id}/delete", name="bms_configuration_del_register", requirements={"comm_id" = "\d+", "device_id" = "\d+", "register_id" = "\d+"}, options={"expose"=true})
     * @param $comm_id
     * @param $device_id
     * @param $register_id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delRegisterAction($comm_id, $device_id, $register_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
        $register = $registerRepo->find($register_id);

        $em->remove($register);
        $em->flush();
        $em->getConnection()->exec("ALTER TABLE register AUTO_INCREMENT = 1;");
        $em->getConnection()->exec("ALTER TABLE register_current_data AUTO_INCREMENT = 1;");
        $em->getConnection()->exec("ALTER TABLE bit_register AUTO_INCREMENT = 1;");

        $session = $request->getSession();
        $session->set('comm_id', $comm_id);
        $session->set('device_id', $device_id);
        $this->setDataToSync();

        return $this->redirectToRoute('bms_configuration_index');
    }


    /**
     * @Route("/{comm_id}/{device_id}/{register_id}/refresh", name="bms_configuration_refresh_page", requirements={"comm_id" = "\d+", "device_id" = "\d+", "register_id" = "\d+"}, options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshPageAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $technicalInformationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:TechnicalInformation');
            $deviceRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Device');
            $times = $deviceRepo->getLastReadTimes();
            $devicesStatus = $deviceRepo->getDevicesStatus();
            $time = $technicalInformationRepo->getRpiStatus();
            $ret['devicesStatus'] = $devicesStatus;
            if ($time) {
                $ret['state'] = $time[0]["time"]->getTimestamp();
            } else {
                $ret['state'] = null;
            }
            $ret["times_of_update"] = $times;
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     *
     */
    public function setDataToSync()
    {
        $technicalInformationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:TechnicalInformation');
        $sync = $technicalInformationRepo->findOneBy(['name' => 'dataToSync']);
        $sync->setStatus(1);
        $this->getDoctrine()->getManager()->flush();
    }

    /**
     * @Route("/synchronize", name="bms_configuration_synchronize_database", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function synchronizeDatabaseAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $vpn = $this->getParameter('vpn');

            $process = new Process("ssh pi@" . $vpn . " ./bin/dbSync.sh");
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $technicalInformationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:TechnicalInformation');
            $sync = $technicalInformationRepo->findOneBy(['name' => 'dataToSync']);

            $sync->setStatus(0);

            $this->getDoctrine()->getManager()->flush();
            $ret['sync'] = $sync->getStatus();
            $ret['output'] = $process->getOutput();

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/stopScanners", name="bms_configuration_stop_scanners", options={"expose"=true})
     * @param Request $request
     */
    public function stopScannersAction(Request $request)
    {
        $host = $request->getHost();
        $h = explode(".", $host);
        $process = new Process("bash ../../_bin/orderToRPi.sh 'bin/stopScanner' " . $h[0]);
        //$process->disableOutput();
        $process->run();
    }

}
