<?php

namespace App\Entity;

use App\Repository\CustomersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomersRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Customers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $uid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=History::class, mappedBy="customer")
     */
    private $history;

    public function __construct(string $uid)
    {
        $this->history = new ArrayCollection();
        $this->uid = $uid;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|History[]
     */
    public function getHistory(): Collection
    {
        return $this->history;
    }

    public function addHistory(History $history): self
    {
        if (!$this->history->contains($history)) {
            $this->history[] = $history;
            $history->setCustomer($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->history->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getCustomer() === $this) {
                $history->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setTimestamps(): void
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }
}
