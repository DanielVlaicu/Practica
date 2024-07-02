<?php

namespace App\Service;

use App\Entity\Exercise;
use App\Entity\MuscleGroup;
use App\Repository\MuscleGroupRepository;

class MuscleGroupService
{
    private $muscleGroupRepository;

    public function __construct(MuscleGroupRepository $muscleGroupRepository)
    {
        $this->muscleGroupRepository = $muscleGroupRepository;
    }

    public function validateAndSaveMuscleGroup(MuscleGroup $muscleGroup): array
    {
        $existingMuscleGroup = $this->muscleGroupRepository->findOneBy(['name' => $muscleGroup->getName()]);

        if ($existingMuscleGroup) {
            return [
                'success' => false,
                'message' => 'Muscle group with this name already exists.'
            ];
        }

        $this->muscleGroupRepository->saveMuscleGroup($muscleGroup);

        return [
            'success' => true,
            'message' => 'Muscle group created successfully.'
        ];
    }
}