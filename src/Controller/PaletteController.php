<?php

namespace App\Controller;

use App\Classes\Search;
use App\Entity\Logs;
use App\Entity\Palette;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Gpal\Src\Classes\Logs as ClassesLogs;
use Gpal\Src\Classes\Palette as ClassesPalette;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaletteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/intranet/ajouter_palette", name="add_palette")
     */
    public function add(Request $request): Response
    {

        $palette = new ClassesPalette();
        $redirectUrl = $palette->add($request, $this->getUser(), $this->entityManager);

        return $this->redirect($redirectUrl);
    }

    /**
     * @Route("/intranet/palette/{id}", name="show_palette")
     */
    public function show($id): Response
    {
        $notification = null;
        $listRefs = "";

        $palette = $this->entityManager->getRepository(Palette::class)->findOneById($id);
        $logs = $this->entityManager->getRepository(Logs::class)->findByPalette($palette);

        return $this->render('palette/index.html.twig', array(
            'notification' => $notification,
            'listRefs' => $listRefs,
            'palette' => $palette,
            'logs' => $logs
        ));
    }

     /**
     * @Route("/intranet/palette/supp/{id}", name="delete_palette")
     */
    public function delete($id): Response
    {
        $baseLink = 'https://localhost:8000/intranet/acceuil';
        $baseLink = explode("?", $baseLink)[0];

        $palette = $this->entityManager->getRepository(Palette::class)->findOneById($id);

        $notificationType = 'success';
        $notificationMessage = "<strong>Succès ! </strong> la palette " . $palette->getReference() . " a bien été retirée";
        
        ClassesLogs::add($this->entityManager, $palette, $this->getUser(), 'Suppréssion', 'La palette ' . $palette->getReference() . ' a bien été retiré en A' . $palette->getWeg() . ' R' . $palette->getShelf());
        
        $this->entityManager->remove($palette);
        $this->entityManager->flush($palette);

        return $this->redirect($baseLink . '?notificationType=' . $notificationType . '&notificationMessage=' . $notificationMessage);
    }
}