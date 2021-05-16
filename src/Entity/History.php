<?php

namespace App\Entity;

use App\Repository\HistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoryRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class History
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $parameters;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullUrl;

    /**
     * @ORM\ManyToOne(targetEntity=Types::class, inversedBy="history")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Customers::class, inversedBy="history")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct(string $url, string $fullUrl, Types $type, Customers $customer, $parameters = null)
    {
        $this->url = $url;
        $this->fullUrl = $fullUrl;
        $this->type = $type;
        $this->customer = $customer;
        $this->parameters = $parameters;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getParameters(): ?string
    {
        return $this->parameters;
    }

    public function setParameters(string $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getFullUrl(): ?string
    {
        return $this->fullUrl;
    }

    public function setFullUrl(string $fullUrl): self
    {
        $this->fullUrl = $fullUrl;

        return $this;
    }

    public function getType(): ?Types
    {
        return $this->type;
    }

    public function setType(?Types $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCustomer(): ?Customers
    {
        return $this->customer;
    }

    public function setCustomer(?Customers $customer): self
    {
        $this->customer = $customer;

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
     * @ORM\PrePersist
     */
    public function setTimestamps(): void
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }
}
