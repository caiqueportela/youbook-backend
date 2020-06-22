<?php

namespace App\Entity;

use App\Repository\GroupUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tb_group_user")
 * @ORM\Entity(repositoryClass=GroupUserRepository::class)
 */
class GroupUser
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $groupUserId;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="groupUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class)
     * @ORM\JoinColumn(name="group_id", referencedColumnName="group_id", nullable=false)
     */
    private $group;

    /**
     * @ORM\ManyToMany(targetEntity="GroupRole")
     * @ORM\JoinTable(name="tb_rel_user_group_role",
     *     joinColumns={@ORM\JoinColumn(name="group_user_id", referencedColumnName="group_user_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="group_role_id")}
     * )
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getGroupUserId(): ?int
    {
        return $this->groupUserId;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): self
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return Collection|GroupRole[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    /**
     * @param GroupRole $role
     * @return $this
     */
    public function addRole(GroupRole $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @param GroupRole $role
     * @return $this
     */
    public function removeRole(GroupRole $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }

        return $this;
    }

}
