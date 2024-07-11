<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Workout;
use App\Form\ExerciseType;
use App\Form\WorkoutType;
use App\Repository\WorkoutRepository;
use App\Service\WorkoutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WorkoutController extends AbstractController
{
    #[Route('/workout', name: 'app_workout')]
    public function index(WorkoutService $workoutService): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if(in_array('ROLE_TRAINER', $user->getRoles())){
            $workouts = $workoutService->getAllWorkoutWithUser();

            return $this->render('workout/indexTrainer.html.twig', [
                'workouts' => $workouts,
            ]);
        }
        $workouts = $workoutService->findWorkoutsByUser($user);

        return $this->render('workout/index.html.twig', [
            'controller_name' => 'WorkoutController',
            'workouts' => $workouts,
        ]);
    }

//    #[Route('/workouts/trainer', name: 'app_workout_trainer')]
//    public function indexTrainer(WorkoutRepository $workoutRepository): Response
//    {
//        $workouts = $workoutRepository->findAllWithUser();
//
//        return $this->render('workout/indexTrainer.html.twig', [
//            'workouts' => $workouts,
//        ]);
//    }

    #[Route('/workout/create', name: 'workout_create')]
    public function store(Request $request, WorkoutService $workoutService): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $workout = new Workout();
        $form = $this->createForm(WorkoutType::class, $workout);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Workout $workout */

            $workout = $form->getData();

            /** @var User $user */

            $user = $this->getUser();

            if ($user) {
                $workout->setPerson($user);
            }


            $workoutService->save($workout);


//            if (isset($status['error']) && $status['error']) {
//                $this->addFlash('error', 'This Workout already exists.');
////                return $this->redirectToRoute('workout_create');
////            }

            return $this->redirectToRoute('app_workout');


        }

        return $this->render('workout/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/workout/details/{id}', name: 'app_workout_details')]
    public function show(WorkoutRepository $workoutRepository, $id): Response
    {


        $workout = $workoutRepository->find($id);

        return $this->render('workout/show.html.twig', [
            'controller_name' => 'WorkoutController',
            'workout' => $workout,
        ]);
    }

    #[Route('/workout/{id}/delete', name: 'delete_workout')]
    public function destroy(Request $request, WorkoutService $workoutService, $id): Response
    {

        $workout = $workoutService->getWorkoutById($id);

        if (!$workout) {
            throw $this->createNotFoundException('The exercise does not exist');
        }

        $workoutService->deleteWorkout($workout);
        $this->addFlash('success', 'workout deleted successfully');
        return $this->redirectToRoute('app_workout');

    }
}

