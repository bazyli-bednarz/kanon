<?php

namespace App\Entity;

use App\Repository\PieceRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PieceRepository::class)
 * @ORM\Table(name="pieces")
 */
class Piece
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
    private ?int $year;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     max="255"
     * )
     */
    private ?string $link;

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
     * @ORM\ManyToOne(targetEntity=Composer::class, inversedBy="pieces")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Type(type="App\Entity\Composer")
     * @Assert\NotNull
     */
    private ?Composer $composer;

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
     * @ORM\ManyToOne(targetEntity=Scale::class, inversedBy="pieces")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Type(type="App\Entity\Scale")
     * @Assert\NotNull
     */
    private ?Scale $scale;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, fetch="EXTRA_LAZY", orphanRemoval=true)
     * @Assert\Valid
     * @ORM\JoinTable(name="pieces_tags")
     *
     */
    private Collection $tags;

    /**
     * @ORM\ManyToMany(targetEntity=Canon::class, inversedBy="pieces", fetch="EXTRA_LAZY")
     * @Assert\Valid
     * @ORM\JoinTable(name="pieces_canons")
     */
    private Collection $canons;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="pieces", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Type(type="App\Entity\User")
     * @Assert\NotNull
     */
    private ?User $author;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="editedPieces")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Type(type="App\Entity\User")
     * @Assert\NotNull
     */
    private ?User $editedBy;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->canons = new ArrayCollection();
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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

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

    public function getComposer(): ?Composer
    {
        return $this->composer;
    }

    public function setComposer(?Composer $composer): self
    {
        $this->composer = $composer;

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

    public function getScale(): ?Scale
    {
        return $this->scale;
    }

    public function setScale(?Scale $scale): self
    {
        $this->scale = $scale;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection<int, Canon>
     */
    public function getCanons(): Collection
    {
        return $this->canons;
    }

    public function addCanon(Canon $canon): self
    {
        if (!$this->canons->contains($canon)) {
            $this->canons[] = $canon;
        }

        return $this;
    }

    public function removeCanon(Canon $canon): self
    {
        $this->canons->removeElement($canon);

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

    public function getEditedBy(): ?User
    {
        return $this->editedBy;
    }

    public function setEditedBy(?User $editedBy): self
    {
        $this->editedBy = $editedBy;

        return $this;
    }
}
