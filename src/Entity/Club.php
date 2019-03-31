<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClubRepository")
 */
class Club
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
     * @ORM\Column(type="string", length=100)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stadium;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $championsLeagueWins;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $countryCupWins;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $leagueWins;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\league", inversedBy="clubs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $league;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Footballer", mappedBy="club")
     */
    private $footballers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GameTeam", mappedBy="club")
     */
    private $gameTeams;

    public function __construct()
    {
        $this->footballers = new ArrayCollection();
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStadium(): ?string
    {
        return $this->stadium;
    }

    public function setStadium(?string $stadium): self
    {
        $this->stadium = $stadium;

        return $this;
    }

    public function getChampionsLeagueWins(): ?int
    {
        return $this->championsLeagueWins;
    }

    public function setChampionsLeagueWins(?int $championsLeagueWins): self
    {
        $this->championsLeagueWins = $championsLeagueWins;

        return $this;
    }

    public function getCountryCupWins(): ?int
    {
        return $this->countryCupWins;
    }

    public function setCountryCupWins(?int $countryCupWins): self
    {
        $this->countryCupWins = $countryCupWins;

        return $this;
    }

    public function getLeagueWins(): ?int
    {
        return $this->leagueWins;
    }

    public function setLeagueWins(?int $leagueWins): self
    {
        $this->leagueWins = $leagueWins;

        return $this;
    }

    public function getLeague(): ?league
    {
        return $this->league;
    }

    public function setLeague(?league $league): self
    {
        $this->league = $league;

        return $this;
    }

    /**
     * @return Collection|Footballer[]
     */
    public function getFootballers(): Collection
    {
        return $this->footballers;
    }

    public function addFootballer(Footballer $footballer): self
    {
        if (!$this->footballers->contains($footballer)) {
            $this->footballers[] = $footballer;
            $footballer->setClub($this);
        }

        return $this;
    }

    public function removeFootballer(Footballer $footballer): self
    {
        if ($this->footballers->contains($footballer)) {
            $this->footballers->removeElement($footballer);
            // set the owning side to null (unless already changed)
            if ($footballer->getClub() === $this) {
                $footballer->setClub(null);
            }
        }

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
            $gameTeam->setClub($this);
        }

        return $this;
    }

    public function removeGameTeam(GameTeam $gameTeam): self
    {
        if ($this->gameTeams->contains($gameTeam)) {
            $this->gameTeams->removeElement($gameTeam);
            // set the owning side to null (unless already changed)
            if ($gameTeam->getClub() === $this) {
                $gameTeam->setClub(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

}
