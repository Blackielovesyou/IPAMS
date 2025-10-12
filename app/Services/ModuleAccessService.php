<?php

namespace App\Services;

use App\Models\ModuleAccessModel;

class ModuleAccessService
{
    public function userHasAccess($userID, $moduleCode)
    {
        return ModuleAccessModel::where('userID', $userID)
                            ->where('module_code', $moduleCode)
                            ->exists();
    }
}
