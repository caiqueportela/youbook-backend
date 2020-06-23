<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="tb_user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class User implements UserInterface
{

    /**
     * @var int
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose()
     */
    private $userId;

    /**
     * @var string
     * @ORM\Column(type="string", length=25, unique=true)
     * @Serializer\Expose()
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, unique=true)
     * @Serializer\Expose()
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Serializer\Exclude()
     */
    private $password;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $activated = true;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    private $locale = 'en';

    /**
     * @var UserRole[]
     * @ORM\ManyToMany(targetEntity="UserRole")
     * @ORM\JoinTable(name="tb_rel_user_role",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="user_role_id")}
     * )
     */
    private $roles;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="address_id")
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity=GroupUser::class, mappedBy="user", orphanRemoval=true, cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    private $groupUser;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->groupUser = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function isActivated(): bool
    {
        return $this->activated;
    }

    /**
     * @param bool $activated
     */
    public function setActivated(bool $activated): void
    {
        $this->activated = $activated;
    }

    /**
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     *
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return array_map(function($role) {
            return $role->getName();
        }, $this->roles->getValues());
    }

    /**
     * @return Collection|UserRole[]
     */
    public function getUserRoles(): Collection
    {
        return $this->roles;
    }

    /**
     * @param UserRole[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @param UserRole $role
     * @return $this
     */
    public function addRole(UserRole $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;;
        }

        return $this;
    }

    /**
     * @param UserRole $role
     * @return $this
     */
    public function removeRole(UserRole $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection|GroupUser[]
     */
    public function getGroupUser(): Collection
    {
        return $this->groupUser;
    }

    public function addGroupUser(GroupUser $groupUser): self
    {
        if (!$this->groupUser->contains($groupUser)) {
            $this->groupUser[] = $groupUser;
            $groupUser->setUser($this);
        }

        return $this;
    }

    public function removeGroupUser(GroupUser $groupUser): self
    {
        if ($this->groupUser->contains($groupUser)) {
            $this->groupUser->removeElement($groupUser);
            // set the owning side to null (unless already changed)
            if ($groupUser->getUser() === $this) {
                $groupUser->setUser(null);
            }
        }

        return $this;
    }

}