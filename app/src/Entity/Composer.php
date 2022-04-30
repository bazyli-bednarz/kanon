<?php

namespace App\Entity;

use App\Repository\ComposerRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ComposerRepository::class)
 * @ORM\Table(name="composers")
 * @UniqueEntity(fields={"name", "lastName"})
 */
class Composer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\NotEqualTo("Create")
     * @Assert\NotEqualTo("create")
     * @Assert\Length(
     *     min="1",
     *     max="255",
     * )
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     max="255"
     * )
     */
    private ?string $lastName;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     max="500"
     * )
     */
    private ?string $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Regex("/(^[1-2][0-9]{3})|(^[1-9][0-9]{2})/")
     * @Assert\PositiveOrZero
     */
    private ?int $birthYear;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Regex("/(^[1-2][0-9]{3})|(^[1-9][0-9]{2})/")
     * @Assert\PositiveOrZero
     */
    private ?int $deathYear;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\Type(DateTimeImmutable::class)]
     * @Gedmo\Timestampable(on="create")
     */
    private ?DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\Type(DateTimeImmutable::class)]
     * @Gedmo\Timestampable(on="update")
     */
    private ?DateTimeImmutable $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Piece::class, mappedBy="composer")
     */
    private Collection $pieces;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="1",
     *     max="255",
     * )
     * @Gedmo\Slug(fields={"name", "lastName"})
     */
    private ?string $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Period::class, inversedBy="composers")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Type(type="App\Entity\Period")
     * @Assert\NotNull
     */
    private ?Period $period;

    public function __construct()
    {
        $this->pieces = new ArrayCollection();
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getBirthYear(): ?int
    {
        return $this->birthYear;
    }

    public function setBirthYear(?int $birthYear): self
    {
        $this->birthYear = $birthYear;

        return $this;
    }

    public function getDeathYear(): ?int
    {
        return $this->deathYear;
    }

    public function setDeathYear(?int $deathYear): self
    {
        $this->deathYear = $deathYear;

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

    /**
     * @return Collection<int, Piece>
     */
    public function getPieces(): Collection
    {
        return $this->pieces;
    }

    public function addPiece(Piece $piece): self
    {
        if (!$this->pieces->contains($piece)) {
            $this->pieces[] = $piece;
            $piece->setComposer($this);
        }

        return $this;
    }

    public function removePiece(Piece $piece): self
    {
        if ($this->pieces->removeElement($piece)) {
            // set the owning side to null (unless already changed)
            if ($piece->getComposer() === $this) {
                $piece->setComposer(null);
            }
        }

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

    public function getPeriod(): ?Period
    {
        return $this->period;
    }

    public function setPeriod(?Period $period): self
    {
        $this->period = $period;

        return $this;
    }
}
