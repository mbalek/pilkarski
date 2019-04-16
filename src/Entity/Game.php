<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $gameDateTime;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $result;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Round", inversedBy="games")
     */
    private $round;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Events", mappedBy="game")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="game")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GameTeam", mappedBy="game")
     */
    private $gameTeams;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->gameTeams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameDateTime(): ?\DateTimeInterface
    {
        return $this->gameDateTime;
    }

    public function setGameDateTime(?\DateTimeInterface $gameDateTime): self
    {
        $this->gameDateTime = $gameDateTime;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getRound(): ?Round
    {
        return $this->round;
    }

    public function setRound(?Round $round): self
    {
        $this->round = $round;

        return $this;
    }

    /**
     * @return Collection|Events[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Events $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setGame($this);
        }

        return $this;
    }

    public function removeEvent(Events $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getGame() === $this) {
                $event->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setGame($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getGame() === $this) {
                $comment->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GameTeam[]
     */
    public function getGameTeams(): Collection
    {
        return $this->gameTeams;
    }

    public function addGameTeam(GameTeam $gameTeam): self
    {
        if (!$this->gameTeams->contains($gameTeam)) {
            $this->gameTeams[] = $gameTeam;
            $gameTeam->setGame($this);
        }

        return $this;
    }

    public function removeGameTeam(GameTeam $gameTeam): self
    {
        if ($this->gameTeams->contains($gameTeam)) {
            $this->gameTeams->removeElement($gameTeam);
            // set the owning side to null (unless already changed)
            if ($gameTeam->getGame() === $this) {
                $gameTeam->setGame(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->result;
    }
}
