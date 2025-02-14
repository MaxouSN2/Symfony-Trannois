<?php

namespace App\Service;

use App\Entity\User;

class StorageService
{
    public function calculateUsedSpace(User $user): float
    {
        $totalUsedSpace = 0;

        // Calculer l'espace utilisé en fonction des fichiers des dossiers
        foreach ($user->getFolders() as $folder) {
            foreach ($folder->getFiles() as $file) {
                $totalUsedSpace += $file->getSize();
            }
        }

        return $totalUsedSpace;
    }

    public function calculateRemainingSpace(User $user): float
    {
        $usedSpace = $this->calculateUsedSpace($user);
        $maxSpace = $this->getMaxSpaceByPlan($user);

        return $maxSpace - $usedSpace;
    }

    private function getMaxSpaceByPlan(User $user): float
    {
        switch ($user->getPlan()) {
            case '1Mo':
                return 1;
            case '10Mo':
                return 10;
            case '100Mo':
                return 100;
            default:
                return 0;
        }
    }
}
