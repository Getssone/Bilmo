<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityNotFoundException;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security as SecutiyOA;
use Symfony\Component\Routing\Annotation\Route as RouteOA;
use OpenApi\Annotations as OA;

class PhoneController extends AbstractController
{
    /**
     * Cette méthode permet de récupérer l'ensemble des téléphones.
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des telephones",
     *       @OA\JsonContent(
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/MainPhone_getAllPhone")
     *     )
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="La page que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     *
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="Le nombre d'éléments que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     * @OA\Tag(name="Phones")
     *
     * @param PhoneRepository $phones
     * @param Request $request
     * @param TagAwareCacheInterface $cache
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */

    #[Route('/api/phones', name: 'app_all_phones', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour voir la liste des téléphones')]
    public function getAllPhone(PhoneRepository $phones, Request $request, TagAwareCacheInterface $cache, SerializerInterface $serializer): JsonResponse
    {

        try {
            $page = $request->get('page', 1);
            $limit = $request->get('limit', 3);
            $idCache = 'getAllPhone-' . $page . "-" . $limit;

            $jsonPhonesList = $cache->get($idCache, function (ItemInterface $item) use ($phones, $page, $limit, $serializer) {
                $context = SerializationContext::create()->setGroups(['getPhones']);
                echo ('nous enregistrons cette requête dans le cache');
                $item->tag('allPhonesCache');
                $phonesList = $phones->findAllWidthPagination($page, $limit);
                return $serializer->serialize($phonesList, 'json', $context);
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
    /**
     * Cette méthode permet de récupérer l'ensemble des téléphones.
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des telephones",
     *      @OA\JsonContent(ref="#/components/schemas/MainPhone_getAllPhone")
     * )
     * 
     * @OA\Tag(name="Phones")
     *
     * @param PhoneRepository $phones
     * @param Request $request
     * @param TagAwareCacheInterface $cache
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/phones/{id}', name: 'app_phone_id', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour voir les détail d\'un téléphone')]
    public function getThisUser(Phone $phone, TagAwareCacheInterface $cache, SerializerInterface $serializer): JsonResponse
    {
        try {
            if ($phone) {

                $idCache = 'getThisPhone-' . $phone->getId();

                $jsonPhone = $cache->get($idCache, function (ItemInterface $item) use ($phone, $serializer) {
                    $context = SerializationContext::create()->setGroups(['getPhones']);
                    echo ('nous enregistrons cette requête dans le cache');
                    $item->tag('IdPhoneCache');
                    return $serializer->serialize($phone, 'json', $context);
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
