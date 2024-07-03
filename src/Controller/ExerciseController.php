<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Form\ExerciseType;
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

    #[Route('/exercise/{id}', name: 'edit_exercise', methods: ['GET','PUT'])]
    public function update(Request $request, ExerciseService $exerciseService, $id)
    {

        $exercise = $exerciseService->getExerciseById($id);
        $form = $this->createForm(ExerciseType::class, $exercise);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $exercise = $form->getData();

            $exerciseService->validateAndUpdateExercise($exercise);
        }

        return $this->render('exercise/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/exercise/{id}', name: 'delete_exercise', methods: ['DELETE'])]
    public function destroy(Request $request, ExerciseService $exerciseService): void
    {
        dd($request);
    }


}