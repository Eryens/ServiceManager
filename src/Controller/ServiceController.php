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
        $doctrine = $this->getDoctrine();
        $entreprises = $doctrine->getRepository(Entreprise::class)->findAll();

        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
            'entreprises' => $entreprises
        ]);
    }

    /**
     * @Route("/service/liste", name="listeServices")
     */
    public function liste()
    {
        $services = $this->getDoctrine()->getRepository(Service::class)->findAll();
        $warningOrDanger = $this->getDoctrine()->getRepository(Service::class)->findWarningOrDanger();

        return $this->render('service/liste.html.twig', [
           'services' => $services,
            'warningOrDanger' => $warningOrDanger
        ]);
    }
}
