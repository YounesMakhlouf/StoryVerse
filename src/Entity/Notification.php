<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $receiver;

    #[ORM\Column(length: 100)]
    private ?string $content;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $sender;

    public function __construct(User $receiver, string $content, User $sender)
    {
        $this->receiver = $receiver;
        $this->content = $content;
        $this->sender = $sender;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReceiver(): ?user
    {
        return $this->receiver;
    }

    public function setReceiver(?user $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
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

    public function getSender(): ?user
    {
        return $this->sender;
    }

    public function setSender(?user $sender): self
    {
        $this->sender = $sender;

        return $this;
    }
}
