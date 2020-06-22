<?php

namespace App\Entity;

use App\Repository\GroupRoleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tb_group_role")
 * @ORM\Entity(repositoryClass=GroupRoleRepository::class)
 */
class GroupRole
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $groupRoleId;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function getGroupRoleId(): ?int
    {
        return $this->groupRoleId;
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
