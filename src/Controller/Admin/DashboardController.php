<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Gpal\Src\Classes\ReferencesRegister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $notification = null;
        $listRefs = ReferencesRegister::findAll($this->entityManager);

        if (isset($_GET['notificationType'])) {
            $notification['type'] = $_GET['notificationType'];
            $notification['message'] = $_GET['notificationMessage'];
        }

        $users = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'notification' => $notification,
            'listRefs' => $listRefss
        ]);
    }
}
