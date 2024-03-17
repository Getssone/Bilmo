<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityNotFoundException;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PhoneController extends AbstractController
{

    #[Route('/api/phones', name: 'app_all_phones', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour voir la liste des téléphones')]
    public function getAllUser(PhoneRepository $phones): JsonResponse
    {

        try {
            $seralizer = SerializerBuilder::create()->build();
            $context = SerializationContext::create()->setGroups('getPhones');
            $phonesList = $phones->findAll();
            $jsonUsersList = $seralizer->serialize($phonesList, 'json', $context);

            return new JsonResponse(
                $jsonUsersList,
                Response::HTTP_OK,
                [],
                true
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => "Une erreur lors de la récupération des données s'est produite."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    #[Route('/api/phones/{id}', name: 'app_phone_id', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour voir les détail d\'un téléphone')]
    public function getThisUser(Phone $phone): JsonResponse
    {
        try {
            if ($phone) {
                $seralizer = SerializerBuilder::create()->build();
                $context = SerializationContext::create()->setGroups('getPhones');
                $jsonPhonesList = $seralizer->serialize($phone, 'json', $context);

                return new JsonResponse(
                    $jsonPhonesList,
                    Response::HTTP_OK,
                    [],
                    true
                );
            }
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => "Une erreur lors de la récupération des données s'est produite."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
