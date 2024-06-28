<?php

namespace App\Entity;

use App\Repository\ExerciseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
class Exercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MuscleGroup $MuscleGroup = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getMuscleGroupId(): ?MuscleGroup
    {
        return $this->muscleGroup_id;
    }

    public function setMuscleGroupId(?MuscleGroup $muscleGroup_id): static
    {
        $this->muscleGroup_id = $muscleGroup_id;

        return $this;
    }
}
