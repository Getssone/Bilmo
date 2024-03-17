<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Particulier;
use App\Entity\User;
use App\Repository\ParticulierRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Requirement\Requirement;
use \Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/api/users', name: 'all_user', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour voir les users')]
    public function getAllUser(UserRepository $users): JsonResponse
    {

        try {
            $serializer = SerializerBuilder::create()->build();
            $context = SerializationContext::create()->setGroups(['getCustomers', 'getClient']);
            $usersList = $users->findAll();
            $jsonUsersList = $serializer->serialize($usersList, 'json', $context);
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

    #[Route('/api/users', name: 'created_user', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour crée un utilisateur')]
    public function createUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, ValidatorInterface $validator, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        try {
            $context = SerializationContext::create()->setGroups(['getCustomers', 'getClient']);
            $newUser = $request->getContent();
            $userDataForVérificationUser = $serializer->deserialize($newUser, User::class, 'json');
            $userDataForVérificationParticulier = $serializer->deserialize($newUser, Particulier::class, 'json');
            $userDataForVérificationClient = $serializer->deserialize($newUser, Client::class, 'json');

            $errorsUser = $validator->validate($userDataForVérificationUser, null,  ['registration']);
            if ($errorsUser->count() > 0) {
                return new JsonResponse($serializer->serialize($errorsUser, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
            }
            if ($userDataForVérificationUser->getRoles()[0] === "ROLE_USER") {
                $errorsParticulier = $validator->validate($userDataForVérificationParticulier, null,  ['registration']);
                if ($errorsParticulier->count() > 0) {
                    return new JsonResponse($serializer->serialize($errorsParticulier, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
                }
            } elseif ($userDataForVérificationUser->getRoles()[0] === "ROLE_ADMIN") {
                $errorsClient = $validator->validate($userDataForVérificationClient, null,  ['registration']);
                if ($errorsClient->count() > 0) {
                    return new JsonResponse($serializer->serialize($errorsClient, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
                }
            }


            $userData = json_decode($newUser, true);
            $user = new User();
            $createdUser = $user->createUser($userData, $userPasswordHasher);
            if ($createdUser->getRoles()[0] === "ROLE_USER") {
                try {
                    $currentAdminUser = $this->security->getUser();
                    if ($currentAdminUser instanceof User) {
                        $currentAdminUser->getClient()->addClientsParticulier($createdUser->getParticulier());
                    }
                } catch (\Exception $e) {
                    return new JsonResponse(['error' => "n'as pas pu trouver qui vous êtes pour crée un utilisateur"], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
            $em->persist($createdUser);
            $em->flush($createdUser);

            $createdUserJson = $serializer->serialize($createdUser, 'json', $context);

            $location = $urlGenerator->generate('user_id', ['id' => $createdUser->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
            return new JsonResponse(
                $createdUserJson,
                Response::HTTP_CREATED,
                ['location' => $location],
                true
            );
        } catch (\Exception $e) {
            return new JsonResponse(['shortError' => "Une erreur lors de l'enregistrement des données une erreur s'est produite.", 'longError' =>  '' . $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/users/{id}', name: 'user_id', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour voir le détail d\'un utilisateur')]
    public function getThisUser(User $user): JsonResponse
    {
        try {
            $currentUser = $this->security->getUser();
            if ($currentUser === $user) {
                $serializer = SerializerBuilder::create()->build();
                $context = SerializationContext::create()->setGroups(["getClient", "getArrayClient", 'getCustomers']);
                $jsonUser = $serializer->serialize($user, 'json', $context);

                return new JsonResponse(
                    $jsonUser,
                    Response::HTTP_OK,
                    [],
                    true
                );
            } else {
                return new JsonResponse(
                    ['error' => "Vous n'avez pas le droit d'accéder à cet utilisateur."],
                    Response::HTTP_FORBIDDEN,
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse(['error' => "Aucun utilisateur n'a été trouver avec cette Id."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/api/users/{id}', name: 'update_user', methods: ['PUT'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour mettre à jour un utilisateur')]
    public function updateUser(
        Request $request,
        User $currentUser,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator,
    ): JsonResponse {

        try {
            $currentAdminUser = $this->security->getUser();
            if ($currentAdminUser instanceof User) {
                $client = $currentAdminUser->getClient();
                if (!$client) {
                    return new JsonResponse(["error" => "L'utilisateur n'a pas de compte client associé."], Response::HTTP_BAD_REQUEST);
                }


                // Récupération de la liste des particuliers du client
                $particuliersList = $client->getClientsParticulier();

                if ($particuliersList->contains($currentUser->getParticulier())) {

                    $editUserRequest = $request->getContent();

                    $editUserDataForVérificationUser = $serializer->deserialize($editUserRequest, User::class, 'json');
                    $editUserDataForVérificationParticulier = $serializer->deserialize($editUserRequest, Particulier::class, 'json');
                    $editUserDataForVérificationClient = $serializer->deserialize($editUserRequest, Client::class, 'json');

                    $errorsUser = $validator->validate($editUserDataForVérificationUser, null,  ['updateProfile']);
                    if ($errorsUser->count() > 0) {
                        return new JsonResponse($serializer->serialize($errorsUser, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
                    }
                    if ($currentUser->getRoles()[0] === "ROLE_USER") {
                        $errorsParticulier = $validator->validate($editUserDataForVérificationParticulier, null,  ['updateProfile']);
                        if ($errorsParticulier->count() > 0) {
                            return new JsonResponse($serializer->serialize($errorsParticulier, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
                        }
                    } elseif ($currentUser->getRoles()[0] === "ROLE_ADMIN") {
                        $errorsClient = $validator->validate($editUserDataForVérificationClient, null,  ['updateProfile']);
                        if ($errorsClient->count() > 0) {
                            return new JsonResponse($serializer->serialize($errorsClient, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
                        }
                    }

                    $updateInfoUser = $request->toArray(); // ou  json_decode($request->getContent(), true);
                    foreach ($updateInfoUser as $key => $value) {
                        $setter = 'set' . ucfirst($key);
                        if (method_exists(User::class, $setter)) {
                            $currentUser->$setter($value);
                        } elseif (in_array('ROLE_ADMIN', $currentUser->getRoles())) {
                            dd($setter);
                            if (method_exists(Client::class, $setter)) {
                                $currentUser->getClient()->$setter($value);
                            }
                        } else {
                            if (method_exists(Particulier::class, $setter)) {
                                $currentUser->getParticulier()->$setter($value);
                            }
                        }
                    }

                    $em->persist($currentUser);
                    $em->flush();

                    $context = SerializationContext::create()->setGroups(['updateClient', 'updateParticulier']);


                    $updatedUserJson = $serializer->serialize($currentUser, 'json', $context);

                    $location = $urlGenerator->generate('user_id', ['id' => $currentUser->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
                    return new JsonResponse(
                        $updatedUserJson,
                        Response::HTTP_OK,
                        ['location' => $location],
                        true
                    );
                }
                return new JsonResponse(["error" => "Cette utilisateur ne fait pas partie de vos clients Particulier."], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new JsonResponse(['error' => "Une erreur lors de la récupération des données s'est produite."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/users/{id}/delete', name: 'delete_user', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour supprimer un utilisateur')]
    public function deleteUser(User $user, EntityManagerInterface $em): JsonResponse
    {
        try {
            $currentUser = $this->security->getUser();
            if ($currentUser === $user) {
                $em->remove($user);
                $em->flush();

                return new JsonResponse(
                    null,
                    Response::HTTP_NO_CONTENT
                );
            }
            return new JsonResponse(["error" => "Aucun utilisateur n'à était trouver."], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => "Une erreur lors de la récupération des données s'est produite."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/users/{id}/Profil', name: 'user_id_profil', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour mettre à jour cette utilisateur')]
    public function getThisUserProfil(User $user): JsonResponse
    {
        try {
            $currentUser = $this->security->getUser();
            if ($currentUser === $user) {
                $serializer = SerializerBuilder::create()->build();
                $context = SerializationContext::create()->setGroups(["getUserProfil", 'getClient', 'getCustomers']);
                $jsonUsersList = $serializer->serialize($user, 'json', $context);

                return new JsonResponse(
                    $jsonUsersList,
                    Response::HTTP_OK,
                    [],
                    true
                );
            }
            return new JsonResponse(["error" => "Aucun utilisateur n'à était trouver."], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => "Une erreur lors de la récupération des données s'est produite."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/users/{id}/my-customers', name: 'user_my-customers', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour voir les client de cette utilisateur')]
    public function getMyCustomers(User $user): JsonResponse
    {
        try {
            $currentUser = $this->security->getUser();
            if ($currentUser === $user) {
                $client = $user->getClient();
                if (!$client) {
                    return new JsonResponse(["error" => "L'utilisateur n'a pas de compte client associé."], Response::HTTP_BAD_REQUEST);
                }

                $serializer = SerializerBuilder::create()->build();
                $context = SerializationContext::create()->setGroups(['getCustomers']);

                $particuliersList = $client->getClientsParticulier();
                $jsonParticuliersList = $serializer->serialize($particuliersList, 'json', $context);

                return new JsonResponse(
                    $jsonParticuliersList,
                    Response::HTTP_OK,
                    [],
                    true
                );
            }
            return new JsonResponse(["error" => "Aucun utilisateur n'à était trouver."], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => "Une erreur lors de la récupération des données s'est produite."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    #[Route('/api/users/{id}/my-customers/{particulier_id}', name: 'user_my-customer', methods: ['GET'], requirements: ['id' => Requirement::DIGITS, 'particulier_id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour voir les detail de ce client')]
    public function getThisCustomer(
        User $user,
        #[MapEntity(id: 'particulier_id')]
        Particulier $particulier
    ): JsonResponse {
        try {
            $currentUser = $this->security->getUser();
            if ($currentUser === $user) {
                $client = $user->getClient();
                if (!$client) {
                    return new JsonResponse(["error" => "L'utilisateur n'a pas de compte client associé."], Response::HTTP_BAD_REQUEST);
                }


                // Récupération de la liste des particuliers du client
                $particuliersList = $client->getClientsParticulier();

                if ($particuliersList->contains($particulier)) {

                    $serializer = SerializerBuilder::create()->build();
                    $context = SerializationContext::create()->setGroups(['getCustomers']);
                    $jsonParticulier = $serializer->serialize($particulier, 'json', $context);

                    return new JsonResponse($jsonParticulier, Response::HTTP_OK, [], true);
                }
            }
            return new JsonResponse(["error" => "cette utilisateur n'est pas dans la liste de vos client Particulier."], Response::HTTP_FORBIDDEN);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => "Une erreur lors de la récupération des données s'est produite."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/api/users/{id}/my-customers/{particulier_id}/delete', name: 'delete_user_my-customer', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS, 'particulier_id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour supprimer le client de cette utilisateur')]
    public function deleteThisCustomer(
        User $user,
        #[MapEntity(id: 'particulier_id')]
        Particulier $particulier,
        EntityManagerInterface $em
    ): JsonResponse {

        try {
            $currentUser = $this->security->getUser();
            if ($currentUser === $user) {
                $client = $user->getClient();
                if (!$client) {
                    return new JsonResponse(["error" => "cette utilisateur n'est pas dans la liste client."], Response::HTTP_BAD_REQUEST);
                }

                // Récupération de la liste des particuliers du client
                $particuliersList = $client->getClientsParticulier();

                if ($particuliersList->contains($particulier)) {

                    $em->remove($particulier);
                    $em->flush();

                    return new JsonResponse(
                        null,
                        Response::HTTP_NO_CONTENT
                    );
                }
            }
            return new JsonResponse(["error" => "cette utilisateur n'est pas dans la liste de vos client Particulier."], Response::HTTP_FORBIDDEN);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => "Une erreur lors de la récupération des données s'est produite."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
