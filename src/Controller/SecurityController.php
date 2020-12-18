<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Form\UserFormType2;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/profile/userlist", name="userList")
     */
    public function userList(UserRepository $userRepository)
    {

        // chercher tous les utilisateurs
        $userList = $userRepository->findAll();
        //afficher dans le twig
        return $this->render('security/index.html.twig', [
            'userList' => $userList,
        ]);
    }

    /**
     * @Route("/profile/add", name="userAdd")
     */
    public function addUser(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User;
        $form = $this->createForm(UserFormType2::class, $user);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $roles = $form->get('roles')->getData();

            $user->setRoles([0 => $roles]);

            $plainPassword = $form['password']->getData();


            if (trim($plainPassword) != '') {
                //encrypt pass
                $password = $passwordEncoder->encodePassword($user, $plainPassword);
                $user->setPassword($password);
            }

            $em->persist($user);
            $em->flush();


            $this->addFlash('success', 'Utilisateur créé avec succès');

            return $this->redirectToRoute('userList', []);
        }



        return $this->render('security/add.html.twig', [
            'form' => $form->createView(),
            'toto' => 'toto'
        ]);
    }

    /**
     * @Route("/profile/edit/{id}", name="userEdit")
     */
    public function editUser(EntityManagerInterface $em, User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            //$role = $request->request->get('user')['roles'];
            $roles = $form->get('roles')->getData();
            $user->setRoles([0 => $roles]);

            $plainPassword = $form['password']->getData();
            if (trim($plainPassword) != '') {
                //encrypt pass
                $password = $passwordEncoder->encodePassword($user, $plainPassword);
                $user->setPassword($password);
            }

            $em->persist($user);
            $em->flush();


            $this->addFlash('success', 'Utilisateur mis à jour avec succès');

            return $this->redirectToRoute('userList', []);
        }

        return $this->render('security/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
