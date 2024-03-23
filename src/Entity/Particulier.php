<?php

namespace App\Entity;

use App\Repository\ParticulierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;

#[ORM\Entity(repositoryClass: ParticulierRepository::class)]
/**
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *         "user_my-customer",
 *         parameters = {"id": "expr(object.getClient().getUser().getId())", "particulier_id": "expr(object.getId())"},
 *         absolute = false
 *     ),
 *     exclusion = @Hateoas\Exclusion(groups={"getCustomers", "updateParticulier"},
 *     excludeIf = "expr(not is_granted('ROLE_ADMIN'))")
 * )
 *
 * @Hateoas\Relation(
 *     "update",
 *     href = @Hateoas\Route(
 *         "update_user",
 *         parameters = {"id": "expr(object.getIdParent())"},
 *         absolute = false
 *     ),
 *     exclusion = @Hateoas\Exclusion(
 *         groups={"getCustomers", "updateParticulier"},
 *         excludeIf = "expr(null === object.getIdParent())",
 *         excludeIf = "expr(not is_granted('ROLE_ADMIN'))")
 * )
 * )
 * @Hateoas\Relation(
 *     "delete",
 *     href = @Hateoas\Route(
 *         "delete_user_my-customer",
 *         parameters = {"id": "expr(object.getClient().getUser().getId())", "particulier_id": "expr(object.getId())"},
 *         absolute = false
 *     ),
 *     exclusion = @Hateoas\Exclusion(
 *     groups={"getCustomers", "updateParticulier"},
 *     excludeIf = "expr(not is_granted('ROLE_ADMIN'))"
 *     )
 * )
 * 
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *         "user_my-customer",
 *         parameters = {"id": "expr(object.getClient().getUser().getId())", "particulier_id": "expr(object.getId())"},
 *         absolute = false
 *     ),
 *     exclusion = @Hateoas\Exclusion(
 *      groups={"createdUser"},
 *      excludeIf = "expr(not is_granted('ROLE_ADMIN'))")
 *      )
 * )
 * 
 * @Hateoas\Relation(
 *     "update",
 *     href = @Hateoas\Route(
 *         "update_user",
 *         parameters = {"id": "expr(object.getClient().getUser().getId())"},
 *         absolute = false
 *     ),
 *     exclusion = @Hateoas\Exclusion(groups={"createdUser"})
 * )
 * 
 * @Hateoas\Relation(
 *     "delete",
 *     href = @Hateoas\Route(
 *         "delete_user_my-customer",
 *         parameters = {"id": "expr(object.getClient().getUser().getId())", "particulier_id": "expr(object.getId())"},
 *         absolute = false
 *     ),
 *     exclusion = @Hateoas\Exclusion(
 *     groups={"createdUser"},
 *     excludeIf = "expr(not is_granted('ROLE_ADMIN'))"))
 * )
 */

class Particulier

{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getCustomers", "createdUser"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getUserProfil", "getCustomers", "createdUser", "updateParticulier"])]
    #[Assert\NotBlank(message: "Le prénom est obligatoire", groups: ['registration'])]
    #[Assert\Length(max: 255, minMessage: "Le prénom doit faire au moins {{ limit }} caractères", maxMessage: "Le titre ne peut pas faire plus de {{ limit }} caractères", groups: ['registration', 'updateProfile'])]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le prénom ne doit contenir que des lettres et des espaces", groups: ['registration', 'updateProfile'])]
    private ?string $firstName = '';

    #[ORM\Column(length: 255)]
    #[Groups(["getUserProfil", "getCustomers", "createdUser", "updateParticulier"])]
    #[Assert\NotBlank(message: "Le nom est obligatoire", groups: ['registration'])]
    #[Assert\Length(max: 255, minMessage: "Le nom doit faire au moins {{ limit }} caractères", maxMessage: "Le titre ne peut pas faire plus de {{ limit }} caractères", groups: ['registration', 'updateProfile'])]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le nom ne doit contenir que des lettres et des espaces", groups: ['registration', 'updateProfile'])]
    private ?string $lastName = '';

    #[ORM\Column(nullable: true)]
    #[Groups(["getUserProfil", "getCustomers", "createdUser", "updateParticulier"])]
    #[Assert\Date(message: "la date d'anniversaire doit être sous le format Y-m-d (par exemple '2024-10-18')", groups: ['registration', 'updateProfile'])]
    private ?string $birthday = null;


    #[ORM\Column(type: "string", length: 10)]
    #[Groups(["getUserProfil", "getCustomers", "createdUser", "updateParticulier"])]
    #[Assert\NotBlank(message: "Le genre est obligatoire", groups: ['registration'])]
    #[Assert\Choice(callback: 'getGenderChoices', groups: ['registration', 'updateProfile'])]
    protected string $gender;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getUserProfil", "getCustomers", "createdUser", "updateParticulier"])]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le job doit faire au moins {{ limit }} caractères", maxMessage: "Le titre ne peut pas faire plus de {{ limit }} caractères", groups: ['registration', 'updateProfile'])]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le job ne doit contenir que des lettres et des espaces", groups: ['registration', 'updateProfile'])]
    private ?string $job = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'ClientsParticulier')]
    private ?Client $client = null;

    #[ORM\OneToOne(mappedBy: 'particulier', targetEntity: User::class, cascade: ["remove"])]
    #[Groups(["getCustomers", "createdUser"])]
    private ?User $user = null;

    const GENDER_MALE = 'Masculin';
    const GENDER_FEMALE = 'Féminin';
    const GENDER_MX = 'Mx';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(?string $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
    }

    public static function getGenderChoices(): array
    {
        return [
            self::GENDER_MALE => self::GENDER_MALE,
            self::GENDER_FEMALE => self::GENDER_FEMALE,
            self::GENDER_MX => self::GENDER_MX,
        ];
    }


    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        if (!in_array($gender, array_keys(self::getGenderChoices()))) {
            throw new \InvalidArgumentException("Invalid gender");
        }
        $this->gender = $gender;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }
    public function getIdParent(): int|null
    {
        $user = $this->getUser();
        return $user ? $user->getId() : $this->getId();
    }
}
