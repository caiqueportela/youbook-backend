<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tb_wallet")
 * @ORM\Entity(repositoryClass=WalletRepository::class)
 */
class Wallet
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $walletId;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $activated;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, options={"default": 0.00})
     */
    private $balance;

    public function __construct()
    {
        $this->activated = true;
        $this->balance = 0.00;
    }

    public function getWalletId(): ?int
    {
        return $this->walletId;
    }

    public function getActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }

    public function getBalance(): ?string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

}
