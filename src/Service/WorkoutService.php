<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Workout;
use App\Repository\ExerciseLogRepository;
use App\Repository\WorkoutRepository;

class WorkoutService
{
    private WorkoutRepository $workoutRepository;
    private ExerciseLogRepository $exerciseLogRepository;

    public function __construct(WorkoutRepository $workoutRepository,ExerciseLogRepository $exerciseLogRepository)
    {
        $this->workoutRepository = $workoutRepository;
        $this->exerciseLogRepository = $exerciseLogRepository;
    }

    /**
     *
     * @throws \Exception
     */
    public function save(Workout $workout): void
    {

        $this->workoutRepository->saveWorkout($workout);

    }

    public function findWorkoutsByUser(User $user)
    {
       return $this->workoutRepository->findByUserId($user);


    }

    public function getAllWorkoutWithUser()
    {
        return $this->workoutRepository->findAllWithUser();
    }

    public function deleteWorkout(Workout $workout): void
    {
        $this->workoutRepository->delete($workout);
    }

    public function getWorkoutById($workoutId): object
    {
        return $this->workoutRepository->find($workoutId);
    }

    public function getWorkoutDurations($workouts)
    {
        $workoutDurations = [];
        foreach ($workouts as $workout) {
            $totalDuration = 0;
            foreach ($workout->getExerciseLogs() as $exerciseLog) {
                $totalDuration += $exerciseLog->getDuration();
            }
            $workoutDurations[$workout->getId()] = $totalDuration;
        }

        return $workoutDurations;
    }


}