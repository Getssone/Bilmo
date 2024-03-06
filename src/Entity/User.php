<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getUsers"])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(["getUsers"])]
    #[Assert\NotBlank(message: "L'email est obligatoire")]
    #[Assert\Email(message: "L'email n'est pas valide")]
    private string $email;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(["getUsers"])]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire")]
    #[Assert\Length(min: 8, minMessage: "Le mot de passe doit faire au moins {{ limit }} caractères")]
    private string $password;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(["getUsers"])]
    #[Assert\NotBlank(message: "Les rôles sont obligatoires")]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getUsers"])]
    #[Assert\Length(max: 255, maxMessage: "L'adresse ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getUsers"])]
    #[Assert\NotBlank(message: "Le téléphone est obligatoire")]
    #[Assert\Length(min: 10, minMessage: "Le téléphone doit faire au moins {{ limit }} caractères")]
    #[Assert\Regex(pattern: "/^[0-9]+$/", message: "Le téléphone ne doit contenir que des chiffres")]
    private string $phone;


    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getUsers"])]
    #[Assert\Length(max: 255, maxMessage: "L'avatar ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $avatar = null;

    #[ORM\Column]
    #[Groups(["getUsers"])]
    #[Assert\Type(type: "bool", message: "La valeur de 'isVerified' doit être un booléen")]
    private ?bool $isVerified = null;



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
}
