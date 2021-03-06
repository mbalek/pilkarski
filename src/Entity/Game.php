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
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $firstHalfStart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $firstHalfEnd;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $secondHalfStart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $secondHalfEnd;

    /**
     * @ORM\Column(type="datetime" , nullable=true)
     */
    private $extentedFirstHalfStart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $extentedFirstHalfEnd;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $extentedSecondHalfStart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $extentedSecondHalfEnd;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $state;

    

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

    public function getFirstHalfStart(): ?\DateTimeInterface
    {
        return $this->firstHalfStart;
    }

    public function setFirstHalfStart(?\DateTimeInterface $firstHalfStart): self
    {
        $this->firstHalfStart = $firstHalfStart;

        return $this;
    }

    public function getFirstHalfEnd(): ?\DateTimeInterface
    {
        return $this->firstHalfEnd;
    }

    public function setFirstHalfEnd(?\DateTimeInterface $firstHalfEnd): self
    {
        $this->firstHalfEnd = $firstHalfEnd;

        return $this;
    }

    public function getSecondHalfStart(): ?\DateTimeInterface
    {
        return $this->secondHalfStart;
    }

    public function setSecondHalfStart(?\DateTimeInterface $secondHalfStart): self
    {
        $this->secondHalfStart = $secondHalfStart;

        return $this;
    }

    public function getSecondHalfEnd(): ?\DateTimeInterface
    {
        return $this->secondHalfEnd;
    }

    public function setSecondHalfEnd(?\DateTimeInterface $secondHalfEnd): self
    {
        $this->secondHalfEnd = $secondHalfEnd;

        return $this;
    }

    public function getExtentedFirstHalfStart(): ?\DateTimeInterface
    {
        return $this->extentedFirstHalfStart;
    }

    public function setExtentedFirstHalfStart(\DateTimeInterface $extentedFirstHalfStart): self
    {
        $this->extentedFirstHalfStart = $extentedFirstHalfStart;

        return $this;
    }

    public function getExtentedFirstHalfEnd(): ?\DateTimeInterface
    {
        return $this->extentedFirstHalfEnd;
    }

    public function setExtentedFirstHalfEnd(?\DateTimeInterface $extentedFirstHalfEnd): self
    {
        $this->extentedFirstHalfEnd = $extentedFirstHalfEnd;

        return $this;
    }

    public function getExtentedSecondHalfStart(): ?\DateTimeInterface
    {
        return $this->extentedSecondHalfStart;
    }

    public function setExtentedSecondHalfStart(?\DateTimeInterface $extentedSecondHalfStart): self
    {
        $this->extentedSecondHalfStart = $extentedSecondHalfStart;

        return $this;
    }

    public function getExtentedSecondHalfEnd(): ?\DateTimeInterface
    {
        return $this->extentedSecondHalfEnd;
    }

    public function setExtentedSecondHalfEnd(?\DateTimeInterface $extentedSecondHalfEnd): self
    {
        $this->extentedSecondHalfEnd = $extentedSecondHalfEnd;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(?int $state): self
    {
        $this->state = $state;

        return $this;
    }

}
