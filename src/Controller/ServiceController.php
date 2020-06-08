<?php

namespace App\Controller;

use App\Entity\Entreprise;
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
        $warningOrDanger = $doctrine->getRepository(Service::class)->findWarningOrDanger();

        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
            'entreprises' => $entreprises,
            'warningOrDanger' => $warningOrDanger,
        ]);
    }

    public function details($entreprise, $name)
    {
        $nomEntreprise = str_replace('-', ' ', $entreprise);
        $nomService = str_replace('-', ' ', $name);

        $entrepriseObj = $this->getDoctrine()->getRepository(Entreprise::class)
            ->searchOneCaseInsensitive($nomEntreprise);

        if (!$entrepriseObj) {
            throw $this->createNotFoundException("L'entreprise n'existe pas.");
        }

        $service = $this->getDoctrine()->getRepository(Service::class)
            ->findOneBy(array('nom' => $nomService, 'entreprise' => $entrepriseObj));

        if (!$service) {
            throw $this->createNotFoundException("Le service demandé n'existe pas");
        }

        return $this->render('service/details.html.twig');
    }

    /**
     * @Route("/service/liste", name="listeServices")
     */
    public function liste()
    {
        $repo = $this->getDoctrine()->getRepository(Service::class);
        $services = $repo->findAll();
        $warningOrDanger = $repo->findWarningOrDanger();

        return $this->render('service/liste.html.twig', [
           'services' => $services,
            'warningOrDanger' => $warningOrDanger
        ]);
    }
}
