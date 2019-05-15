<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubstitutionRepository")
 */
class Substitution
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GameTeamSquad", inversedBy="substituted")
     * @ORM\JoinColumn(nullable=false)
     */
    private $playerIn;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerIn(): ?GameTeamSquad
    {
        return $this->playerIn;
    }

    public function setPlayerIn(?GameTeamSquad $playerIn): self
    {
        $this->playerIn = $playerIn;

        return $this;
    }
}
