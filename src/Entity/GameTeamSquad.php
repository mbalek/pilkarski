<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameTeamSquadRepository")
 */
class GameTeamSquad
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Footballer", inversedBy="gameTeamSquads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $footballer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GameTeam", inversedBy="gameTeamSquads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gameTeam;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isReserve;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isCaptain;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Events", mappedBy="teamSquad")
     */
    private $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFootballer(): ?Footballer
    {
        return $this->footballer;
    }

    public function setFootballer(?Footballer $footballer): self
    {
        $this->footballer = $footballer;

        return $this;
    }

    public function getGameTeam(): ?GameTeam
    {
        return $this->gameTeam;
    }

    public function setGameTeam(?GameTeam $gameTeam): self
    {
        $this->gameTeam = $gameTeam;

        return $this;
    }

    public function getIsReserve(): ?bool
    {
        return $this->isReserve;
    }

    public function setIsReserve(?bool $isReserve): self
    {
        $this->isReserve = $isReserve;

        return $this;
    }

    public function getIsCaptain(): ?bool
    {
        return $this->isCaptain;
    }

    public function setIsCaptain(?bool $isCaptain): self
    {
        $this->isCaptain = $isCaptain;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

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
            $event->setTeamSquad($this);
        }

        return $this;
    }

    public function removeEvent(Events $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getTeamSquad() === $this) {
                $event->setTeamSquad(null);
            }
        }

        return $this;
    }

}
