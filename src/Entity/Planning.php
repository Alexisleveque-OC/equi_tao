<?php

namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
#[UniqueEntity('title', message: 'Ce titre de planning est déjà utilisé.')]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?bool $isValid = null;

    #[ORM\Column]
    private ?int $numberOfDay = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastUpdateDate = null;

    #[ORM\ManyToOne(inversedBy: 'plannings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'planning', targetEntity: DayPlanning::class)]
    private Collection $daysPlanning;

    public function __construct()
    {
        $this->daysPlanning = new ArrayCollection();
    }

    public function get(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function isIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }

    public function getNumberOfDay(): ?int
    {
        return $this->numberOfDay;
    }

    public function setNumberOfDay(int $numberOfDay): self
    {
        $this->numberOfDay = $numberOfDay;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getLastUpdateDate(): ?\DateTimeInterface
    {
        return $this->lastUpdateDate;
    }

    public function setLastUpdateDate(\DateTimeInterface $lastUpdateDate): self
    {
        $this->lastUpdateDate = $lastUpdateDate;

        return $this;
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

    /**
     * @return Collection<int, DayPlanning>
     */
    public function getDaysPlanning(): Collection
    {
        return $this->daysPlanning;
    }

    public function addDaysPlanning(DayPlanning $daysPlanning): self
    {
        if (!$this->daysPlanning->contains($daysPlanning)) {
            $this->daysPlanning->add($daysPlanning);
            $daysPlanning->setPlanning($this);
        }

        return $this;
    }

    public function removeDaysPlanning(DayPlanning $daysPlanning): self
    {
        if ($this->daysPlanning->removeElement($daysPlanning)) {
            // set the owning side to null (unless already changed)
            if ($daysPlanning->getPlanning() === $this) {
                $daysPlanning->setPlanning(null);
            }
        }

        return $this;
    }
}
