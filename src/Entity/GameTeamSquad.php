<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
}
