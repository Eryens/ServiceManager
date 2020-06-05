<?php

namespace App\Controller;

use App\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    /**
     * @Route("/service", name="service")
     */
    public function index()
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }

    /**
     * @Route("/service/liste", name="listeServices")
     */
    public function liste()
    {
        $services = $this->getDoctrine()->getRepository(Service::class)->findAll();

        return $this->render('service/liste.html.twig', [
           'services' => $services
        ]);
    }
}
