<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Form\ExerciseType;
use App\Repository\ExerciseRepository;
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
    public function store(Request $request, ExerciseRepository $exerciseRepository): Response
    {
        $exercise = new Exercise();
        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $exerciseRepository->saveExercise($exercise);

            return $this->redirectToRoute('app_exercise');
        }

        return $this->render('exercise/create.html.twig', [
            'form' => $form,
        ]);
    }
}
