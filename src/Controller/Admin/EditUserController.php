<?php

namespace App\Controller\Admin;

use App\Entity\Logs;
use App\Entity\User;
use App\Form\EditUserType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Gpal\Src\Classes\ReferencesRegister;
use Gpal\Src\Classes\Users;
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
        $listRefs = ReferencesRegister::findAll($this->entityManager);

        $notification = null;
        $user = new User();
        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('admin', Users::create($this->entityManager, $form, $user, $hasher, $notification));
            
        }

        return $this->render('admin/edit_user.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
            'listRefs' => $listRefs
        ]);
    }

    /**
     * @Route("/admin/utilisateur/{id}", name="admin_show_user")
     */
    public function show($id): Response
    {
        $listRefs = ReferencesRegister::findAll($this->entityManager);

        $date = new DateTime('now'); 
        $date -> modify('-2 day');
        $date -> format('Y-m-d');

        $user = $this->entityManager->getRepository(User::class)->findOneById($id);
        $logs = $this->entityManager->getRepository(Logs::class)->findByUser($user);
        $userLogs = $this->entityManager->getRepository(Logs::class)->findLogsUserByDate($user, $date);

        return $this->render('admin/show_user.html.twig', [
            'user' => $user,
            'listRefs' => $listRefs,
            'logs' => array_reverse($logs),
            'userLogs' => $userLogs
        ]);
    }

    /**
     * @Route("/admin/editer_utilisateur/{id}", name="admin_edit_user")
     */
    public function edit($id, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $listRefs = ReferencesRegister::findAll($this->entityManager);
        
        $notification = null;
        $user = $this->entityManager->getRepository(User::class)->findOneById($id);
        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            
            return $this->redirectToRoute('admin', Users::edit($this->entityManager, $form, $user, $id, $hasher, $notification));
            
        }

        return $this->render('admin/edit_user.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
            'listRefs' => $listRefs
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
