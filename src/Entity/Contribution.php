<?php

namespace App\Entity;

use App\Repository\ContributionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ContributionRepository::class)]
class Contribution
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private int $position = 1;

    #[ORM\Column]
    private bool $reported = false;

    #[ORM\Column]
    private int $likes = 0;

    #[ORM\ManyToOne(inversedBy: 'contribution')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Story $story = null;

<<<<<<< HEAD
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Contribution::class)]
    private Collection $children;

    #[ORM\OneToMany(mappedBy: 'contribution', targetEntity: User::class)]
    private Collection $Author;

    

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->Author = new ArrayCollection();
    }
=======
    #[ORM\ManyToOne(inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $author = null;
>>>>>>> 188b3df1c66f44e041cc91efd6cefd943c7f4f0d

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function isReported(): ?bool
    {
        return $this->reported;
    }

    public function setReported(?bool $reported): self
    {
        $this->reported = $reported;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(?int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getStory(): ?Story
    {
        return $this->story;
    }

    public function setStory(?Story $story): self
    {
        $this->story = $story;

        return $this;
    }

    public function getAuthor(): ?user
    {
        return $this->author;
    }

    public function setAuthor(?user $author): self
    {
        $this->author = $author;

        return $this;
    }
<<<<<<< HEAD

    public function removechildren(Contribution $children): self
    {
        if ($this->children->removeElement($children)) {
            // set the owning side to null (unless already changed)
            if ($children->getParent() === $this) {
                $children->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAuthor(): Collection
    {
        return $this->Author;
    }

    public function addAuthor(User $author): self
    {
        if (!$this->Author->contains($author)) {
            $this->Author->add($author);
            $author->setContribution($this);
        }

        return $this;
    }

    public function removeAuthor(User $author): self
    {
        if ($this->Author->removeElement($author)) {
            // set the owning side to null (unless already changed)
            if ($author->getContribution() === $this) {
                $author->setContribution(null);
            }
        }

        return $this;
    }
}
    
=======
}
>>>>>>> 188b3df1c66f44e041cc91efd6cefd943c7f4f0d
