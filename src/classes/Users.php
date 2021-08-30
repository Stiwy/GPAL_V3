<?php

namespace Gpal\Src\Classes;

use App\Entity\User;

class Users
{

    public static function edit($entityManager, $form, $user, $id, $hasher, $notification)
    {
        $thisUser = $entityManager->getRepository(User::class)->findOneById($id);
        $repo = $entityManager->getRepository(User::class);

        if ($repo->findOneByUsername($user->getUsername()) && $thisUser->getUsername() != $user->getUsername()) {
            $notification['type'] = 'danger';
            $notification['message'] = "<strong>Erreur ! </strong> Le nom d'utilisateur doit êtres unique." ;
        }

        if ($form->get('password')->getData()) {
            $password = $hasher->hashPassword($user, $form->get('password')->getData());  
            $user->setPassword($password);
        }

        if (is_null($notification)) {
            $entityManager->flush();

            $notificationType = 'success';
            $notificationMessage = "<strong>Succès ! </strong> L'utilisateur <strong>" . $user->getUsername() . "</strong> à bien été mis à jour !" ;

            return ['notificationType' => $notificationType, 'notificationMessage' => $notificationMessage];
        }
    }

    public static function create($entityManager, $form, $user, $hasher, $notification)
    {
        $repo = $entityManager->getRepository(User::class);

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

            $entityManager->persist($user);
            $entityManager->flush();

            $notificationType = 'success';
            $notificationMessage = "<strong>Succès ! </strong> L'utilisateur à bien été créé avec l'identifiant <strong>" . $user->getUsername() . "</strong> et le mot de passe <strong>" . $passwordClear . "</strong>." ;

            return ['notificationType' => $notificationType, 'notificationMessage' => $notificationMessage];
        }
    }

}
