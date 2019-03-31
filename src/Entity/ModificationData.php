<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;

trait ModificationData
{
    /**
     * @var \DateTimeImmutable
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    protected $createdAt;

    /**
     * @var UserInterface
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Symfony\Component\Security\Core\User\UserInterface")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $createdBy;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var UserInterface
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Symfony\Component\Security\Core\User\UserInterface")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $updatedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $changedAt;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="Symfony\Component\Security\Core\User\UserInterface")
     * @ORM\JoinColumn(name="changed_by", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $changedBy;

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     * @return mixed
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getCreatedBy(): ?UserInterface
    {
        return $this->createdBy;
    }

    /**
     * @param UserInterface $createdBy
     * @return mixed
     */
    public function setCreatedBt(UserInterface $createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return mixed
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getUpdatedBy(): ?UserInterface
    {
        return $this->updatedBy;
    }

    /**
     * @param UserInterface $updatedBy
     * @return mixed
     */
    public function setUpdatedBy(UserInterface $updatedBy)
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getChangedAt(): ?\DateTime
    {
        return $this->changedAt;
    }

    /**
     * @param \DateTime $changedAt
     * @return mixed
     */
    public function setChangedAt(\DateTime $changedAt)
    {
        $this->changedAt = $changedAt;
        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getChangedBy(): ?UserInterface
    {
        return $this->changedBy;
    }

    /**
     * @param UserInterface $changedBy
     * @return mixed
     */
    public function setChangedBy(UserInterface $changedBy)
    {
        $this->changedBy = $changedBy;
        return $this;
    }
}
