<?php

namespace App\Entity;

use App\Repository\ScaleRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ScaleRepository::class)
 * @ORM\Table(name="scales")
 * @UniqueEntity(fields={"name"})
 */
class Scale
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
     * @ORM\OneToMany(targetEntity=Piece::class, mappedBy="scale")
     */
    private Collection $pieces;

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
            $piece->setScale($this);
        }

        return $this;
    }

    public function removePiece(Piece $piece): self
    {
        if ($this->pieces->removeElement($piece)) {
            // set the owning side to null (unless already changed)
            if ($piece->getScale() === $this) {
                $piece->setScale(null);
            }
        }

        return $this;
    }

}
