<?php

namespace App\Entity;

use App\Repository\ParticulierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParticulierRepository::class)]
class Particulier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getUsers"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getUsers"])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getUsers"])]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(["getUsers"])]
    private ?\DateTimeInterface $birthday = null;


    #[ORM\Column(type: "string", length: 10)]
    #[Groups(["getUsers"])]
    #[Assert\Choice(callback: 'getGenderChoices')]
    protected string $gender;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getUsers"])]
    private ?string $job = null;

    #[ORM\ManyToOne(inversedBy: 'ClientsParticulier')]
    #[Groups(["getUsers"])]
    private ?Client $client = null;

    const GENDER_MALE = 'Masculin';
    const GENDER_FEMALE = 'Féminin';
    const GENDER_MX = 'Mx';

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): static
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
}
