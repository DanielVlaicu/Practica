<?php

namespace App\Controller;

use App\Entity\MuscleGroup;
use App\Entity\User;
use App\Form\MuscleGroupType;
use App\Form\UserType;
use App\Repository\MuscleGroupRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class MuscleGroupController extends AbstractController
{
    #[Route('/muscle-group', name: 'app_muscle_group')]
    public function index(): Response
    {
        return $this->render('muscle_group/index.html.twig', [
            'controller_name' => 'MuscleGroupController',
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

            $muscleGroupRepository->saveMuscleGroup($muscleGroup);



            return $this->redirectToRoute('app_muscle_group');
        }

        return $this->render('muscle_group/create.html.twig', [
            'form' => $form,
        ]);


    }


}
