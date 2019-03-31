<?php

namespace App\Entity;

use App\Entity\Dictionary\Position;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FootballerRepository")
 */
class Footballer
{
    use ModificationData;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $surname;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $goals;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $assists;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $yellowCards;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $redCards;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dictionary\Position", inversedBy="footballers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club", inversedBy="footballers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Events", mappedBy="footballer")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GameTeamSquad", mappedBy="footballer")
     */
    private $gameTeamSquads;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->matchTeamSquads = new ArrayCollection();
        $this->gameTeamSquads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getGoals(): ?int
    {
        return $this->goals;
    }

    public function setGoals(?int $goals): self
    {
        $this->goals = $goals;

        return $this;
    }

    public function getAssists(): ?int
    {
        return $this->assists;
    }

    public function setAssists(?int $assists): self
    {
        $this->assists = $assists;

        return $this;
    }

    public function getYellowCards(): ?int
    {
        return $this->yellowCards;
    }

    public function setYellowCards(?int $yellowCards): self
    {
        $this->yellowCards = $yellowCards;

        return $this;
    }

    public function getRedCards(): ?int
    {
        return $this->redCards;
    }

    public function setRedCards(?int $redCards): self
    {
        $this->redCards = $redCards;

        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

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
            $event->setFootballer($this);
        }

        return $this;
    }

    public function removeEvent(Events $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getFootballer() === $this) {
                $event->setFootballer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GameTeamSquad[]
     */
    public function getGameTeamSquads(): Collection
    {
        return $this->gameTeamSquads;
    }

    public function addGameTeamSquad(GameTeamSquad $gameTeamSquad): self
    {
        if (!$this->gameTeamSquads->contains($gameTeamSquad)) {
            $this->gameTeamSquads[] = $gameTeamSquad;
            $gameTeamSquad->setFootballer($this);
        }

        return $this;
    }

    public function removeGameTeamSquad(GameTeamSquad $gameTeamSquad): self
    {
        if ($this->gameTeamSquads->contains($gameTeamSquad)) {
            $this->gameTeamSquads->removeElement($gameTeamSquad);
            // set the owning side to null (unless already changed)
            if ($gameTeamSquad->getFootballer() === $this) {
                $gameTeamSquad->setFootballer(null);
            }
        }

        return $this;
    }

}
