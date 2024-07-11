<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/register', name: 'register_user')]
    public function store(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $user = $form->getData();
            $password = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);


            $userRepository->saveUser($user);




            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form,
        ]);


    }

    #[Route('/users', name: 'list_users')]
    public function list(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/details/{id}', name: 'list_user_details')]
    public function userDetails(UserRepository $userRepository, string $id): Response
    {
        $id = (int)$id;
        $user = $userRepository->find($id);
        return $this->render('user/show.html.twig', [
            'user' => $user

        ]);


    }

}
