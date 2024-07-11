<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Entity\MuscleGroup;
use App\Form\ExerciseType;
use App\Repository\ExerciseLogRepository;
use App\Repository\ExerciseRepository;
use App\Service\ExerciseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExerciseController extends AbstractController
{


    #[Route('/exercise', name: 'app_exercise')]
    public function index(ExerciseRepository $exerciseRepository): Response
    {
        $exercises = $exerciseRepository->findAll();

        return $this->render('exercise/index.html.twig', [
            'exercises' => $exercises,
        ]);
    }

    #[Route('/exercise/create', name: 'exercise_create')]
    public function store(Request $request, ExerciseService $exerciseService): Response
    {
        $exercise = new Exercise();
        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $status = $exerciseService->validateAndSaveExercise($exercise);

            if (isset($status['error']) && $status['error']) {
                $this->addFlash('error', 'This Exercise already exists.');
                return $this->redirectToRoute('exercise_create');
            }

            return $this->redirectToRoute('app_exercise');


        }

        return $this->render('exercise/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/exercise/update/{id}', name: 'edit_exercise', methods: ['GET', 'POST'])]
    public function update(Request $request, ExerciseService $exerciseService, $id)
    {

        $exercise = $exerciseService->getExerciseById($id);

        if (!$exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        $form = $this->createForm(ExerciseType::class, $exercise);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Exercise $exercise */
            $exercise = $form->getData();


            $status = $exerciseService->validateAndUpdateExercise($exercise);
            if (!$status['success']) {

                $this->addFlash('error', 'This Exercise already exists.');

                return $this->redirectToRoute('edit_exercise',['id'=>$exercise->getId()]);

            }
        }

        return $this->render('exercise/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/exercise/{id}/delete', name: 'delete_exercise')]
    public function destroy(Request $request, ExerciseService $exerciseService, $id): Response
    {

        $exercise = $exerciseService->getExerciseById($id);

        if (!$exercise) {
            throw $this->createNotFoundException('The exercise does not exist');
        }

        $exerciseService->deleteExercise($exercise);
        $this->addFlash('success', 'Exercise deleted successfully');
        return $this->redirectToRoute('app_exercise');

    }

    #[Route('/muscle-group/{muscleGroupName}', name: 'exercises_by_muscle_group')]
    public function exercisesByMuscleGroup(string $muscleGroupName, ExerciseRepository $exerciseRepository): Response
    {
        $exercises = $exerciseRepository->findByMuscleGroup($muscleGroupName);

        return $this->render('exercise/exercises_by_muscle_group.html.twig', [
            'muscleGroupName' => $muscleGroupName,
            'exercises' => $exercises,
        ]);
    }

    #[Route('/exercise/{id}/logs', name: 'exercise_logs')]
    public function showLogs(ExerciseLogRepository $exerciseLogRepository, int $id,): Response
    {
        $user = $this->getUser();
        $logs = $exerciseLogRepository->findLogsByExerciseAndUser($id, $user->getId());

        return $this->render('exercise/logs.html.twig', [
            'logs' => $logs,
        ]);
    }


}