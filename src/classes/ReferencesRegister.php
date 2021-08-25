<?php

namespace Gpal\Src\Classes;

use App\Entity\ReferencesRegister as EntityReferencesRegister;

class ReferencesRegister
{

    public static function findAll($entityManager)
    {   
        $listRefs = "";

        $refsData = $entityManager->getRepository(EntityReferencesRegister::class)->findAll();
        foreach ($refsData as $listRef) {
            $listRefs .= $listRef->getReference() . ",";
        } 

        return $listRefs;
    }
}
