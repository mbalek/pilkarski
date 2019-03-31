<?php
/**
 * Created by PhpStorm.
 * User: Ikki
 * Date: 16.03.2019
 * Time: 13:05
 */

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\SoftDeleteable;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements SoftDeleteable
{
    use ModificationData;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $lastName;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    protected $isSystem = false;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return User
     */
    public function setLastName(?string $lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSystem(): bool
    {
        return $this->isSystem;
    }

    /**
     * @param bool $isSystem
     * @return User
     */
    public function setIsSystem(bool $isSystem): User
    {
        $this->isSystem = $isSystem;
        return $this;
    }
}