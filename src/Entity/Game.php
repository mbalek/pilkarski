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
     * @ORM\OneToOne(targetEntity="App\Entity\GameTeam", cascade={"persist"})
     * @ORM\JoinColumn(name="home_team_id", referencedColumnName="id")
     */
    private $homeTeam;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\GameTeam", cascade={"persist"})
     * @ORM\JoinColumn(name="away_team_id", referencedColumnName="id")
     */
    private $awayTeam;


    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /*
     * Stages of game:
     *-1 - Not a live game
     * 0 - Break
     * 1 - First Half
     * 2 - Second Half
     * 3 - ET First Half
     * 4 - ET Second Half
     * 5 - Penalties
     */
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $currentStage;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $currentStageStart;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $currentStageAdditionalTime;

    /**
     * @return mixed
     */
    public function getCurrentStage()
    {
        return $this->currentStage;
    }

    /**
     * @param mixed $currentStage
     * @return Game
     */
    public function setCurrentStage($currentStage)
    {
        $this->currentStage = $currentStage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentStageStart()
    {
        return $this->currentStageStart;
    }

    /**
     * @param mixed $currentStageStart
     * @return Game
     */
    public function setCurrentStageStart($currentStageStart)
    {
        $this->currentStageStart = $currentStageStart;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentStageAdditionalTime()
    {
        return $this->currentStageAdditionalTime;
    }

    /**
     * @param mixed $currentStageAdditionalTime
     * @return Game
     */
    public function setCurrentStageAdditionalTime($currentStageAdditionalTime)
    {
        $this->currentStageAdditionalTime = $currentStageAdditionalTime;
        return $this;
    }


    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function __toString()
    {
        return $this->id;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    /**
     * @param mixed $homeTeam
     * @return Game
     */
    public function setHomeTeam($homeTeam)
    {
        $this->homeTeam = $homeTeam;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    /**
     * @param mixed $awayTeam
     * @return Game
     */
    public function setAwayTeam($awayTeam)
    {
        $this->awayTeam = $awayTeam;
        return $this;
    }

}
