<?php

namespace App\Controller;

use App\Entity\MuscleGroup;
use App\Entity\User;
use App\Form\MuscleGroupType;
use App\Form\UserType;
use App\Repository\MuscleGroupRepository;
use App\Repository\UserRepository;
use App\Service\ExerciseService;
use App\Service\MuscleGroupService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class MuscleGroupController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private MuscleGroupService $muscleGroupService;

    public function __construct(EntityManagerInterface $entityManager,MuscleGroupService $muscleGroupService)
    {
        $this->entityManager = $entityManager;
        $this->muscleGroupService = $muscleGroupService;

    }

    #[Route('/muscle-group', name: 'app_muscle_group')]
    public function index(MuscleGroupRepository $muscleGroupRepository): Response
    {
        $muscleGroups = $muscleGroupRepository->findAll();

        return $this->render('muscle_group/index.html.twig', [
            'muscleGroups' => $muscleGroups,
        ]);
    }

    #[Route('/muscle-group/create', name: 'muscle-group_create')]
    public function store(Request $request, MuscleGroupRepository $muscleGroupRepository): Response
    {
        $muscleGroup = new MuscleGroup();
        $form = $this->createForm(MuscleGroupType::class, $muscleGroup);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingMuscleGroup = $muscleGroupRepository->findOneBy(['name' => $muscleGroup->getName()]);

            if ($existingMuscleGroup) {
                $this->addFlash('error', 'This Muscle Group already exists.');
                return $this->redirectToRoute('muscle-group_create');

            } else {
                $this->entityManager->persist($muscleGroup);
                $this->entityManager->flush();
                $this->addFlash('success', 'Muscle Group created successfully.');
                return $this->redirectToRoute('app_muscle_group');
            }
        }


        $muscleGroups = $muscleGroupRepository->findAll();

        return $this->render('muscle_group/create.html.twig', [
            'form' => $form->createView(),
            'muscleGroups' => $muscleGroups,
        ]);
    }

    #[Route('/muscle-group/{id}/exercises', name: 'muscle_group_exercises')]
    public function muscleGroupExercises(MuscleGroup $muscleGroup): Response
    {
        $exercises = $this->muscleGroupService->getExercisesByMuscleGroup($muscleGroup->getName());

        return $this->render('muscle_group/tabel.html.twig', [
            'muscleGroup' => $muscleGroup,
            'exercises' => $exercises,
        ]);
    }



}
