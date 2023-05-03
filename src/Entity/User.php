<?php

namespace App\Entity;

use DateTimeInterface;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 25)]
    private ?string $first_name = null;

    #[ORM\Column(length: 25)]
    private ?string $last_name = null;

    #[ORM\Column(length: 25, unique: true)]
    private ?string $username = null;

    #[ORM\Column(length: 10)]
    private ?string $gender = null;

    #[ORM\Column]
    private ?bool $isVerified = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $Last_login_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Biography = null;

    #[ORM\ManyToMany(targetEntity: Competition::class, inversedBy: 'users')]
    private Collection $compete;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'following')]
    private Collection $follower;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'follower')]
    private Collection $following;

    public function __construct()
    {
        $this->compete = new ArrayCollection();
        $this->follower = new ArrayCollection();
        $this->following = new ArrayCollection();
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
        return (string)$this->email;
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

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getLastLoginDate(): ?DateTimeInterface
    {
        return $this->Last_login_date;
    }

    public function setLastLoginDate(DateTimeInterface $Last_login_date): self
    {
        $this->Last_login_date = $Last_login_date;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->Biography;
    }

    public function setBiography(?string $Biography): self
    {
        $this->Biography = $Biography;

        return $this;
    }
    public function getFullName(): ?string{
        return $this->getFirstName().' '.$this->getLastName();
    }

    /**
     * @return Collection<int, Competition>
     */
    public function getCompete(): Collection
    {
        return $this->compete;
    }

    public function addCompete(Competition $compete): self
    {
        if (!$this->compete->contains($compete)) {
            $this->compete->add($compete);
        }

        return $this;
    }

    public function removeCompete(Competition $compete): self
    {
        $this->compete->removeElement($compete);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFollower(): Collection
    {
        return $this->follower;
    }

    public function addFollower(self $follower): self
    {
        if (!$this->follower->contains($follower)) {
            $this->follower->add($follower);
        }

        return $this;
    }

    public function removeFollower(self $follower): self
    {
        $this->follower->removeElement($follower);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFollowing(): Collection
    {
        return $this->following;
    }

    public function addFollowing(self $following): self
    {
        if (!$this->following->contains($following)) {
            $this->following->add($following);
            $following->addFollower($this);
        }

        return $this;
    }

    public function removeFollowing(self $following): self
    {
        if ($this->following->removeElement($following)) {
            $following->removeFollower($this);
        }

        return $this;
    }
}
