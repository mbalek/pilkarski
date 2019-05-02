<?php

namespace App\Entity;

use App\Entity\Dictionary\Position;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FootballerRepository")
 * @Vich\Uploadable
 */
class Footballer
{
    use ModificationData;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank(message = "assert.global.notBlank")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank(message = "assert.global.notBlank")
     */
    private $surname;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $goals;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $assists;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $yellowCards;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $redCards;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dictionary\Position", inversedBy="footballers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club", inversedBy="footballers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return Footballer
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GameTeamSquad", mappedBy="footballer")
     */
    private $gameTeamSquads;

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
        $this->matchTeamSquads = new ArrayCollection();
        $this->gameTeamSquads = new ArrayCollection();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getGoals(): ?int
    {
        return $this->goals;
    }

    public function setGoals(?int $goals): self
    {
        $this->goals = $goals;

        return $this;
    }

    public function getAssists(): ?int
    {
        return $this->assists;
    }

    public function setAssists(?int $assists): self
    {
        $this->assists = $assists;

        return $this;
    }

    public function getYellowCards(): ?int
    {
        return $this->yellowCards;
    }

    public function setYellowCards(?int $yellowCards): self
    {
        $this->yellowCards = $yellowCards;

        return $this;
    }

    public function getRedCards(): ?int
    {
        return $this->redCards;
    }

    public function setRedCards(?int $redCards): self
    {
        $this->redCards = $redCards;

        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): self
    {
        $this->position = $position;

        return $this;
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
            $gameTeamSquad->setFootballer($this);
        }

        return $this;
    }

    public function removeGameTeamSquad(GameTeamSquad $gameTeamSquad): self
    {
        if ($this->gameTeamSquads->contains($gameTeamSquad)) {
            $this->gameTeamSquads->removeElement($gameTeamSquad);
            // set the owning side to null (unless already changed)
            if ($gameTeamSquad->getFootballer() === $this) {
                $gameTeamSquad->setFootballer(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName().' '.$this.$this->getSurname();
    }

}
