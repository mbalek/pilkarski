<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClubRepository")
 * @Vich\Uploadable
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
     * @Assert\NotBlank(message = "assert.global.notBlank")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message = "assert.global.notBlank")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stadium;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\League", inversedBy="clubs")
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

    /**
     *
     * @Vich\UploadableField(mapping="images", fileNameProperty="image.name", size="image.size", mimeType="image.mimeType", originalName="image.originalName", dimensions="image.dimensions")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     *
     * @var EmbeddedFile
     */
    private $image;

    public function __construct()
    {
        $this->footballers = new ArrayCollection();
        $this->gameTeams = new ArrayCollection();
        $this->image = new EmbeddedFile();
    }

    /**
     * @param File|UploadedFile $image
     */
    public function setImageFile(?File $image = null): void
    {
        $this->imageFile = $image;
        if (null !== $image) {
            $this->updatedAt = new \DateTimeImmutable();
        }    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImage(EmbeddedFile $image)
    {
        $this->image = $image;
    }

    public function getImage(): ?EmbeddedFile
    {
        return $this->image;
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


    public function getLeague(): ?League
    {
        return $this->league;
    }

    public function setLeague(?League $league): self
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
