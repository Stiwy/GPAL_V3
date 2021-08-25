<?php

namespace Gpal\Src\Classes;

use App\Entity\Logs as LogsEntity;
use DateTime;

class Logs
{

    public static function add($entityManager, $palette, $user, $action, $info)
    {   
        $date = new DateTime();

        $logs = new LogsEntity();
        $logs->setUser($user);
        $logs->setPalette($palette);
        $logs->setAction($action);
        $logs->setInfo($info);
        $logs->setDateInsert($date);

        $entityManager->persist($logs);
        $entityManager->flush();
    }
}
