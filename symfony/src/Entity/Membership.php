<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Membership
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Client $client = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?TrainingProgram $trainingProgram = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column(length: 20)]
    private ?string $status = 'active';

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @param Client|null $client
     * @return $this
     */
    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return TrainingProgram|null
     */
    public function getTrainingProgram(): ?TrainingProgram
    {
        return $this->trainingProgram;
    }

    /**
     * @param TrainingProgram|null $trainingProgram
     * @return $this
     */
    public function setTrainingProgram(?TrainingProgram $trainingProgram): static
    {
        $this->trainingProgram = $trainingProgram;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @param \DateTimeImmutable $startDate
     * @return $this
     */
    public function setStartDate(\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * @param \DateTimeImmutable $endDate
     * @return $this
     */
    public function setEndDate(\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
