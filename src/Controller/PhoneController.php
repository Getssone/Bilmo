<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityNotFoundException;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class PhoneController extends AbstractController
{

    #[Route('/api/phones', name: 'app_all_phones', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour voir la liste des téléphones')]
    public function getAllUser(PhoneRepository $phones, Request $request, TagAwareCacheInterface $cache): JsonResponse
    {

        try {
            $page = $request->get('page', 1);
            $limit = $request->get('limit', 3);
            $idCache = 'getAllPhone-' . $page . "-" . $limit;

            $jsonPhonesList = $cache->get($idCache, function (ItemInterface $item) use ($phones, $page, $limit) {
                $seralizer = SerializerBuilder::create()->build();
                $context = SerializationContext::create()->setGroups('getPhones');
                echo ('nous enregistrons cette requête dans le cache');
                $item->tag('allPhonesCache');
                $phonesList = $phones->findAllWidthPagination($page, $limit);
                return $seralizer->serialize($phonesList, 'json', $context);
            });


            return new JsonResponse(
                $jsonPhonesList,
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
    public function getThisUser(Phone $phone, TagAwareCacheInterface $cache): JsonResponse
    {
        try {
            if ($phone) {

                $idCache = 'getThisPhone-' . $phone->getId();

                $jsonPhone = $cache->get($idCache, function (ItemInterface $item) use ($phone) {
                    $seralizer = SerializerBuilder::create()->build();
                    $context = SerializationContext::create()->setGroups('getPhones');
                    echo ('nous enregistrons cette requête dans le cache');
                    $item->tag('IdPhoneCache');
                    return  $seralizer->serialize($phone, 'json', $context);
                });


                return new JsonResponse(
                    $jsonPhone,
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
