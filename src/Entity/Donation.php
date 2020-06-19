<?php

namespace App\Entity;

use App\Repository\DonationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tb_donation")
 * @ORM\Entity(repositoryClass=DonationRepository::class)
 */
class Donation
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $donationId;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class, inversedBy="donations")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="group_id", nullable=false)
     */
    private $group;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="donor_id", referencedColumnName="user_id", nullable=false)
     */
    private $donor;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $value;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="json")
     */
    private $information = [];

    public function getDonationId(): ?int
    {
        return $this->donationId;
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

    public function getDonor(): ?User
    {
        return $this->donor;
    }

    public function setDonor(?User $donor): self
    {
        $this->donor = $donor;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getInformation(): ?array
    {
        return $this->information;
    }

    public function setInformation(array $information): self
    {
        $this->information = $information;

        return $this;
    }
    
}
