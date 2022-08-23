<?php

namespace App\Entity;

use App\Repository\DemandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandRepository::class)]
#[ORM\Table(name: '`demands`')]
class Demand
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campervan $campervan = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $rentalStart = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $rentalEnd = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Station $startStation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Station $endStation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    #[ORM\OneToMany(mappedBy: 'demand', targetEntity: DemandItem::class)]
    private Collection $demandItems;

    public function __construct()
    {
        $this->demandItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCampervan(): ?Campervan
    {
        return $this->campervan;
    }

    public function setCampervan(?Campervan $campervan): self
    {
        $this->campervan = $campervan;

        return $this;
    }

    public function getRentalStart(): ?\DateTimeInterface
    {
        return $this->rentalStart;
    }

    public function setRentalStart(\DateTimeInterface $rentalStart): self
    {
        $this->rentalStart = $rentalStart;

        return $this;
    }

    public function getRentalEnd(): ?\DateTimeInterface
    {
        return $this->rentalEnd;
    }

    public function setRentalEnd(\DateTimeInterface $rentalEnd): self
    {
        $this->rentalEnd = $rentalEnd;

        return $this;
    }

    public function getStartStation(): ?Station
    {
        return $this->startStation;
    }

    public function setStartStation(?Station $startStation): self
    {
        $this->startStation = $startStation;

        return $this;
    }

    public function getEndStation(): ?Station
    {
        return $this->endStation;
    }

    public function setEndStation(?Station $endStation): self
    {
        $this->endStation = $endStation;

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection<int, DemandItem>
     */
    public function getDemandItems(): Collection
    {
        return $this->demandItems;
    }

    public function addDemandItem(DemandItem $demandItem): self
    {
        if (!$this->demandItems->contains($demandItem)) {
            $this->demandItems->add($demandItem);
            $demandItem->setDemand($this);
        }

        return $this;
    }
}
