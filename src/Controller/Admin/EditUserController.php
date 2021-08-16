<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\AddUserType;
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
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {

        $flash = null;
        $user = new User();
        $form = $this->createForm(AddUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $repo = $this->entityManager->getRepository(User::class);

            if (!empty($user->getId()) && !is_integer($user->getId())) {
                $flash['type'] = 'danger';
                $flash['message'] = "<strong>Erreur ! </strong> L'identifiant doit êtres un nombre." ;
            } elseif (!empty($user->getId()) && $repo->findOneById($user->getId())) {
                $flash['type'] = 'danger';
                $flash['message'] = "<strong>Erreur ! </strong> L'identifiant doit êtres unique." ;
            }

            if ($repo->findOneByUsername($user->getUsername())) {
                $flash['type'] = 'danger';
                $flash['message'] = "<strong>Erreur ! </strong> Le nom d'utilisateur doit êtres unique." ;
            }
            
            $roles = $form->get("roles")->getData();

            $roles = $roles ? ["ROLE_ADMIN"] : ['ROLE_USER'];

            if (!$form->get('password')->getData()) {
                $user->setPassword($user->getUsername() . rand(10, 99));
            }

            if ($form->isValid() && is_null($flash)) {
                $passwordClear = $user->getPassword();
                $password = $hasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $flash['type'] = 'success';
                $flash['message'] = "<strong>Succès ! </strong> L'utilisateur à bien été créé avec l'identifiant <strong>" . $user->getUsername() . "</strong> et le mot de passe <strong>" . $passwordClear . "</strong>." ;
            }
            
        }

        return $this->render('admin/add_user.html.twig', [
            'form' => $form->createView(),
            'flash' => $flash
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="admin_show_user")
     */
    public function show($id): Response
    {

        dd($this->getUser());
    }

    
}
