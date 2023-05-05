<?php

namespace App\Entity;

use App\Repository\StoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: StoryRepository::class)]
class Story
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 25)]
    private string $language = 'english';

    #[ORM\Column]
    private int $likes = 0;

    #[ORM\Column(length: 25)]
    private string $status = 'pending';

    #[ORM\Column(length: 255, unique: true)]
    #[Slug(fields: ['title'])]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'story', targetEntity: Contribution::class)]
    private Collection $contribution;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $genre = null;

    public function __construct()
    {
        $this->contribution = new ArrayCollection();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getImageUrl(int $width=300): string
    {
        return sprintf(
            'https://picsum.photos/id/%d/%d',
            ($this->getId() + 50) % 1000, // number between 0 and 1000, based on the id
            $width
        );
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection<int, contribution>
     */
    public function getContribution(): Collection
    {
        return $this->contribution;
    }

    public function addContribution(contribution $contribution): self
    {
        if (!$this->contribution->contains($contribution)) {
            $this->contribution->add($contribution);
            $contribution->setStory($this);
        }

        return $this;
    }

    public function removeContribution(contribution $contribution): self
    {
        if ($this->contribution->removeElement($contribution)) {
            // set the owning side to null (unless already changed)
            if ($contribution->getStory() === $this) {
                $contribution->setStory(null);
            }
        }

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }
}
