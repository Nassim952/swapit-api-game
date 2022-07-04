<?php

namespace App\Entity;

use App\Filter\Gamefilter;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GameRepository;
use App\Controller\PatchCoverController;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;

use ApiPlatform\Core\Annotation\ApiResource;

use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource(
    itemOperations: [
        'get' => [
            'normalisation_context' => ['groups' => ['read:Game:collection','read:Game:item']]
        ],
        'generate_cover' => [
            'method' => 'PATCH',
            'path' => '/games/{id}/generate-cover',
            'controller' => PatchCoverController::class,
            'openapi_context' => [
                'summary' => 'Generate cover for a game',
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => []
                        ]
                    ]
                ]
            ]
        ],
    ],
    collectionOperations: [
        'get' => [
            'normalisation_context' => ['groups' => ['read:Game:collection']]
        ]
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'name' => 'partial','status' => 'exact'])]
#[ApiFilter(DateFilter::class, properties: ['first_release_date'])]
#[ApiFilter(GameFilter::class)]
// #[ApiFilter(OrderFilter::class, properties: ['aggregated_rating_count' => 'ASC','rating','rating_count','total_rating','total_rating_count','popularity','release_dates'])]
#[ApiFilter(PropertyFilter::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"NONE")]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:Game:collection'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:Game:collection'])]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $status;

    #[ORM\Column(type: 'text', nullable: true)]
    private $storyline;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['read:Game:item'])]
    private $summary;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Groups(['read:Game:item'])]
    private $cover = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['read:Game:item'])]
    private $version_title;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(['read:Game:item'])]
    private $aggregated_rating;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['read:Game:item'])]
    private $aggregated_rating_count;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['read:Game:item'])]
    private $follows;
    
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['read:Game:collection'])]
    private $first_release_date;

    #[ORM\ManyToMany(targetEntity: Genre::class, mappedBy: 'games')]
    #[Groups(['read:Game:item'])]
    #[ApiSubresource]
    private $genres;

    #[ORM\ManyToMany(targetEntity: Company::class, mappedBy: 'developed')]
    #[Groups(['read:Game:item'])]
    #[ApiSubresource]
    private $involvedCompanies;

    #[ORM\ManyToMany(targetEntity: Mode::class, mappedBy: 'games')]
    #[Groups(['read:Game:item'])]
    #[ApiSubresource]
    private $modes;

    #[ORM\ManyToMany(targetEntity: Platform::class, mappedBy: 'games')]
    #[Groups(['read:Game:item'])]
    #[ApiSubresource]
    private $platforms;




    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->involvedCompanies = new ArrayCollection();
        $this->modes = new ArrayCollection();
        $this->platforms = new ArrayCollection();
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStoryline(): ?string
    {
        return $this->storyline;
    }

    public function setStoryline(?string $storyline): self
    {
        $this->storyline = $storyline;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getVersionTitle(): ?string
    {
        return $this->version_title;
    }

    public function setVersionTitle(?string $version_title): self
    {
        $this->version_title = $version_title;

        return $this;
    }

    public function getAggregatedRating(): ?float
    {
        return $this->aggregated_rating;
    }

    public function setAggregatedRating(?float $aggregated_rating): self
    {
        $this->aggregated_rating = $aggregated_rating;

        return $this;
    }

    public function getAggregatedRatingCount(): ?int
    {
        return $this->aggregated_rating_count;
    }

    public function setAggregatedRatingCount(?int $aggregated_rating_count): self
    {
        $this->aggregated_rating_count = $aggregated_rating_count;

        return $this;
    }

    public function getFollows(): ?int
    {
        return $this->follows;
    }

    public function setFollows(?int $follows): self
    {
        $this->follows = $follows;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
            $genre->addGame($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->removeElement($genre)) {
            $genre->removeGame($this);
        }

        return $this;
    }

    /**
     * @return Collection|Company[]
     */
    public function getinvolvedCompanies(): Collection
    {
        return $this->involvedCompanies;
    }

    public function addInvolvedCompany(Company $company): self
    {
        if (!$this->involvedCompanies->contains($company)) {
            $this->involvedCompanies[] = $company;
            $company->addDeveloped($this);
        }

        return $this;
    }

    public function removeInvolvedCompany(Company $company): self
    {
        if ($this->involvedCompanies->removeElement($company)) {
            $company->removeDeveloped($this);
        }

        return $this;
    }

    /**
     * @return Collection|Mode[]
     */
    public function getModes(): Collection
    {
        return $this->modes;
    }

    public function addMode(Mode $mode): self
    {
        if (!$this->modes->contains($mode)) {
            $this->modes[] = $mode;
            $mode->addGame($this);
        }

        return $this;
    }

    public function removeMode(Mode $mode): self
    {
        if ($this->modes->removeElement($mode)) {
            $mode->removeGame($this);
        }

        return $this;
    }

    /**
     * @return Collection|Platform[]
     */
    public function getPlatforms(): Collection
    {
        return $this->platforms;
    }

    public function addPlatform(Platform $platform): self
    {
        if (!$this->platforms->contains($platform)) {
            $this->platforms[] = $platform;
            $platform->addGame($this);
        }

        return $this;
    }

    public function removePlatform(Platform $platform): self
    {
        if ($this->platforms->removeElement($platform)) {
            $platform->removeGame($this);
        }

        return $this;
    }

    public function getFirstReleaseDate(): ?int
    {
        return $this->first_release_date;
    }

    public function setFirstReleaseDate(?int $first_release_date): self
    {
        $this->first_release_date = $first_release_date;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }
}
