<?php

namespace App\Entity;

use App\Repository\PeriodRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PeriodRepository::class)
 * @ORM\Table(name="periods")
 * @UniqueEntity(fields={"name"})
 */
class Period
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\NotEqualTo("Create")
     * @Assert\NotEqualTo("create")
     * @Assert\Length(
     *     min="1",
     *     max="100",
     * )
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     max="500"
     * )
     */
    private ?string $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Regex("/^-?[1-9][0-9]{,3}/")
     */
    private ?int $startYear;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Regex("/(^[1-2][0-9]{3})|(^[1-9][0-9]{2})/")
     * @Assert\PositiveOrZero
     */
    private ?int $endYear;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @Assert\Type(DateTimeImmutable::class)]
     * @Gedmo\Timestampable(on="create")
     */
    private ?DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @Assert\Type(DateTimeImmutable::class)]
     * @Gedmo\Timestampable(on="update")
     */
    private ?DateTimeImmutable $updatedAt;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="1",
     *     max="100",
     * )
     * @Gedmo\Slug(fields={"name"})
     */
    private ?string $slug;

    /**
     * @ORM\OneToMany(targetEntity=Composer::class, mappedBy="period")
     */
    private Collection $composers;

    public function __construct()
    {
        $this->composers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartYear(): ?int
    {
        return $this->startYear;
    }

    public function setStartYear(?int $startYear): self
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    public function setEndYear(?int $endYear): self
    {
        $this->endYear = $endYear;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Composer>
     */
    public function getComposers(): Collection
    {
        return $this->composers;
    }

    public function addComposer(Composer $composer): self
    {
        if (!$this->composers->contains($composer)) {
            $this->composers[] = $composer;
            $composer->setPeriod($this);
        }

        return $this;
    }

    public function removeComposer(Composer $composer): self
    {
        if ($this->composers->removeElement($composer)) {
            // set the owning side to null (unless already changed)
            if ($composer->getPeriod() === $this) {
                $composer->setPeriod(null);
            }
        }

        return $this;
    }
}
