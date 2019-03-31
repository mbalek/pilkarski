<?php

namespace App\Entity\Dictionary;

use App\Entity\GameTeam;
use App\Entity\MatchTeam;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ModificationData;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dictionary\FormationRepository")
 */
class Formation
{
    use ModificationData;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GameTeam", mappedBy="formation")
     */
    private $gameTeams;

    public function __construct()
    {
        $this->matchTeams = new ArrayCollection();
        $this->gameTeams = new ArrayCollection();
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

    /**
     * @return Collection|GameTeam[]
     */
    public function getGameTeams(): Collection
    {
        return $this->gameTeams;
    }

    public function addGameTeam(GameTeam $gameTeam): self
    {
        if (!$this->gameTeams->contains($gameTeam)) {
            $this->gameTeams[] = $gameTeam;
            $gameTeam->setFormation($this);
        }

        return $this;
    }

    public function removeGameTeam(GameTeam $gameTeam): self
    {
        if ($this->gameTeams->contains($gameTeam)) {
            $this->gameTeams->removeElement($gameTeam);
            // set the owning side to null (unless already changed)
            if ($gameTeam->getFormation() === $this) {
                $gameTeam->setFormation(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
    
}
