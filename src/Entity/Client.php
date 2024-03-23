<?php

namespace App\Entity;

use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;

#[ORM\Entity(repositoryClass: ClientsRepository::class)]
/**
 * @Hateoas\Relation(
 *     name="self",
 *     href=@Hateoas\Route(
 *         "user_id",
 *         parameters={"id": "expr(object.getUser().getId())"},
 *         absolute=false
 *     ),
 *     exclusion = @Hateoas\Exclusion(groups={"getCustomers", "getArrayClient", "getClient"}, excludeIf = "expr(not is_granted('ROLE_ADMIN'))")
 * )
 * @Hateoas\Relation(
 *     name="update",
 *     href=@Hateoas\Route(
 *         "update_user",
 *         parameters={"id": "expr(object.getIdParent())"},
 *         absolute=false
 *     ),
 *     exclusion = @Hateoas\Exclusion(groups={"getCustomers", "getArrayClient", "getClient"}, 
 *     excludeIf = "expr(not is_granted('ROLE_ADMIN'))")
 * )
 * @Hateoas\Relation(
 *     name="delete",
 *     href=@Hateoas\Route(
 *         "delete_user",
 *         parameters={"id": "expr(object.getUser().getId())"},
 *         absolute=false
 *     ),
 *     exclusion = @Hateoas\Exclusion(groups={"getCustomers", "getArrayClient", "getClient"},
 *     excludeIf = "expr(not is_granted('ROLE_ADMIN'))")
 * )
 */
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getClient"])]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'client', targetEntity: User::class)]
    private ?User $user = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le nom est obligatoire", groups: ['registration'])]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le nom doit faire au moins {{ limit }} caractères", maxMessage: "Le nom ne peut pas faire plus de {{ limit }} caractères")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le nom ne doit contenir que des lettres et des espaces")]
    #[Groups(["getUserProfil", "getClient", "createUserClient_bodyOA", "updateClient", "updateClient_bodyOA"])]
    private string $name;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\Length(min: 0, max: 14, minMessage: "Le SIRET doit faire au moins {{ limit }} caractères", maxMessage: "Le SIRET ne peut pas faire plus de {{ limit }} caractères")]
    #[Groups(["getUserProfil", "getClient", "createUserClient_bodyOA", "updateClient", "updateClient_bodyOA"])]
    private ?string $siret = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "L'activité est obligatoire", groups: ['registration'])]
    #[Assert\Length(min: 1, max: 255, minMessage: "L'activité doit faire au moins {{ limit }} caractères", maxMessage: "L'activité ne peut pas faire plus de {{ limit }} caractères")]
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9\s]+$/", message: "L'activité ne doit contenir que des lettres, des chiffres et des espaces")]
    #[Groups(["getUserProfil", "getClient", "createUserClient_bodyOA", "updateClient", "updateClient_bodyOA"])]
    private string $business;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 0, max: 255,  minMessage: "L'URL du site web doit faire au moins {{ limit }} caractères", maxMessage: "L'URL du site web ne peut pas faire plus de {{ limit }} caractères")]
    #[Assert\Url(message: "L'URL du site web n'est pas valide")]
    #[Groups(["getUserProfil", "getClient", "createUserClient_bodyOA", "updateClient", "updateClient_bodyOA"])]
    private ?string $webSite = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 0, max: 255, minMessage: "Le statut juridique doit faire au moins {{ limit }} caractères", maxMessage: "Le statut juridique ne peut pas faire plus de {{ limit }} caractères")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le statut juridique ne doit contenir que des lettres et des espaces")]
    #[Groups(["getUserProfil", "getClient", "createUserClient_bodyOA", "updateClient", "updateClient_bodyOA"])]
    private ?string $legalStatus = null;

    #[ORM\OneToMany(targetEntity: Particulier::class, mappedBy: 'client')]
    #[Groups(["getArrayClient"])]
    private Collection $clientsParticulier;

    public function __construct()
    {
        $this->clientsParticulier = new ArrayCollection();
    }

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getBusiness(): ?string
    {
        return $this->business;
    }

    public function setBusiness(string $business): static
    {
        $this->business = $business;

        return $this;
    }

    public function getWebSite(): ?string
    {
        return $this->webSite;
    }

    public function setWebSite(?string $webSite): static
    {
        $this->webSite = $webSite;

        return $this;
    }

    public function getLegalStatus(): ?string
    {
        return $this->legalStatus;
    }

    public function setLegalStatus(?string $legalStatus): static
    {
        $this->legalStatus = $legalStatus;

        return $this;
    }

    /**
     * @return Collection<int, Particulier>
     */
    public function getClientsParticulier(): Collection
    {
        return $this->clientsParticulier;
    }

    public function addClientsParticulier(Particulier $client): static
    {
        if (!$this->clientsParticulier->contains($client)) {
            $this->clientsParticulier->add($client);
            $client->setClient($this);
        }

        return $this;
    }

    public function removeClients(Particulier $client): static
    {
        if ($this->clientsParticulier->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getClient() === $this) {
                $client->setClient(null);
            }
        }

        return $this;
    }
    public function getIdParent(): int|null
    {
        $user = $this->getUser();
        return $user ? $user->getId() : $this->getId();
    }
}
