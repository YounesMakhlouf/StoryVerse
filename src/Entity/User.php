<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use DateTimeInterface;
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
     * @var string|null The hashed password
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

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $bio;

    #[ORM\Column (nullable: true)]
    private ?string $avatar;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'following', fetch: 'EXTRA_LAZY')]
    private Collection $follower;
    #[ORM\Column]
    private ?string $csrf_token = '';

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'follower', fetch: 'EXTRA_LAZY')]
    private Collection $following;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Contribution::class)]
    private Collection $contributions;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $dateOfBirth = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $country = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;
    #[ORM\OneToMany(mappedBy: 'receiver', targetEntity: Notification::class)]
    private Collection $notifications;

    #[ORM\ManyToMany(targetEntity: Story::class, mappedBy: 'likes', fetch: 'EXTRA_LAZY')]
    private Collection $likedStories;

    #[ORM\ManyToMany(targetEntity: Quest::class, inversedBy: 'users')]
    private Collection $completedQuests;

    #[ORM\Column]
    private int $xp = 0;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Tier $tier = null;
    private ?string $fullName;

    public function __construct()
    {
        $this->follower = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->contributions = new ArrayCollection();
        $this->likedStories = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->completedQuests = new ArrayCollection();
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->password = null;
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

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;
        $this->updateFullName();

        return $this;
    }

    private function updateFullName(): void
    {
        $this->fullName = $this->first_name . ' ' . $this->last_name;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;
        $this->updateFullName();

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFollower(): Collection
    {
        return $this->follower;
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

    public function addFollower(self $follower): self
    {
        if (!$this->follower->contains($follower)) {
            $this->follower->add($follower);
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

    public function removeFollower(self $follower): self
    {
        $this->follower->removeElement($follower);

        return $this;
    }

    public function __toString(): string
    {
        return $this->username ?? '';
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setReceiver($this);
        }
        return $this;
    }

    public function addContribution(Contribution $contribution): self
    {
        if (!$this->contributions->contains($contribution)) {
            $this->contributions->add($contribution);
            $contribution->setAuthor($this);
        }
        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getReceiver() === $this) {
                $notification->setReceiver(null);
            }
        }
        return $this;
    }

    public function removeContribution(Contribution $contribution): self
    {
        if ($this->contributions->removeElement($contribution)) {
            // set the owning side to null (unless already changed)
            if ($contribution->getAuthor() === $this) {
                $contribution->setAuthor(null);

            }
        }
        return $this;
    }

    public function getCsrfToken(): ?string
    {
        return $this->csrf_token;
    }

    public function getDateOfBirth(): ?DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?DateTimeImmutable $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Story>
     */
    public function getLikedStories(): Collection
    {
        return $this->likedStories;
    }

    public function addLikedStory(Story $likedStory): self
    {
        if (!$this->likedStories->contains($likedStory)) {
            $this->likedStories->add($likedStory);
            $likedStory->addLike($this);
        }

        return $this;
    }

    public function removeLikedStory(Story $likedStory): self
    {
        if ($this->likedStories->removeElement($likedStory)) {
            $likedStory->removeLike($this);
        }

        return $this;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }
        return $this;
    }

    public function getTier(): ?Tier
    {
        return $this->tier;
    }

    public function setTier(?Tier $tier): self
    {
        $this->tier = $tier;

        return $this;
    }

    public function getXp(): ?int
    {
        return $this->xp;
    }

    public function setXp(int $xp): self
    {
        $this->xp = $xp;

        return $this;
    }

    public function addXp(int $xp): self
    {
        $this->xp += $xp;

        return $this;
    }

    /**
     * @return Collection<int, Quest>
     * @return Collection<int, Quest>
     */
    public function getCompletedQuests(): Collection
    {
        return $this->completedQuests;
    }

    public function addCompletedQuest(Quest $completedQuest): self
    {
        if (!$this->completedQuests->contains($completedQuest)) {
            $this->completedQuests->add($completedQuest);
        }

        return $this;
    }

    public function removeCompletedQuest(Quest $completedQuest): self
    {
        $this->completedQuests->removeElement($completedQuest);

        return $this;
    }

    public function getUserRequirement(string $questRequirement): int
    {
        return match ($questRequirement) {
            'post_comments' => $this->getCommentsCount(),
            'receive_comments' => $this->getCommentsReceivedCount(),
            'post_likes' => $this->getLikedStoriesCount(),
            'receive_likes' => $this->getLikesReceivedCount(),
            'post_stories' => $this->getStartedStoriesCount(),
            'post_contributions' => $this->getContributionsCount(),
            'complete_quests' => $this->getCompletedQuestsCount(),
            default => -1,
        };
    }

    public function getCommentsCount(): int
    {
        return count($this->comments);
    }

    public function getCommentsReceivedCount(): int
    {
        $contributedStories = $this->getContributedStories();
        $commentCount = 0;

        foreach ($contributedStories as $story) {
            $commentCount += $story->getCommentsCount();
        }

        return $commentCount;
    }

    public function getContributedStories(): array
    {
        $contributions = $this->getContributions();
        $contributedStories = [];

        foreach ($contributions as $contribution) {
            $story = $contribution->getStory();
            $contributedStories[] = $story;
        }
        return $contributedStories;
    }

    /**
     * @return Collection<int, Contribution>
     */
    public function getContributions(): Collection
    {
        return $this->contributions;
    }

    public function getLikedStoriesCount(): int
    {
        return count($this->likedStories);
    }

    public function getLikesReceivedCount(): int
    {
        $contributedStories = $this->getContributedStories();
        $likesCount = 0;

        foreach ($contributedStories as $story) {
            $likesCount += $story->getTotalLikes();
        }

        return $likesCount;
    }

    public function getStartedStoriesCount(): int
    {
        return count($this->getStartedStories());
    }

    public function getStartedStories(): array
    {
        $contributions = $this->getContributions();
        $startedStories = [];

        foreach ($contributions as $contribution) {
            if ($contribution->getPosition() === 1) {
                $story = $contribution->getStory();
                $startedStories[] = $story;
            }
        }

        return $startedStories;
    }

    public function getContributionsCount(): int
    {
        return count($this->contributions);
    }

    public function getCompletedQuestsCount(): int
    {
        return count($this->completedQuests);
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
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
}