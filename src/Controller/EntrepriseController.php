<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EntrepriseController extends AbstractController
{

    public function services($entreprise)
    {
        $nomEntreprise = str_replace('-', ' ', $entreprise);

        $entrepriseObj = $this->getDoctrine()->getRepository(Entreprise::class)
            ->searchOneCaseInsensitive($nomEntreprise);

        if (!$entrepriseObj) {
            throw $this->createNotFoundException("L'entreprise n'existe pas.");
        }

        $repo = $this->getDoctrine()->getRepository(Service::class);
        $services = $repo->findBy(['entreprise' => $entrepriseObj]);

        return $this->render('entreprise/services.html.twig', [
            'services' => $services,
            'entreprise' => $entrepriseObj
        ]);
    }
}
