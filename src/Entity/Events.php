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

    /*
     * Event types:
     * 1 - Basic
     * 2 - Card
     * 3 - Penalty
     * 4 - Goal
     * 5 - Substitution
     * 6 - Added Time
     */

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $eventType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="events")
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GameTeamSquad", inversedBy="events")
     * @ORM\JoinColumn(nullable=true)
     */
    private $player1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GameTeamSquad", inversedBy="events")
     * @ORM\JoinColumn(nullable=true)
     */
    private $player2;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $otherData;

    /**
     * @return mixed
     */
    public function getOtherData()
    {
        return $this->otherData;
    }

    /**
     * @param mixed $otherData
     * @return Events
     */
    public function setOtherData($otherData)
    {
        $this->otherData = $otherData;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * @param mixed $minute
     * @return Events
     */
    public function setMinute($minute)
    {
        $this->minute = $minute;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return Events
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlayer1()
    {
        return $this->player1;
    }

    /**
     * @param mixed $player1
     * @return Events
     */
    public function setPlayer1($player1)
    {
        $this->player1 = $player1;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getPlayer2()
    {
        return $this->player2;
    }

    /**
     * @param mixed $player2
     * @return Events
     */
    public function setPlayer2($player2)
    {
        $this->player2 = $player2;
        return $this;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventType(): ?int
    {
        return $this->eventType;
    }

    public function setEventType(?int $eventType): self
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


}
