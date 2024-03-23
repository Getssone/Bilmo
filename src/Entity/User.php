<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Type;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(
    fields: 'email',
    errorPath: 'email',
    message: 'un e-mail identique est déjà enregistrer en Base de Donnée'
)]


class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getUserProfil", "getClient", "createdUser", "getCustomers", "updateClient", "updateParticulier"])]
    private ?int $id = null;

    #[ORM\Column(name: 'email', type: 'string', length: 255, unique: true)]
    #[Groups(["getUserProfil", "getClient", "createdUser", "getCustomers", "updateClient", "updateParticulier"])]
    #[Assert\NotBlank(message: "L'email est obligatoire", groups: ['registration'])]
    #[Assert\Email(message: "L'email n'est pas valide", groups: ['registration', 'updateProfile'])]
    private string $email;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(["getUserProfil"])]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire", groups: ['registration'])]
    #[Assert\Length(min: 8, minMessage: "Le mot de passe doit faire au moins {{ limit }} caractères", groups: ['registration', 'updateProfile'])]
    private string $password;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(["getUserProfil"])]
    #[Assert\NotBlank(message: "Les rôles sont obligatoires", groups: ['registration'])]
    #[Type("array<string>")]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getUserProfil", "getClient", "createdUser", "getCustomers", "updateClient", "updateParticulier"])]
    #[Assert\Length(max: 255, maxMessage: "L'adresse ne peut pas faire plus de {{ limit }} caractères", groups: ['registration', 'updateProfile'])]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getUserProfil", "getClient", "createdUser", "getCustomers", "updateClient", "updateParticulier"])]
    #[Assert\NotBlank(message: "Le téléphone est obligatoire", groups: ['registration'])]
    #[Assert\Length(min: 10, minMessage: "Le téléphone doit faire au moins {{ limit }} caractères", groups: ['registration', 'updateProfile'])]
    #[Assert\Regex(pattern: "/^[0-9]+$/", message: "Le téléphone ne doit contenir que des chiffres", groups: ['registration', 'updateProfile'])]
    private string $phone;


    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getUserProfil", "getClient", "createdUser", "getCustomers", "updateClient", "updateParticulier"])]
    #[Assert\Length(max: 255, maxMessage: "L'avatar ne peut pas faire plus de {{ limit }} caractères", groups: ['registration', 'updateProfile'])]
    private ?string $avatar = null;

    #[ORM\Column]
    #[Groups(["getClient"])]
    #[Assert\Type(type: "bool", message: "La valeur de 'isVerified' doit être un booléen", groups: ['registration', 'updateProfile'])]
    private ?bool $isVerified = null;

    #[ORM\OneToOne(targetEntity: Client::class, cascade: ["persist", "remove"])]
    #[Groups(["getUserProfil", "getClient", "createdUser", "updateClient"])]
    #[Assert\Valid(groups: ['registration', 'updateProfile'])]
    private ?Client $client = null;

    #[ORM\OneToOne(targetEntity: Particulier::class, cascade: ["persist", "remove"])]
    #[Groups(["getUserProfil", "getCustomers", "createdUser", "updateParticulier"])]
    #[Assert\Valid(groups: ['registration', 'updateProfile'])]
    private ?Particulier $particulier = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * Méthode getUsersname qui permet de retourner le champ qui est utilisé pour l'authentification.
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }


    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        //Prend les rôles actuellement assignés à l'utilisateur depuis $this->roles.
        $roles = $this->roles;

        //Ajoute ROLE_USER au tableau $roles, garantissant ainsi que ce rôle est toujours inclus. ⚠ Attention a la syntaxe Ajoute ≠ Remplace
        $roles[] = 'ROLE_USER';
        //Utilise array_unique pour éliminer les doublons, au cas où ROLE_USER était déjà dans les rôles assignés, assurant que chaque rôle ne figure qu'une seule fois dans le tableau retourné.
        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    public function getParticulier(): ?Particulier
    {
        return $this->particulier;
    }

    public function setParticulier(?Particulier $particulier): self
    {
        $this->particulier = $particulier;
        return $this;
    }

    public function createUser(array $userData, UserPasswordHasherInterface  $userPasswordHasher)
    {
        $this->setEmail($userData['email']);
        $this->setPassword($userData['password']);
        $this->setPassword($userPasswordHasher->hashPassword($this, $userData['password']));
        $this->setAddress($userData['address']);
        $this->setPhone($userData['phone']);
        $this->setAvatar($userData['avatar']);
        $this->setIsVerified($userData['isVerified']);
        $this->setRoles($userData['roles']);

        if (in_array('ROLE_ADMIN', $userData['roles'])) {
            // Logique pour créer/assigner un Client
            $client = new Client();
            $client->setName($userData['name']);
            $siret = $userData['siret'];
            $client->setSiret($siret);
            $client->setBusiness($userData['business']);
            $client->setWebSite($userData['webSite']);
            $client->setLegalStatus($userData['legalStatus']);
            $this->setClient($client);
            return $this;
        } elseif (in_array('ROLE_USER', $userData['roles'])) {
            // Logique pour créer/assigner un Particulier
            $particulier = new Particulier();
            $particulier->setGender($userData['gender']);
            $particulier->setFirstName($userData['firstName']);
            $particulier->setLastName($userData['lastName']);
            $particulier->setBirthday($userData['birthday']);
            $particulier->setJob($userData['job']);
            $this->setParticulier($particulier);
            return $this;
        }
    }


    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    public function getIdParent(): int|null
    {
        return $this->getId();
    }
}
