<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/intranet/acceuil", name="home")
     */
    public function index(): Response
    {
        $date = new DateTime();
        $user = $this->getUser();
        $user->setLastConnexion($date);

        $this->entityManager->flush();

        return $this->render('home/index.html.twig');
    }
}
