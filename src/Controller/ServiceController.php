<?php

namespace App\Controller;

use App\Entity\Service;
use App\Repository\EntrepriseRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    /**
     * @Route("/service", name="service")
     * @param EntrepriseRepository $entrepriseRepository
     * @param ServiceRepository $serviceRepository
     * @return Response
     */
    public function index(EntrepriseRepository $entrepriseRepository, ServiceRepository $serviceRepository)
    {
        $entreprises = $entrepriseRepository->findAll();
        $warningOrDanger = $serviceRepository->findWarningOrDanger();

        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
            'entreprises' => $entreprises,
            'warningOrDanger' => $warningOrDanger,
        ]);
    }

    public function details($entreprise, $name, EntrepriseRepository $entrepriseRepository, ServiceRepository $serviceRepository)
    {
        $nomEntreprise = str_replace('-', ' ', $entreprise);
        $nomService = str_replace('-', ' ', $name);

        $entreprise = $entrepriseRepository->searchOneCaseInsensitive($nomEntreprise);

        if (!$entreprise) {
            throw $this->createNotFoundException("L'entreprise n'existe pas.");
        }

        $service = $serviceRepository->findOneBy(array('nom' => $nomService, 'entreprise' => $entreprise));

        if (!$service) {
            throw $this->createNotFoundException("Le service demandé n'existe pas");
        }

        return $this->render('service/details.html.twig', [
            'service' => $service,
            'entreprise' => $entreprise,
        ]);
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
