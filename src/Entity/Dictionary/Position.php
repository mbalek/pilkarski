<?php

namespace App\Entity\Dictionary;

use App\Entity\Footballer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ModificationData;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dictionary\PositionRepository")
 */
class Position
{
    use ModificationData;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $shortName;

    /**
     * @return mixed
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param mixed $shortName
     * @return Position
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
        return $this;
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Footballer", mappedBy="position")
     */
    private $footballers;

    public function __construct()
    {
        $this->footballers = new ArrayCollection();
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
            $footballer->setPosition($this);
        }

        return $this;
    }

    public function removeFootballer(Footballer $footballer): self
    {
        if ($this->footballers->contains($footballer)) {
            $this->footballers->removeElement($footballer);
            // set the owning side to null (unless already changed)
            if ($footballer->getPosition() === $this) {
                $footballer->setPosition(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
