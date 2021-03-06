<?php

namespace App\Entity;

use App\Repository\UserRoleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tb_user_role")
 * @ORM\Entity(repositoryClass=UserRoleRepository::class)
 */
class UserRole
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $userRoleId;

    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function getUserRoleId(): ?int
    {
        return $this->userRoleId;
    }

    public function setUserRoleId(int $userRoleId): self
    {
        $this->userRoleId = $userRoleId;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

}
