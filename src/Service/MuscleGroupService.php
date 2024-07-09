<?php

namespace App\Service;

use App\Entity\Exercise;
use App\Entity\MuscleGroup;
use App\Repository\ExerciseLogRepository;
use App\Repository\ExerciseRepository;
use App\Repository\MuscleGroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class MuscleGroupService
{
    private MuscleGroupRepository $muscleGroupRepository;
    private EntityManagerInterface $entityManager;
    private ExerciseRepository $exerciseRepository;
    private ExerciseLogRepository $exerciseLogRepository;

    public function __construct(MuscleGroupRepository $muscleGroupRepository, EntityManagerInterface $entityManager, ExerciseRepository $exerciseRepository,ExerciseLogRepository $exerciseLogRepository)
    {
        $this->entityManager = $entityManager;
        $this->exerciseRepository = $exerciseRepository;
        $this->muscleGroupRepository = $muscleGroupRepository;
        $this->exerciseLogRepository = $exerciseLogRepository;
    }

    public function validateAndSaveMuscleGroup(MuscleGroup $muscleGroup): array
    {
        try {

            $existingMuscleGroup = $this->muscleGroupRepository->findOneBy(['name' => $muscleGroup->getName()]);


            if ($existingMuscleGroup) {
                throw new \Exception('Muscle group with this name already exists.');
            }

            $this->muscleGroupRepository->saveMuscleGroup($muscleGroup);
            return [
                'success' => true,
                'message' => 'Muscle group created successfully.'
            ];

        } catch (\Exception $exeption) {

            return [
                'success' => false,
                'message' => 'Muscle group with this name already exists.'
            ];
        }


    }

    public function deleteMuscleGroupWithExercises(MuscleGroup $muscleGroup): void
    {

        $exercises = $this->exerciseRepository->findBy(['muscleGroup' => $muscleGroup]);


        foreach ($exercises as $exercise) {
            $exerciseLogs = $this->exerciseLogRepository->findBy(['exercise' => $exercise]);
            foreach ($exerciseLogs as $exerciseLog) {
                $this->entityManager->remove($exerciseLog);
            }


            $this->entityManager->remove($exercise);
        }


        $this->entityManager->remove($muscleGroup);
        $this->entityManager->flush();
    }

    public function getMuscleGroupById($muscleGroupId): object
    {
        return $this->muscleGroupRepository->find($muscleGroupId);
    }


}