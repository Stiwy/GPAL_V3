<?php

namespace App\Controller;

use App\Entity\Palette;
use Doctrine\ORM\EntityManagerInterface;
use Gpal\Src\Classes\ReferencesRegister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/rechercher", name="search")
     */
    public function index(): Response
    {
        $notification = null;
        $listRefs = ReferencesRegister::findAll($this->entityManager);
        $searchRef = "";
        $palettes = "";

        if (isset($_GET['notificationType'])) {
            $notification['type'] = $_GET['notificationType'];
            $notification['message'] = $_GET['notificationMessage'];
        }

        if (!empty($_GET['search'])) {
            $searchRef = htmlspecialchars($_GET['search']);
            $palettes = $this->entityManager->getRepository(Palette::class)->findByRef($searchRef);
        }

        return $this->render('search/index.html.twig', [
            'palettes' => $palettes,
            'listRefs' => $listRefs,
            'searchRef' => $searchRef,
            'notification' => $notification
        ]);
    }
}
