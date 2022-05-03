<?php

namespace App\Entity;

use App\Repository\CanonRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CanonRepository::class)
 * @ORM\Table(name="canons")
 */
class Canon
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
    private ?string $description;

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
     * @ORM\ManyToMany(targetEntity=Piece::class, mappedBy="canons", fetch="EXTRA_LAZY")
     * @Assert\Valid
     * @ORM\JoinTable(name="pieces_canons")
     */
    private Collection $pieces;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="1",
     *     max="255",
     * )
     * @Gedmo\Slug(fields={"name"})
     */
    private ?string $slug;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type(type="boolean")
     */
    private ?bool $visibility;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="canons")
     * @Assert\Type(type="App\Entity\User")
     */
    private ?User $author;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
            $piece->addCanon($this);
        }

        return $this;
    }

    public function removePiece(Piece $piece): self
    {
        if ($this->pieces->removeElement($piece)) {
            $piece->removeCanon($this);
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

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
