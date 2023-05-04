<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity]
#[ORM\Table(name: "user_follow")]
class Follow
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private User $user;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "following_id", referencedColumnName: "id")]
    private User $following;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFollowing(): User
    {
        return $this->following;
    }

    public function setFollowing(User $following): self
    {
        $this->following = $following;

        return $this;
    }

}
