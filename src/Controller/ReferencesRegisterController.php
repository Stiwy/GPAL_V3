<?php

namespace App\Controller;

use App\Entity\ReferencesRegister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReferencesRegisterController extends AbstractController
{
    
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/references/nouvelle/{ref}", name="references_register")
     */
    public function index($ref): Response
    {

        $referencesRegister = new ReferencesRegister();
        
        if ($ref) {
            $referencesRegister->setReference($ref);
            $this->entityManager->persist($referencesRegister);
            $this->entityManager->flush();

            $notificationType = 'success';
            $notificationMessage = "<strong>Succès ! </strong> La référence " . $ref . " a bien été ajouté. <strong>N'oublié pas de créer la palette</strong>." ;

            return $this->redirect('https://localhost:8000/intranet/acceuil?ref=' . $ref . '&notificationType=' . $notificationType . '&notificationMessage=' . $notificationMessage);
        }

        return $this->redirectToRoute('home');
    }
}
