<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ApiResource(
    itemOperations: [
        'get' => [
            'normalisation_context' => ['groups' => ['read:Company:collection','read:Company:item']]
        ]
        ],
    collectionOperations: [
        'get' => [
            'normalisation_context' => ['groups' => ['read:Company:collection']]
        ]
    ]
)]
#[ApiFilter(PropertyFilter::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"NONE")]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $country;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\ManyToMany(targetEntity: Game::class, inversedBy: 'involvedCompanies')]
    private $developed;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $slug;

    public function __construct()
    {
        $this->developed = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): ?self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?int
    {
        return $this->country;
    }

    public function setCountry(?int $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getDeveloped(): Collection
    {
        return $this->developed;
    }

    public function addDeveloped(Game $developed): self
    {
        if (!$this->developed->contains($developed)) {
            $this->developed[] = $developed;
        }

        return $this;
    }

    public function removeDeveloped(Game $developed): self
    {
        $this->developed->removeElement($developed);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
