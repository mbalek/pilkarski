<?php

namespace App\Entity;

use App\Entity\Dictionary\Formation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameTeamRepository")
 */
class GameTeam
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club", inversedBy="gameTeams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="gameTeams")
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dictionary\Formation", inversedBy="gameTeams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GameTeamSquad", mappedBy="gameTeam")
     */
    private $gameTeamSquads;

    public function __construct()
    {
        $this->gameTeamSquads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

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
            $gameTeamSquad->setGameTeam($this);
        }

        return $this;
    }

    public function removeGameTeamSquad(GameTeamSquad $gameTeamSquad): self
    {
        if ($this->gameTeamSquads->contains($gameTeamSquad)) {
            $this->gameTeamSquads->removeElement($gameTeamSquad);
            // set the owning side to null (unless already changed)
            if ($gameTeamSquad->getGameTeam() === $this) {
                $gameTeamSquad->setGameTeam(null);
            }
        }

        return $this;
    }
}
