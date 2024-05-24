<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $Hasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() &&$form->isValid()){
            $user=$form->getData();//récupére les données

            $password=$Hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $entityManager->persist($user);//garde les données en mémoire
            $entityManager->flush();//retourne les données dans la bdd
        }
        return $this->render('user/index.html.twig', [

            'form'=> $form->createView(),
        ]);
    }
}
