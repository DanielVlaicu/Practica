<?php

namespace App\Controller;

use App\Entity\MuscleGroup;
use App\Entity\User;
use App\Form\MuscleGroupType;
use App\Form\UserType;
use App\Repository\MuscleGroupRepository;
use App\Repository\UserRepository;
use App\Service\MuscleGroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class MuscleGroupController extends AbstractController
{
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
            $muscleGroup = $form->getData();

            // Salvează noul grup muscular
            $muscleGroupRepository->saveMuscleGroup($muscleGroup);

            return $this->redirectToRoute('app_muscle_group');
        }

        // Obține o listă de toate grupurile musculare pentru a le afișa în formular
        $muscleGroups = $muscleGroupRepository->findAll();

        return $this->render('muscle_group/create.html.twig', [
            'form' => $form->createView(),
            'muscleGroups' => $muscleGroups, // Pasează lista de grupuri musculare către șablon
        ]);
    }
}
