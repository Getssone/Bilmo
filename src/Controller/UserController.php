<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Particulier;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security as SecutiyOA;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route as RouteOA;

class UserController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Cette méthode permet de récupérer l'ensemble des Users pour des tests.
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des users",
     *      @OA\JsonContent(ref="#/components/schemas/MainUser_getClient")
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
     * @OA\Tag(name="Users")
     *
     * @param UserRepository $users
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param TagAwareCacheInterface $cache,
     * @return JsonResponse
     */
    #[Route('/api/users', name: 'all_user', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour voir les users')]
    public function getAllUser(UserRepository $users, Request $request, TagAwareCacheInterface $cache, SerializerInterface $serializer): JsonResponse
    {

        try {
            $page = $request->get('page', 1);
            $limit = $request->get('limit', 3);
            $idCache = 'getAllUsers-' . $page . "-" . $limit;

            $jsonUsersList = $cache->get($idCache, function (ItemInterface $item) use ($users, $page, $limit, $serializer) {
                $context = SerializationContext::create()->setGroups(['getCustomers', 'getClient', 'getUserCustomerID']);
                echo ('nous enregistrons cette requête dans le cache');
                $item->tag('allUsersCache');
                $usersList = $users->findAllWidthPagination($page, $limit);

                return $serializer->serialize($usersList, 'json', $context);
            });



            return new JsonResponse(
                $jsonUsersList,
                Response::HTTP_OK,
                [],
                true
            );
        } catch (\Exception $e) {
            return new JsonResponse(['shortError' => "Une erreur lors de la récupération des données s'est produite.", 'longError' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Cette méthode permet de crée un User "SOIT Client SOIT Particulier".
     * @OA\Response(
     *     response=200,
     *     description="Retourne un user Client",
     *     @OA\JsonContent(ref="#/components/schemas/MainUser_createClient")
     * )
     * @OA\RequestBody(
     *         description="Données de l'utilisateur à créer ⚠ Un Utilisateur et soit Client Soit Particulier",
     *         required=true,
     *         @OA\JsonContent(
     *            oneOf = {
     *                 @OA\Schema(ref="#/components/schemas/MainUser_createUser"),
     *             }
     *         )
     *     ),
     * @OA\Tag(name="Users")
     *
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param UrlGeneratorInterface $urlGenerator
     * @param TagAwareCacheInterface $cache
     * @return JsonResponse
     */
    #[Route('/api/users', name: 'created_user', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour crée un utilisateur')]
    public function createUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, ValidatorInterface $validator, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, TagAwareCacheInterface $cache): JsonResponse
    {
        try {
            $context = SerializationContext::create()->setGroups(['createdUser']);
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
                    return new JsonResponse(['shortError' => "Une erreur lors de la récupération des données s'est produite.", 'longError' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
            $em->persist($createdUser);
            $em->flush($createdUser);
            $cache->invalidateTags(['allUsersCache', 'userAllParticulierCache', 'IdUserCache', 'IdUserParticulierCache']);

            $createdUserJson = $serializer->serialize($createdUser, 'json', $context);

            $location = $urlGenerator->generate('user_my-customer', ['id' => $createdUser->getParticulier()->getClient()->getUser()->getId(), 'particulier_id' => $createdUser->getParticulier()->getID()], UrlGeneratorInterface::ABSOLUTE_URL);
            return new JsonResponse(
                $createdUserJson,
                Response::HTTP_CREATED,
                ['location' => $location],
                true
            );
        } catch (\Exception $e) {
            return new JsonResponse(['shortError' => "Une erreur lors de la récupération des données s'est produite.", 'longError' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Cette méthode permet de récupérer l'ensemble des informations du Client.
     * @OA\Response(
     *     response=200,
     *     description="Retourne les informations sur le Client",* 
     *     @OA\JsonContent(ref="#/components/schemas/MainUser_getClient")
     *     
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @param User $users
     * @param TagAwareCacheInterface $cache,
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/users/{id}', name: 'user_id', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour voir le détail d\'un utilisateur')]
    public function getThisUser(User $user, TagAwareCacheInterface $cache, SerializerInterface $serializer): JsonResponse
    {
        try {
            $currentUser = $this->security->getUser();
            if ($currentUser === $user) {
                $idCache = 'getThisUser-' . $user->getId();

                $jsonUser = $cache->get($idCache, function (ItemInterface $item) use ($user, $serializer) {
                    $context = SerializationContext::create()->setGroups(["getClient", "getArrayClient", 'getCustomers']);
                    echo ('nous enregistrons cette requête dans le cache');
                    $item->tag('IdUserCache');

                    return $serializer->serialize($user, 'json', $context);
                });

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

    /**
     * Cette méthode permet de mettre à jour un User "SOIT Client SOIT Particulier".
     * @OA\Response(
     *     response=200,
     *     description="Retourne un user Client",
     *     @OA\JsonContent(ref="#/components/schemas/MainUser_updateUser")
     * )
     * @OA\RequestBody(
     *         description="Données de l'utilisateur à modifié ⚠ Un Utilisateur et soit Client Soit Particulier",
     *         @OA\JsonContent(
     *            oneOf = {
     *                 @OA\Schema(ref="#/components/schemas/MainUser_updateUser"),
     *             }
     *         )
     *     ),
     * @OA\Tag(name="Users")
     *
     * @param Request $request
     * @param User $currentUser
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param UrlGeneratorInterface $urlGenerator
     * @param TagAwareCacheInterface $cache
     * @return JsonResponse
     */
    #[Route('/api/users/{id}', name: 'update_user', methods: ['PUT'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour mettre à jour un utilisateur')]
    public function updateUser(
        Request $request,
        User $currentUser,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator,
        TagAwareCacheInterface $cache
    ): JsonResponse {

        try {
            $currentAdminUser = $this->security->getUser();
            if ($currentAdminUser instanceof User) {


                if ($currentAdminUser->getId() === $currentUser->getId()) {

                    $editUserRequest = $request->getContent();

                    $editUserDataForVérificationUser = $serializer->deserialize($editUserRequest, User::class, 'json');
                    $editUserDataForVérificationClient = $serializer->deserialize($editUserRequest, Client::class, 'json');

                    $errorsUser = $validator->validate($editUserDataForVérificationUser, null,  ['updateProfile']);
                    if ($errorsUser->count() > 0) {
                        return new JsonResponse($serializer->serialize($errorsUser, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
                    }
                    if ($currentUser->getRoles()[0] === "ROLE_ADMIN") {
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
                            if (method_exists(Client::class, $setter)) {
                                $currentUser->getClient()->$setter($value);
                            }
                        }
                    }
                    $cache->invalidateTags(['allUsersCache', 'userAllParticulierCache', 'IdUserCache', 'IdUserParticulierCache']);
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
                            if (method_exists(Client::class, $setter)) {
                                $currentUser->getClient()->$setter($value);
                            }
                        } else {
                            if (method_exists(Particulier::class, $setter)) {
                                $currentUser->getParticulier()->$setter($value);
                            }
                        }
                    }
                    $cache->invalidateTags(['allUsersCache', 'userAllParticulierCache', 'IdUserCache', 'IdUserParticulierCache']);
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
            return new JsonResponse(['shortError' => "Une erreur lors de la récupération des données s'est produite.", 'longError' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Cette méthode permet de supprimer l'ensemble des informations du Client.
     * @OA\Response(
     *     response=204,
     *     description="supprime le client sans retourner d'object",
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @param User $users
     * @param EntityManagerInterface $em,
     * @param TagAwareCacheInterface $cache,
     * @return JsonResponse
     */
    #[Route('/api/users/{id}', name: 'delete_user', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour supprimer un utilisateur')]
    public function deleteUser(User $user, EntityManagerInterface $em, TagAwareCacheInterface $cache): JsonResponse
    {
        try {
            $currentUser = $this->security->getUser();
            if ($currentUser === $user) {
                $cache->invalidateTags(['allUsersCache', 'userAllParticulierCache', 'IdUserCache', 'IdUserParticulierCache']);
                $em->remove($user);
                $em->flush();

                return new JsonResponse(
                    null,
                    Response::HTTP_NO_CONTENT
                );
            }
            dd('je suis');
            return new JsonResponse(["error" => "Aucun utilisateur n'à était trouver."], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['shortError' => "Une erreur lors de la récupération des données s'est produite.", 'longError' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Cette méthode permet de voir le Profil et la totalité des informations du compte Client.
     * @OA\Response(
     *     response=200,
     *     description="Retourne le profil du Client",
     *     @OA\JsonContent(ref="#/components/schemas/MainUser_getUserProfil")
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @param User $users
     * @param EntityManagerInterface $em,
     * @param TagAwareCacheInterface $cache,
     * @return JsonResponse
     */
    #[Route('/api/users/{id}/Profil', name: 'user_id_profil', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour mettre à jour cette utilisateur')]
    public function getThisUserProfil(User $user, UrlGeneratorInterface $urlGenerator, TagAwareCacheInterface $cache, SerializerInterface $serializer): JsonResponse
    {
        try {
            $currentUser = $this->security->getUser();
            if ($currentUser === $user) {
                $idCache = 'getThisUserProfil-' . $user->getId();

                $jsonUserProfil = $cache->get($idCache, function (ItemInterface $item) use ($user, $serializer) {
                    $context = SerializationContext::create()->setGroups(["getUserProfil", 'getClient']);
                    echo ('nous enregistrons cette requête dans le cache');
                    $item->tag('IdUserProfilCache');

                    return $serializer->serialize($user, 'json', $context);
                });

                $location = $urlGenerator->generate('user_id', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
                return new JsonResponse(
                    $jsonUserProfil,
                    Response::HTTP_OK,
                    ['location' => $location],
                    true
                );
            }
            return new JsonResponse(["error" => "Aucun utilisateur n'à était trouver."], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['shortError' => "Une erreur lors de la récupération des données s'est produite.", 'longError' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Cette méthode permet de voir la totalité des ClientParticulier attacher a son propre compte Client.
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des ClientParticulier",
     *     @OA\JsonContent(ref="#/components/schemas/MainUser_getInfoCustomerArray")
     * )
     *
     * @OA\Tag(name="Users_Customers")
     *
     * @param User $users
     * @param UrlGeneratorInterface $urlGenerator
     * @param TagAwareCacheInterface $cache
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/users/{id}/my-customers', name: 'user_my-customers', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour voir les client de cette utilisateur')]
    public function getMyCustomers(User $user, UrlGeneratorInterface $urlGenerator, TagAwareCacheInterface $cache, SerializerInterface $serializer): JsonResponse
    {
        try {
            $currentUser = $this->security->getUser();
            if ($currentUser === $user) {
                $idCache = 'getThisUserAllParticulier-' . $user->getId();

                $jsonAllParticulier = $cache->get($idCache, function (ItemInterface $item) use ($user, $urlGenerator, $serializer) {
                    $context = SerializationContext::create()->setGroups(['getCustomers']);
                    echo ('nous enregistrons cette requête dans le cache');
                    $item->tag('userAllParticulierCache');

                    $client = $user->getClient();

                    if (!$client) {
                        return new JsonResponse(["error" => "L'utilisateur n'a pas de compte client associé."], Response::HTTP_BAD_REQUEST);
                    }

                    $particuliersList = $client->getClientsParticulier();
                    return   $serializer->serialize($particuliersList, 'json', $context);
                });

                $response = new JsonResponse(
                    $jsonAllParticulier,
                    Response::HTTP_OK,
                    [],
                    true
                );
                $client = $user->getClient();
                $particuliersList = $client->getClientsParticulier();
                $locations = [];
                // Créer une chaîne de caractères pour les URL de localisation
                foreach ($particuliersList as $particulier) {
                    $locations[] = $urlGenerator->generate('user_id', ['id' => $particulier->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
                }
                $response->headers->set('Location', $locations);

                return $response;
            }
            return new JsonResponse(["error" => "Aucun utilisateur n'à était trouver."], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['shortError' => "Une erreur lors de la récupération des données s'est produite.", 'longError' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Cette méthode permet de voir les information d'un ClientParticulier attacher a son propre compte Client.
     * @OA\Response(
     *     response=200,
     *     description="Retourne les informations du ClientParticulier",
     *     @OA\JsonContent(ref="#/components/schemas/MainUser_getCustomers")
     * )
     *
     * @OA\Tag(name="Users_Customers")
     *
     * @param  User $user
     * @param  Particulier $particulier
     * @param  UrlGeneratorInterface $urlGenerator
     * @param  TagAwareCacheInterface $cache
     * @param  SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/users/{id}/my-customers/{particulier_id}', name: 'user_my-customer', methods: ['GET'], requirements: ['id' => Requirement::DIGITS, 'particulier_id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour voir les detail de ce client')]
    public function getThisCustomer(
        User $user,
        #[MapEntity(id: 'particulier_id')]
        Particulier $particulier,
        UrlGeneratorInterface $urlGenerator,
        TagAwareCacheInterface $cache,
        SerializerInterface $serializer
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
                try {
                    if ($particuliersList->contains($particulier)) {
                        $idCache = 'getThisUserParticulier-' . $particulier->getId();

                        $jsonParticulier = $cache->get($idCache, function (ItemInterface $item) use ($particulier, $serializer) {
                            $context = SerializationContext::create()->setGroups(['getCustomers']);
                            echo ('nous enregistrons cette requête dans le cache');
                            $item->tag('IdUserParticulierCache');

                            return $serializer->serialize($particulier, 'json', $context);
                        });
                        $location = $urlGenerator->generate('delete_user_my-customer', ['id' => $user->getId(), 'particulier_id' => $particulier->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
                        return new JsonResponse($jsonParticulier, Response::HTTP_OK, ["location" => $location], true);
                    }
                } catch (\Exception $e) {
                    return new JsonResponse(['shortError' => "Une erreur lors de la récupération des données s'est produite.", 'longError' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
            return new JsonResponse(["error" => "cette utilisateur n'est pas dans la liste de vos client Particulier."], Response::HTTP_FORBIDDEN);
        } catch (\Exception $e) {
            return new JsonResponse(['shortError' => "Une erreur lors de la récupération des données s'est produite.", 'longError' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Cette méthode permet de supprimer les information d'un ClientParticulier attacher a son propre compte Client.
     * @OA\Response(
     *     response=204,
     *     description="supprime le client sans retourner d'object",
     * )
     * )
     *
     * @OA\Tag(name="Users_Customers")
     *
     * @param  User $user
     * @param  Particulier $particulier
     * @param  UrlGeneratorInterface $urlGenerator
     * @param  TagAwareCacheInterface $cache
     * @param  SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/users/{id}/my-customers/{particulier_id}', name: 'delete_user_my-customer', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS, 'particulier_id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour supprimer le client de cette utilisateur')]
    public function deleteThisCustomer(
        User $user,
        #[MapEntity(id: 'particulier_id')]
        Particulier $particulier,
        EntityManagerInterface $em,
        TagAwareCacheInterface $cache
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
                    $cache->invalidateTags(['allUsersCache', 'userAllParticulierCache', 'IdUserCache', 'IdUserParticulierCache']);
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
            return new JsonResponse(['shortError' => "Une erreur lors de la récupération des données s'est produite.", 'longError' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
