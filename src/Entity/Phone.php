<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @Hateoas\Relation(
 *    "self",
 *     href=@Hateoas\Route(
 *         "app_phone_id",
 *         parameters={"id": "expr(object.getId())"},
 *     ),
 *     exclusion = @Hateoas\Exclusion(groups="getPhones")
 * )
 */

#[ORM\Entity(repositoryClass: PhoneRepository::class)]
class Phone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getPhones"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank('le type est obligatoire')]
    #[Assert\Length(min: 1, max: 255, minMessage: 'Le type doit faire au moins {{limit}} caractères', maxMessage: "Le type ne peut pas faire plus de {{ limit }} caractères")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le type ne doit contenir que des lettres et des espaces")]
    #[Groups(["getPhones"])]
    private string $type;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La marque est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "La marque doit faire au moins {{ limit }} caractères", maxMessage: "La marque ne peut pas faire plus de {{ limit }} caractères")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "La marque ne doit contenir que des lettres et des espaces")]
    #[Groups(["getPhones"])]
    private string $brand;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le modèle est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le modèle doit faire au moins {{ limit }} caractères", maxMessage: "Le modèle ne peut pas faire plus de {{ limit }} caractères")]
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9\s]+$/", message: "Le modèle ne doit contenir que des lettres, des chiffres et des espaces")]
    #[Groups(["getPhones"])]
    private string $model;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le prix est obligatoire")]
    #[Assert\Range(min: 0, max: 999999, minMessage: "Le prix doit être supérieur ou égal à {{ limit }}", maxMessage: "Le prix ne peut pas être supérieur à {{ limit }}")]
    #[Groups(["getPhones"])]
    private int $price;



    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(min: 0, max: 60000, minMessage: "La description doit faire au moins {{ limit }} caractères", maxMessage: "La description ne peut pas faire plus de {{ limit }} caractères")]
    #[Groups(["getPhones"])]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\Range(min: 0, max: 999999, minMessage: "Le stock doit être supérieur ou égal à {{ limit }}", maxMessage: "Le stock ne peut pas être supérieur à {{ limit }}")]
    #[Groups(["getPhones"])]
    private ?int $stock = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }


    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }
}
