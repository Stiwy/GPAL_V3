<?php

namespace Gpal\Src\Classes;

use App\Entity\Palette as EntityPalette;
use App\Entity\ReferencesRegister;
use DateTime;

class Palette
{

    public function add($request, $user, $entityManager)
    {   
        $palette = new EntityPalette();
        $baseLink = ($request->get('add_palette_link')) ? $request->get('add_palette_link') : 'https://localhost:8000/intranet/acceuil';

        $baseLink = explode("?", $baseLink)[0];
        
        if ($request->get('palette') && !empty($request->get('palette'))) {

            $date = new DateTime();
            $data = $request->get('palette');
            $company = empty($data['company']) ? "Robé Médical" : $data['company'];

            $palette->setReference(htmlspecialchars($data['reference']));
            $palette->setWeg((int) $data['weg']);
            $palette->setShelf((int) $data['shelf']);
            $palette->setInsertDate($date);
            $palette->setCompany($company);

             if (empty($palette->getReference())) {
                $notificationType = 'danger';
                $notificationMessage = "<strong>Erreur ! </strong> La référence n'as pas été renseignée." ;

                return $baseLink . '?notificationType=' . $notificationType . '&notificationMessage=' . $notificationMessage;
            }

            if ($request->get('add_palette_link') == 'no-registered') {
                $referencesRegister = new ReferencesRegister();
                $referencesRegister->setReference($palette->getReference());
                $this->entityManager->persist($referencesRegister);
                $this->entityManager->flush();
            }

            if ($palette->getWeg() < 1 || $palette->getWeg() > 13) {
                $notificationType = 'danger';
                $notificationMessage = "<strong>Erreur ! </strong> L'allée " . $palette->getWeg() . " n'existe pas." ;

                return $baseLink . '?notificationType=' . $notificationType . '&notificationMessage=' . $notificationMessage;
            }

            if ($palette->getShelf() < 1 || $palette->getShelf() > 26) {
                $notificationType = 'danger';
                $notificationMessage = "<strong>Erreur ! </strong> Le rayon " . $palette->getShelf() . " n'existe pas." ;

                return $baseLink . '?notificationType=' . $notificationType . '&notificationMessage=' . $notificationMessage;
            }

            $entityManager->persist($palette);
            $entityManager->flush();

            Logs::add($entityManager, $palette, $user, 'Création', 'La palette ' . $palette->getReference() . ' a bien été ajouté en A' . $palette->getWeg() . ' R' . $palette->getShelf());

            $notificationType = 'success';
            $notificationMessage = "<strong>Succès ! </strong> la palette " . $palette->getReference() . " a bien été ajoutée en A" .  $palette->getWeg() . " R" . $palette->getShelf();

            return $baseLink . '?notificationType=' . $notificationType . '&notificationMessage=' . $notificationMessage;
        }
    }
}