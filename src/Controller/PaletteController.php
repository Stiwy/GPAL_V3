<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
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
}