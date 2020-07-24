<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Service;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class ApiServiceController extends AbstractController
{
    /**
     * @Route("/api/service/index", name="api_service", methods={"GET"})
     * @param ServiceRepository $serviceRepository
     * @return Response
     */
    public function index(ServiceRepository $serviceRepository)
    {
        $services = $serviceRepository->findAll();
        return $this->json($services, 200, [], ['groups' => 'post:read']);
    }

    /**
     * @Route("/api/service/index", name="api_service_store", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ServiceRepository $serviceRepository
     * @return JsonResponse
     */
    public function post(Request $request, SerializerInterface $serializer, ServiceRepository $serviceRepository)
    {
        $jsonRecieved = $request->getContent();

        try
        {
            $service = $serializer->deserialize($jsonRecieved, Service::class, 'json');
            $existing = $serviceRepository->alreadyExistsInDatabase($service);

            // If the service exists we only update the timestamps
            if ($existing) {
                $this->patch($existing);
                $message = 'The timestamps have been updated';
            }
            // If it doesn't we save it as a new service
            else {
                $this->store($service);
                $message = 'The service has been created';
            }

            return $this->json([
                'status' => 200,
                'message' => $message
            ]);
        }
        catch (NotEncodableValueException $e)
        {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Update the service's timestamps
    private function patch($existing)
    {
        $serviceRepository = $this->getDoctrine()->getRepository(Service::class);
        $em = $this->getDoctrine()->getManager();
        $existing = $serviceRepository->find($existing[0]->getId());
        $existing->updateTimestamps();
        $em->flush();
    }

    // Creates the new service
    private function store($service)
    {
        $entrepriseRepository = $this->getDoctrine()->getRepository(Entreprise::class);
        $em = $this->getDoctrine()->getManager();
        $existingEntreprise = $entrepriseRepository->findOneBy(['nom' => $service->getEntreprise()->getNom()]);

        if (!$existingEntreprise)
        {
            $entreprise = new Entreprise();
            $entreprise->setNom($service->getEntreprise()->getNom());
            $em->persist($entreprise);
            $em->flush();
            $service->setEntreprise($entreprise);
        }
        else
        {
            $service->setEntreprise($existingEntreprise);
        }
        $service->updateTimestamps();
        $em->persist($service);
        $em->flush();
    }
}
