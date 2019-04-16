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
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message = "assert.global.notBlank")
     */
    private $eventTime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dictionary\eventsType", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $eventType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Footballer", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $footballer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="events")
     */
    private $game;

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

    public function getFootballer(): ?Footballer
    {
        return $this->footballer;
    }

    public function setFootballer(?Footballer $footballer): self
    {
        $this->footballer = $footballer;

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

}
