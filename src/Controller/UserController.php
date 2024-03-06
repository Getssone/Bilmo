<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Security\Http\Attribute\IsGranted;
// use Symfony\Component\Serializer\SerializerInterface as SerializerSerializerInterface;


class UserController extends AbstractController
{


    #[Route('/api/test', name: 'app_test', methods: ['GET'])]
    public function test(UserRepository $users): JsonResponse
    {
        $seralizer = SerializerBuilder::create()->build();
        $usersList = $users->findAll();
        $context = SerializationContext::create()->setGroups('getUsers');
        $jsonUsersList = $seralizer->serialize($usersList, 'json', $context);

        return new JsonResponse(
            $jsonUsersList,
            Response::HTTP_OK,
            [],
            true
        );
    }


    #[Route('/api/users', name: 'app_all_user', methods: ['GET'])]
    // #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour créer un livre')]
    public function getAllUser(UserRepository $users): JsonResponse
    {
        $seralizer = SerializerBuilder::create()->build();
        $usersList = $users->findAll();
        $context = SerializationContext::create()->setGroups('getUsers');
        $jsonUsersList = $seralizer->serialize($usersList, 'json', $context);

        return new JsonResponse(
            $jsonUsersList,
            Response::HTTP_OK,
            [],
            true
        );
    }
    #[Route('/api/users/{id}', name: 'app_user_id', methods: ['GET'])]
    public function getThisUser(User $user): JsonResponse
    {
        if ($user) {
            $seralizer = SerializerBuilder::create()->build();
            $context = SerializationContext::create()->setGroups('getUsers');
            $jsonUsersList = $seralizer->serialize($user, 'json', $context);

            return new JsonResponse(
                $jsonUsersList,
                Response::HTTP_OK,
                [],
                true
            );
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
