<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\EditUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class EditUserController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/ajouter_utilisateur", name="admin_add_user")
     */
    public function create(Request $request, UserPasswordHasherInterface $hasher): Response
    {

        $notification = null;
        $user = new User();
        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $repo = $this->entityManager->getRepository(User::class);

            if (!empty($user->getId()) && !is_integer($user->getId())) {
                $notification['type'] = 'danger';
                $notification['message'] = "<strong>Erreur ! </strong> L'identifiant doit êtres un nombre." ;
            } elseif (!empty($user->getId()) && $repo->findOneById($user->getId())) {
                $notification['type'] = 'danger';
                $notification['message'] = "<strong>Erreur ! </strong> L'identifiant doit êtres unique." ;
            }

            if ($repo->findOneByUsername($user->getUsername())) {
                $notification['type'] = 'danger';
                $notification['message'] = "<strong>Erreur ! </strong> Le nom d'utilisateur doit êtres unique." ;
            }

            if (!$form->get('password')->getData()) {
                $passwordClear = $user->getUsername() . rand(10, 99);
            } else {
                $passwordClear = $form->get('password')->getData();
            }

            if ($form->isValid() && is_null($notification)) {
                $password = $hasher->hashPassword($user, $passwordClear);
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $notificationType = 'success';
                $notificationMessage = "<strong>Succès ! </strong> L'utilisateur à bien été créé avec l'identifiant <strong>" . $user->getUsername() . "</strong> et le mot de passe <strong>" . $passwordClear . "</strong>." ;

                return $this->redirectToRoute('admin', ['notificationType' => $notificationType, 'notificationMessage' => $notificationMessage]);
            }
            
        }

        return $this->render('admin/edit_user.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }

    /**
     * @Route("/admin/utilisateur/{id}", name="admin_show_user")
     */
    public function show($id): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findOneById($id);

        return $this->render('admin/show_user.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/admin/editer_utilisateur/{id}", name="admin_edit_user")
     */
    public function edit($id, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        
        $notification = null;
        $user = $this->entityManager->getRepository(User::class)->findOneById($id);
        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            
            $thisUser = $this->entityManager->getRepository(User::class)->findOneById($id);
            $repo = $this->entityManager->getRepository(User::class);

            if ($repo->findOneByUsername($user->getUsername()) && $thisUser->getUsername() != $user->getUsername()) {
                $notification['type'] = 'danger';
                $notification['message'] = "<strong>Erreur ! </strong> Le nom d'utilisateur doit êtres unique." ;
            }

            if ($form->get('password')->getData()) {
                $password = $hasher->hashPassword($user, $form->get('password')->getData());  
                $user->setPassword($password);
            }

            if (is_null($notification)) {
                $this->entityManager->flush();

                $notificationType = 'success';
                $notificationMessage = "<strong>Succès ! </strong> L'utilisateur <strong>" . $user->getUsername() . "</strong> à bien été mis à jour !" ;

                return $this->redirectToRoute('admin', ['notificationType' => $notificationType, 'notificationMessage' => $notificationMessage]);
            }
            
        }

        return $this->render('admin/edit_user.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }

    /**
     * @Route("/admin/supprimer_utilisateur/{id}", name="admin_delete_user")
     */
    public function delete($id): Response
    {

        $user = $this->entityManager->getRepository(User::class)->findOneById($id);

        if ($user && $user != $this->getUser() && $user->getId() != 1) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            $notificationType = 'success';
            $notificationMessage = "<strong>Succès ! </strong> L'utilisateur <strong>" . $user->getUsername() . "</strong> à bien été supprimé !" ;

            return $this->redirectToRoute('admin', ['notificationType' => $notificationType, 'notificationMessage' => $notificationMessage]);
        } else {
            $notificationType = 'danger';

            if ($user->getId() == 1)
                $notificationMessage = "<strong>Erreur ! </strong> Vous ne pouvez pas supprimer le compte principal" ;
            else 
                $notificationMessage = "<strong>Erreur ! </strong> Vous ne pouvez pas supprimer votre compte" ;
    
            return $this->redirectToRoute('admin', ['notificationType' => $notificationType, 'notificationMessage' => $notificationMessage]);
        }

        return $this->redirectToRoute('admin');
    }

    
}
