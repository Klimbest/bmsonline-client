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
use BmsConfigurationBundle\Entity\BitRegister;
use BmsConfigurationBundle\Entity\RegisterCurrentData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Process\Process;

class DefaultController extends Controller {

    /**
     * @Route("/", name="bms_configuration_index", options={"expose"=true})
     */
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

    /**
     * @Route("/{comm_id}", name="bms_configuration_communication_type", requirements={"comm_id" = "\d+"}, options={"expose"=true})
     */
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

    /**
     * @Route("/{comm_id}/{device_id}", name="bms_configuration_device" ,requirements={"comm_id" = "\d+", "device_id" = "\d+"}, options={"expose"=true})
     */
    public function configureDeviceAction($comm_id, $device_id, Request $request) {
        //ustawienie połączenia na bazę danego obiektu
        $em = $this->getDoctrine()->getManager();
        $communicationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:CommunicationType');
        $deviceRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Device');
        $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
        //pobiera aktywne porty(do menu)
        $communicationTypes = $communicationRepo->createQueryBuilder('ct')
                        ->join('ct.hardware_id', 'h')
                        ->where('h.active = 1')
                        ->getQuery()->getResult();
        //pobiera zasoby do zawartości strony
        $device = $deviceRepo->find($device_id);

        $registers = $registerRepo->getAllOrderByAdr($device_id);


        $form = $this->createForm(DeviceType::class, $device, array(
            'action' => $this->generateUrl('bms_configuration_device', array('comm_id' => $comm_id, 'device_id' => $device_id)),
            'method' => 'POST'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            $session = $request->getSession();
            $session->set('comm_id', $comm_id);

            return $this->redirectToRoute('bms_configuration_index');
        } else if ($request->isXmlHttpRequest()) {
            $template = $this->container
                            ->get('templating')->render('BmsConfigurationBundle::device.html.twig', ['comms' => $communicationTypes, 'device' => $device, 'registers' => $registers, 'form' => $form->createView()]);

            return new JsonResponse(array('ret' => $template));
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/{comm_id}/{device_id}/{register_id}", name="bms_configuration_register", requirements={"comm_id" = "\d+", "device_id" = "\d+", "register_id" = "\d+"}, options={"expose"=true})
     */
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
            'action' => $this->generateUrl('bms_configuration_register', array('comm_id' => $comm_id, 'device_id' => $device_id, 'register_id' => $register_id)),
            'method' => 'POST'
        ));

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

            $template = $this->container
                            ->get('templating')->render('BmsConfigurationBundle::register.html.twig', ['comms' => $communicationTypes, 'register' => $register, 'form' => $form->createView()]);

            return new JsonResponse(array('ret' => $template));
        } else {

            //return $this->render('BmsConfigurationBundle::register.html.twig', ['comms' => $communicationTypes, 'register' => $register, 'form' => $form->createView()]);
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/{comm_id}/add_device", name="bms_configuration_add_device", requirements={"comm_id" = "\d+"}, options={"expose"=true})
     */
    public function addDeviceAction($comm_id, Request $request) {

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

            $em = $this->getDoctrine()->getManager();
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

    /**
     * @Route("/{comm_id}/{device_id}/add_register", name="bms_configuration_add_register", requirements={"comm_id" = "\d+", "device_id" = "\d+"}, options={"expose"=true})
     */
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

        if ($form->isSubmitted() ) {

            $registerAddress = $form['register_address']->getData();
            $function = $form['function']->getData();
            $scanQueue = $form['scan_queue']->getData();
            $register_size = $form['register_size']->getData();
            $name = $form['name']->getData();
            $description = $form['description']->getData();
            $description2 = $form['description2']->getData();
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
                    ->setDescription2($description2)
                    ->setDisplaySuffix($displaySuffix)
                    ->setModificatorRead($modificatorRead)
                    ->setModificatorWrite($modificatorWrite)
                    ->setArchive($archive)
                    ->setActive($active)
                    ->setDevice($device);

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


            return $this->redirectToRoute('bms_configuration_index');
        } else if ($request->isXmlHttpRequest()) {
            $template = $this->container
                            ->get('templating')->render('BmsConfigurationBundle::newRegister.html.twig', ['comms' => $communicationTypes, 'comm' => $comm, 'device' => $device, 'form' => $form->createView()]);

            return new JsonResponse(array('ret' => $template));
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/{comm_id}/{device_id}/delete", name="bms_configuration_del_device", requirements={"comm_id" = "\d+", "device_id" = "\d+"}, options={"expose"=true})
     */
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

    /**
     * @Route("/{comm_id}/{device_id}/{register_id}/delete", name="bms_configuration_del_register", requirements={"comm_id" = "\d+", "device_id" = "\d+", "register_id" = "\d+"}, options={"expose"=true})
     */
    public function delRegisterAction($comm_id, $device_id, $register_id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
        $register = $registerRepo->find($register_id);

        $registerCD = $register->getRegisterCurrentData();

        $bitRegisters = $register->getBitRegisters();
        foreach ($bitRegisters as $br) {
            $register->removeBitRegister($br);
            $em->remove($br);
        }
        
        $em->remove($registerCD);
        $em->remove($register);
        $em->flush();
        $em->getConnection()->exec("ALTER TABLE register AUTO_INCREMENT = 1;");
        $em->getConnection()->exec("ALTER TABLE register_current_data AUTO_INCREMENT = 1;");
        $em->getConnection()->exec("ALTER TABLE bit_register AUTO_INCREMENT = 1;");

        $session = $request->getSession();
        $session->set('comm_id', $comm_id);
        $session->set('device_id', $device_id);

        return $this->redirectToRoute('bms_configuration_index');
    }

    /**
     * @Route("/{comm_id}/{device_id}/registers-delete", name="bms_configuration_del_many_registers", requirements={"comm_id" = "\d+", "device_id" = "\d+"}, options={"expose"=true})
     */
    public function delManyRegistersAction($comm_id, $device_id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');

        $checkedRegisters = $request->request->get('checkedRegId');
        if ($checkedRegisters > 0) {
            foreach ($checkedRegisters as $registersToDeleteId) {
                $registersToDeleteId = intval($registersToDeleteId);

                $register = $registerRepo->find($registersToDeleteId);

                $registerCD = $register->getRegisterCurrentData();

                $bitRegisters = $register->getBitRegisters();
                foreach ($bitRegisters as $br) {
                    $register->removeBitRegister($br);
                    $em->remove($br);
                }
                $em->remove($registerCD);
                $em->remove($register);                
            }
            $em->flush();
        }

        $em->getConnection()->exec("ALTER TABLE register AUTO_INCREMENT = 1;");
        $em->getConnection()->exec("ALTER TABLE register_current_data AUTO_INCREMENT = 1;");
        $em->getConnection()->exec("ALTER TABLE bit_register AUTO_INCREMENT = 1;");
        
        $session = $request->getSession();
        $session->set('comm_id', $comm_id);
        $session->set('device_id', $device_id);

        return $this->redirectToRoute('bms_configuration_index');
    }

    /**
     * @Route("/{comm_id}/registers-delete", name="bms_configuration_del_many_devices", requirements={"comm_id" = "\d+"}, options={"expose"=true})
     */
    public function delManyDevicesAction($comm_id, Request $request) {

        return new Response();
    }

    /**
     * @Route("/{comm_id}/{device_id}/{register_id}/refresh", name="bms_configuration_refresh_page", requirements={"comm_id" = "\d+", "device_id" = "\d+", "register_id" = "\d+"}, options={"expose"=true})
     */
    public function refreshPageAction($comm_id, $device_id, $register_id, Request $request) {
        if ($request->isXmlHttpRequest()) {

            $em = $this->getDoctrine()->getManager();
            $registerDevice = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Device');
            $devices = $registerDevice->findAll();
            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
            $registers = $registerRepo->findAll();
            $ret = array();
            $times = array();
            $time = 0;
            foreach ($devices as $device) {
                $did = $device->getId();
                $active = $device->getActive();
                $registers = $device->getRegisters();
                $time = 0;
                foreach ($registers as $register) {
                    $lastRead = date_timestamp_get($register->getRegisterCurrentData()->getTimeOfUpdate());

                    if ($lastRead > $time) {
                        $time = $lastRead;
                    }
                    $id = $register->getId();
                    $val = $register->getRegisterCurrentData()->getFixedValue();
                    $ret[$id] = $val;
                }
                if ($active) {
                    $times[$did] = $time;
                } else {
                    $times[$did] = 0;
                }
            }

            $ret["times_of_update"] = $times;

            $ret["time_of_update"] = $time;
            $ret["refresh"] = 1;

            return new JsonResponse($ret);
        } else {
            throw new\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
    }

    public function setDataToSync() {

//            $em = $this->getDoctrine()->getManager();
//            $technicalInformationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:TechnicalInformation');
//            $ti = $technicalInformationRepo->findOneById(1);
//            $ti->setStatus(1);
//            $ti->setTime();
//            $em->persist($ti);
        $process = new Process("bash ../../_bin/orderToRPi.sh 'bin/dbSync'");
        $process->disableOutput();
        $process->run();
    }

}
