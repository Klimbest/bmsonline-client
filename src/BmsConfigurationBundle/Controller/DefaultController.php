<?php

//src/BmsBundle/Controller/DefaultController.php

namespace BmsConfigurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use BmsConfigurationBundle\Form\CommunicationTypeType;
use BmsConfigurationBundle\Form\DeviceType;
use BmsConfigurationBundle\Form\RegisterType;
use BmsConfigurationBundle\Entity\Device;
use BmsConfigurationBundle\Entity\Register;
use BmsConfigurationBundle\Entity\RegisterCurrentData;

class DefaultController extends Controller {

    public function bmsConfigurationIndexAction(Request $request) {

        $session = $request->getSession();
        $target = $session->get('target');
        
        $communicationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:CommunicationType');
        $communicationTypes = $communicationRepo->createQueryBuilder('ct')
                        ->join('ct.hardware_id', 'h')
                        ->getQuery()->getResult();
        $comm_id = $session->get('comm_id');
        $device_id = $session->get('device_id');
        $session->remove('comm_id');
        $session->remove('device_id');

        if ($request->isXmlHttpRequest()) {
            $template = $this->container
                            ->get('templating')->render('BmsConfigurationBundle::target.html.twig', ['comms' => $communicationTypes, 'target' => $target]);

            return new JsonResponse(array('ret' => $template));
        } else {
            return $this->render('BmsConfigurationBundle::index.html.twig', ['comms' => $communicationTypes, 'target' => $target, 'comm_id' => $comm_id, 'device_id' => $device_id]);
        }
    }

    public function configureCommunicationTypeAction($comm_id, Request $request) {

        //ustawienie połączenia na bazę danego obiektu
        $em = $this->getDoctrine()->getManager();
        $communicationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:CommunicationType');
        //pobiera aktywne porty(do menu)
        $communicationTypes = $communicationRepo->createQueryBuilder('ct')
                        ->join('ct.hardware_id', 'h')
                        ->where('h.active = 1')
                        ->getQuery()->getResult();
        //pobiera zasoby do zawartości strony
        $comm = $communicationRepo->find($comm_id);

        $form = $this->createForm(CommunicationTypeType::class, $comm, array(
            'action' => $this->generateUrl('bms_configuration_communication_type', array('comm_id' => $comm_id)),
            'method' => 'POST'
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {

            $name = $form['name']->getData();
            $type = $form['type']->getData();
            $baudRate = $form['baudRate']->getData();
            $parity = $form['parity']->getData();
            $dataBits = $form['dataBits']->getData();
            $stopBits = $form['stopBits']->getData();
            $ipAddress = $form['ipAddress']->getData();
            $port = $form['port']->getData();

            $comm->setName($name)
                    ->setType($type)
                    ->setBaudRate($baudRate)
                    ->setParity($parity)
                    ->setDataBits($dataBits)
                    ->setStopBits($stopBits)
                    ->setIpAddress($ipAddress)
                    ->setPort($port)
                    ->setUpdated(new \DateTime());

            $em->persist($comm);
            $em->flush();
            return $this->redirectToRoute('bms_configuration_index');
        } else if ($request->isXmlHttpRequest()) {
            $template = $this->container->get('templating')->render('BmsConfigurationBundle::communicationType.html.twig', ['comms' => $communicationTypes, 'comm' => $comm, 'form' => $form->createView()]);
            return new JsonResponse(array('ret' => $template));
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
    }

    public function configureDeviceAction($comm_id, $device_id, Request $request) {
        //ustawienie połączenia na bazę danego obiektu
        $em = $this->getDoctrine()->getManager();
        $communicationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:CommunicationType');
        $deviceRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Device');

        //pobiera aktywne porty(do menu)
        $communicationTypes = $communicationRepo->createQueryBuilder('ct')
                        ->join('ct.hardware_id', 'h')
                        ->where('h.active = 1')
                        ->getQuery()->getResult();
        //pobiera zasoby do zawartości strony
        $device = $deviceRepo->find($device_id);

        $form = $this->createForm(DeviceType::class, $device, array(
            'action' => $this->generateUrl('bms_configuration_device', array('comm_id' => $comm_id, 'device_id' => $device_id)),
            'method' => 'POST'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form['name']->getData();
            $description = $form['description']->getData();
            $modbus_address = $form['modbusAddress']->getData();

            $device->setName($name)
                    ->setDescription($description)
                    ->setModbusAddress($modbus_address);

            $em->persist($device);
            $em->flush();

            $session = $request->getSession();
            $session->set('comm_id', $comm_id);

            return $this->redirectToRoute('bms_configuration_index');
        } else if ($request->isXmlHttpRequest()) {
            $template = $this->container
                            ->get('templating')->render('BmsConfigurationBundle::device.html.twig', ['comms' => $communicationTypes, 'device' => $device, 'form' => $form->createView()]);

            return new JsonResponse(array('ret' => $template));
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
    }

    public function configureRegisterAction($comm_id, $device_id, $register_id, Request $request) {
        //ustawienie połączenia na bazę danego obiektu
        $em = $this->getDoctrine()->getManager();
        $communicationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:CommunicationType');
        $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');

        //pobiera aktywne porty(do menu)
        $communicationTypes = $communicationRepo->createQueryBuilder('ct')
                        ->join('ct.hardware_id', 'h')
                        ->where('h.active = 1')
                        ->getQuery()->getResult();

        //pobiera zasoby do zawartości strony
        $register = $registerRepo->find($register_id);

        $form = $this->createForm(RegisterType::class, $register, array(
            'action' => $this->generateUrl('bms_configuration_register', array( 'comm_id' => $comm_id, 'device_id' => $device_id, 'register_id' => $register_id)),
            'method' => 'POST'
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form['name']->getData();
            $description = $form['description']->getData();
            $register_address = $form['register_address']->getData();
            $function = $form ['function']->getData();
            $scan_queue = $form['scan_queue']->getData();
            $register_size = $form['register_size']->getData();
            $display_suffix = $form['display_suffix']->getData();
            $modificator_read = $form['modificator_read']->getData();
            $modificator_write = $form['modificator_write']->getData();
            $archive = $form['archive']->getData();
            $active = $form['active']->getData();

            $register->setName($name)
                    ->setFunction($function)
                    ->setDescription($description)
                    ->setRegisterAddress($register_address)
                    ->setScanQueue($scan_queue)
                    ->setRegisterSize($register_size)
                    ->setDisplaySuffix($display_suffix)
                    ->setModificatorRead($modificator_read)
                    ->setModificatorWrite($modificator_write)
                    ->setArchive($archive)
                    ->setActive($active);

            $em->persist($register);
            $em->flush();

            $session = $request->getSession();
            $session->set('comm_id', $comm_id);
            $session->set('device_id', $device_id);

            
            return $this->redirectToRoute('bms_configuration_index');
        } else if ($request->isXmlHttpRequest()) {

            $template = $this->container
                            ->get('templating')->render('BmsConfigurationBundle::register.html.twig', ['comms' => $communicationTypes, 'register' => $register, 'form' => $form->createView()]);

            return new JsonResponse(array('ret' => $template));
        } else {

            return $this->render('BmsConfigurationBundle::register.html.twig', ['comms' => $communicationTypes, 'target' => $target, 'register' => $register, 'form' => $form->createView()]);
            //throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
    }

    public function addDeviceAction($comm_id, Request $request) {
        //ustawienie połączenia na bazę danego obiektu
        $em = $this->getDoctrine()->getManager();
        $communicationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:CommunicationType');
        //pobiera aktywne porty(do menu)
        $communicationTypes = $communicationRepo->createQueryBuilder('ct')
                        ->join('ct.hardware_id', 'h')
                        ->where('h.active = 1')
                        ->getQuery()->getResult();
        //pobiera zasoby do zawartości strony
        $comm = $communicationRepo->find($comm_id);
        $device = new Device();
        $form = $this->createForm(DeviceType::class, $device, array(
            'action' => $this->generateUrl('bms_configuration_add_device', array('comm_id' => $comm_id)),
            'method' => 'POST'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form['name']->getData();
            $description = $form['description']->getData();
            $modbus_address = $form['modbusAddress']->getData();
            $active = $form['active']->getData();
            $localization = $form['localization']->getData();

            $device->setName($name)
                    ->setCommunicationType($comm)
                    ->setDescription($description)
                    ->setModbusAddress($modbus_address)
                    ->setActive($active)
                    ->setLocalization($localization);

            $em->persist($device);
            $em->flush();

            $session = $request->getSession();
            $session->set('comm_id', $comm_id);

            return $this->redirectToRoute('bms_configuration_index');
        } else if ($request->isXmlHttpRequest()) {
            $template = $this->container
                            ->get('templating')->render('BmsConfigurationBundle::newDevice.html.twig', ['comms' => $communicationTypes, 'comm' => $comm, 'form' => $form->createView()]);

            return new JsonResponse(array('ret' => $template));
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
    }

    public function addRegisterAction($comm_id, $device_id, Request $request) {
        

        //ustawienie połączenia na bazę danego obiektu
        $em = $this->getDoctrine()->getManager();
        $communicationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:CommunicationType');
        $deviceRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Device');
        //pobiera aktywne porty(do menu)
        $communicationTypes = $communicationRepo->createQueryBuilder('ct')
                        ->join('ct.hardware_id', 'h')
                        ->where('h.active = 1')
                        ->getQuery()->getResult();
        //pobiera zasoby do zawartości strony
        $comm = $communicationRepo->find($comm_id);
        $device = $deviceRepo->find($device_id);
        $register = new Register();
        $registerCD = new RegisterCurrentData();
        $form = $this->createForm(RegisterType::class, $register, array(
            'action' => $this->generateUrl('bms_configuration_add_register', array('comm_id' => $comm_id, 'device_id' => $device_id)),
            'method' => 'POST'
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $registerAddress = $form['register_address']->getData();
            $function = $form['function']->getData();
            $scanQueue = $form['scan_queue']->getData();
            $register_size = $form['register_size']->getData();
            $name = $form['name']->getData();
            $description = $form['description']->getData();
            $displaySuffix = $form['display_suffix']->getData();
            $modificatorRead = $form['modificator_read']->getData();
            $modificatorWrite = $form['modificator_write']->getData();
            $archive = $form['archive']->getData();
            $active = $form['active']->getData();


            $register->setRegisterAddress($registerAddress)
                    ->setFunction($function)
                    ->setScanQueue($scanQueue)
                    ->setRegisterSize($register_size)
                    ->setName($name)
                    ->setDescription($description)
                    ->setDisplaySuffix($displaySuffix)
                    ->setModificatorRead($modificatorRead)
                    ->setModificatorWrite($modificatorWrite)
                    ->setArchive($archive)
                    ->setActive($active)
                    ->setDevice($device);
            
            $em->persist($register);
            $em->flush();
            $registerCD->setRegister($register);
            $em->persist($registerCD);
            $em->flush();
            $register->setRegisterCurrentData($registerCD);
            $em->persist($register);
            $em->flush();
            $session = $request->getSession();
            $session->set('comm_id', $comm_id);
            $session->set('device_id', $device_id);
            
            
            return $this->redirectToRoute('bms_configuration_index');
        } else if ($request->isXmlHttpRequest()) {
            $template = $this->container
                            ->get('templating')->render('BmsConfigurationBundle::newRegister.html.twig', ['comms' => $communicationTypes, 'comm' => $comm, 'device' => $device, 'form' => $form->createView()]);

            return new JsonResponse(array('ret' => $template));
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
    }

    public function delDeviceAction($comm_id, $device_id, Request $request) {

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

        return $this->redirectToRoute('bms_configuration_index');
    }

    public function delRegisterAction( $comm_id, $device_id, $register_id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
        $register = $registerRepo->find($register_id);

        $registerCD = $register->getRegisterCurrentData();

        $em->remove($registerCD);
        $em->remove($register);
        $em->flush();
        $em->getConnection()->exec("ALTER TABLE register AUTO_INCREMENT = 1;");
        $em->getConnection()->exec("ALTER TABLE register_current_data AUTO_INCREMENT = 1;");

        $session = $request->getSession();
        $session->set('comm_id', $comm_id);
        $session->set('device_id', $device_id);

        return $this->redirectToRoute('bms_configuration_index');
    }

    public function delManyRegistersAction( $comm_id, $device_id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');

        $checkedRegisters = $request->request->get('checkedRegId');
        if ($checkedRegisters > 0) {
            foreach ($checkedRegisters as $registersToDeleteId) {
                $registersToDeleteId = intval($registersToDeleteId);

                $register = $registerRepo->find($registersToDeleteId);

                $registerCD = $register->getRegisterCurrentData();

                $em->remove($registerCD);
                $em->remove($register);
            }
            $em->flush();
        }

        $em->getConnection()->exec("ALTER TABLE register AUTO_INCREMENT = 1;");
        $em->getConnection()->exec("ALTER TABLE register_current_data AUTO_INCREMENT = 1;");

        $session = $request->getSession();
        $session->set('comm_id', $comm_id);
        $session->set('device_id', $device_id);

        return $this->redirectToRoute('bms_configuration_index');
    }

    public function delManyDevicesAction( $comm_id, Request $request) {

        return new Response();
    }
            
   

}
