<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];#[ORM\OneToMany(mappedBy: 'relation', targetEntity: Rate::class)]
    private Collection $rates;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Like::class)]
    private Collection $likes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Planning::class)]
    private Collection $plannings;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    public function __construct()
    {
        $this->rates = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->plannings = new ArrayCollection();
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getRates(): Collection|ArrayCollection
    {
        return $this->rates;
    }

    /**
     * @param ArrayCollection|Collection $rates
     */
    public function setRates(Collection|ArrayCollection $rates): void
    {
        $this->rates = $rates;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getArticles(): Collection|ArrayCollection
    {
        return $this->articles;
    }

    /**
     * @param ArrayCollection|Collection $articles
     */
    public function setArticles(Collection|ArrayCollection $articles): void
    {
        $this->articles = $articles;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getComments(): Collection|ArrayCollection
    {
        return $this->comments;
    }

    /**
     * @param ArrayCollection|Collection $comments
     */
    public function setComments(Collection|ArrayCollection $comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getLikes(): Collection|ArrayCollection
    {
        return $this->likes;
    }

    /**
     * @param ArrayCollection|Collection $likes
     */
    public function setLikes(Collection|ArrayCollection $likes): void
    {
        $this->likes = $likes;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getPlannings(): Collection|ArrayCollection
    {
        return $this->plannings;
    }

    /**
     * @param ArrayCollection|Collection $plannings
     */
    public function setPlannings(Collection|ArrayCollection $plannings): void
    {
        $this->plannings = $plannings;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
}
