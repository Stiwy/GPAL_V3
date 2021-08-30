<?php

namespace App\Controller;

use App\Entity\Logs;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Gpal\Src\Classes\ReferencesRegister;
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
        if ($this->getUser()->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->redirectToRoute('admin');
        }

        $notification = null;
        $ref = null;
        $listRefs = ReferencesRegister::findAll($this->entityManager);

        $date = new DateTime('now'); 
        $date -> modify('-2 day');
        $date -> format('Y-m-d');

        $userLogs = $this->entityManager->getRepository(Logs::class)->findLogsUserByDate($this->getUser(), $date);

        if (isset($_GET['notificationType'])) {
            $notification['type'] = $_GET['notificationType'];
            $notification['message'] = $_GET['notificationMessage'];
        }

        if (isset($_GET['ref'])) {
            $ref = $_GET['ref'];
        }

        $date = new DateTime();
        $user = $this->getUser();
        $user->setLastConnexion($date);

        $this->entityManager->flush();

        return $this->render('home/index.html.twig', [
            'notification' => $notification,
            'ref' => $ref,
            'userLogs' => array_reverse($userLogs),
            'listRefs' => $listRefs,
        ]);
    }
}
