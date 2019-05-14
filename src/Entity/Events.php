<?php

namespace App\Entity;

use App\Entity\Dictionary\eventsType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventsRepository")
 */
class Events
{
    use ModificationData;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\NotBlank(message = "assert.global.notBlank")
     */
    private $minute;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dictionary\eventsType", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $eventType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="events")
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GameTeamSquad", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teamSquad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventTime(): ?\DateTimeInterface
    {
        return $this->eventTime;
    }

    public function setEventTime(\DateTimeInterface $eventTime): self
    {
        $this->eventTime = $eventTime;

        return $this;
    }

    public function getEventType(): ?eventsType
    {
        return $this->eventType;
    }

    public function setEventType(?eventsType $eventType): self
    {
        $this->eventType = $eventType;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getTeamSquad(): ?GameTeamSquad
    {
        return $this->teamSquad;
    }

    public function setTeamSquad(?GameTeamSquad $teamSquad): self
    {
        $this->teamSquad = $teamSquad;

        return $this;
    }


}
