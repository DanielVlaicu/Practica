<?php

namespace App\Service;

use App\Entity\Exercise;
use App\Repository\ExerciseRepository;

class ExerciseService
{

    public  function __construct(private readonly ExerciseRepository $exerciseRepository)
    {

    }

    public function getExerciseById($exerciseId):object
    {
        return $this->exerciseRepository->find($exerciseId);
    }

    public function getAllExercises()
    {
        return $this->exerciseRepository->findAll();
    }


    public function validateAndSaveExercise(Exercise $exercise): array
    {
        try {

            $existingExercise = $this->exerciseRepository->findOneBy(['name' => $exercise->getName()]);


            if ($existingExercise) {
                throw new \Exception('Exercise with this name already exists.');
            }

            $this->exerciseRepository->saveExercise($exercise);
            return [
                'success' => true,
                'message' => 'Exercise group created successfully.'
            ];

        } catch (\Exception $exeption) {

            return [
                'success' => false,
                'message' => 'Exercise group with this name already exists.'
            ];
        }


    }

    public function validateAndUpdateExercise(Exercise $exercise): array
    {
        try {

            $existingExercise = $this->exerciseRepository->findOneBy(['name' => $exercise->getName()]);


            if (!$existingExercise) {
                throw new \Exception('Exercise with this name is not exists.');
            }

            $this->exerciseRepository->saveExercise($exercise);
            return [
                'success' => true,
                'message' => 'Exercise group update successfully.'
            ];

        } catch (\Exception $exeption) {

            return [
                'success' => false,
                'message' => 'Exercise with this name is not exists.'
            ];
        }
    }

    public function deleteExercise(Exercise $exercise):void
    {
        $existingExercise->@this->exerciseRepository-> delete();
    }

}