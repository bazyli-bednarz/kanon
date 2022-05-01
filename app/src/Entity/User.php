<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="email_idx",
 *              columns={"email"},
 *          )
 *     }
 * )
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="50",
     * )
     * @Assert\Type(type="string")
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="3",
     *     max="50",
     * )
     * @Gedmo\Slug(fields={"name"})
     */
    private ?string $slug;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @Assert\Type(DateTimeImmutable::class)]
     * @Gedmo\Timestampable(on="create")
     */
    private ?DateTimeImmutable $createdAt;


    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private string $password;

    /**
     * @ORM\OneToMany(targetEntity=Piece::class, mappedBy="author")
     */
    private $pieces;

    /**
     * @ORM\OneToMany(targetEntity=Piece::class, mappedBy="editedBy")
     */
    private $editedPieces;

    /**
     * @ORM\OneToMany(targetEntity=Canon::class, mappedBy="author")
     */
    private $canons;

    /**
     * @ORM\OneToMany(targetEntity=Composer::class, mappedBy="author")
     */
    private $composers;

    /**
     * @ORM\OneToMany(targetEntity=Composer::class, mappedBy="editedBy")
     */
    private $editedComposers;

    public function __construct()
    {
        $this->pieces = new ArrayCollection();
        $this->editedPieces = new ArrayCollection();
        $this->canons = new ArrayCollection();
        $this->composers = new ArrayCollection();
        $this->editedComposers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = static::ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Getter for password.
     *
     * @return string|null Password
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $piece->setAuthor($this);
        }

        return $this;
    }

    public function removePiece(Piece $piece): self
    {
        if ($this->pieces->removeElement($piece)) {
            // set the owning side to null (unless already changed)
            if ($piece->getAuthor() === $this) {
                $piece->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Piece>
     */
    public function getEditedPieces(): Collection
    {
        return $this->editedPieces;
    }

    public function addEditedPiece(Piece $editedPiece): self
    {
        if (!$this->editedPieces->contains($editedPiece)) {
            $this->editedPieces[] = $editedPiece;
            $editedPiece->setEditedBy($this);
        }

        return $this;
    }

    public function removeEditedPiece(Piece $editedPiece): self
    {
        if ($this->editedPieces->removeElement($editedPiece)) {
            // set the owning side to null (unless already changed)
            if ($editedPiece->getEditedBy() === $this) {
                $editedPiece->setEditedBy(null);
            }
        }

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
            $canon->setAuthor($this);
        }

        return $this;
    }

    public function removeCanon(Canon $canon): self
    {
        if ($this->canons->removeElement($canon)) {
            // set the owning side to null (unless already changed)
            if ($canon->getAuthor() === $this) {
                $canon->setAuthor(null);
            }
        }

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
            $composer->setAuthor($this);
        }

        return $this;
    }

    public function removeComposer(Composer $composer): self
    {
        if ($this->composers->removeElement($composer)) {
            // set the owning side to null (unless already changed)
            if ($composer->getAuthor() === $this) {
                $composer->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Composer>
     */
    public function getEditedComposers(): Collection
    {
        return $this->editedComposers;
    }

    public function addEditedComposer(Composer $editedComposer): self
    {
        if (!$this->editedComposers->contains($editedComposer)) {
            $this->editedComposers[] = $editedComposer;
            $editedComposer->setEditedBy($this);
        }

        return $this;
    }

    public function removeEditedComposer(Composer $editedComposer): self
    {
        if ($this->editedComposers->removeElement($editedComposer)) {
            // set the owning side to null (unless already changed)
            if ($editedComposer->getEditedBy() === $this) {
                $editedComposer->setEditedBy(null);
            }
        }

        return $this;
    }
}
