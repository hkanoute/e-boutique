<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    #[Route('/user/update', name: 'update_user')]
    public function update(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $name = $request->get("name");
        $firstname = $request->get("firstName");
        $email = $request->get("email");
        $phone = $request->get("phone");

        if (isset($name) && isset($firstname) && isset($email) && isset($phone)){
            $user->setName($name);
            $user->setFirstName($firstname);
            $user->setEmail($email);
            $user->setPhone($phone);
            $em->persist($user);
            $em->flush();
        }
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
