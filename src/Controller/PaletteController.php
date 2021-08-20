<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Palette;
use App\Entity\ReferencesRegister;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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
        $palette = new Palette();

        $baseLink = ($request->get('add_palette_link')) ? $request->get('add_palette_link') : 'https://localhost:8000/intranet/acceuil';

        if ($request->get('palette') && !empty($request->get('palette'))) {

            $data = $request->get('palette');
            $date = new DateTime();

            $palette->setReference(htmlspecialchars($data['reference']));
            $palette->setWeg(htmlspecialchars($data['weg']));
            $palette->setShelf(htmlspecialchars($data['shelf']));
            $palette->setInsertDate($date);
            $palette->setUser($this->getUser());

             if (empty($palette->getReference())) {
                $notificationType = 'danger';
                $notificationMessage = "<strong>Erreur ! </strong> La référence n'as pas été renseignée." ;

                return $this->redirect($baseLink . '?notificationType=' . $notificationType . '&notificationMessage=' . $notificationMessage);
            }

            if (!$this->entityManager->getRepository(ReferencesRegister::class)->findOneByReference($palette->getReference())) {
                $notificationType = 'warning';
                $notificationMessage = "<strong>Attention ! </strong> La référence " . $palette->getReference() . " n'est pas encore enregistré. Avant de acréer la palette, vous devez créer la référence. <a class='btn btn-sm btn-success' href='/references/nouvelle/"  . $palette->getReference() . "'>Créer la référence</a>" ;

                return $this->redirect($baseLink . '?notificationType=' . $notificationType . '&notificationMessage=' . $notificationMessage);
            }

            if ($palette->getWeg() < 1 || $palette->getWeg() > 13) {
                $notificationType = 'danger';
                $notificationMessage = "<strong>Erreur ! </strong> L'allée " . $palette->getWeg() . " n'existe pas." ;

                return $this->redirect($baseLink . '?notificationType=' . $notificationType . '&notificationMessage=' . $notificationMessage);
            }

            if ($palette->getShelf() < 1 || $palette->getShelf() > 26) {
                $notificationType = 'danger';
                $notificationMessage = "<strong>Erreur ! </strong> Le rayon " . $palette->getShelf() . " n'existe pas." ;

                return $this->redirect($baseLink . '?notificationType=' . $notificationType . '&notificationMessage=' . $notificationMessage);
            }

            $this->entityManager->persist($palette);
            $this->entityManager->flush();

            $notificationType = 'success';
            $notificationMessage = "<strong>Succès ! </strong> la palette " . $palette->getReference() . " a bien été ajoutée en A" .  $palette->getWeg() . " R" . $palette->getShelf();

            return $this->redirect($baseLink . '?notificationType=' . $notificationType . '&notificationMessage=' . $notificationMessage);
        }

        return $this->redirect($baseLink);
    }
}