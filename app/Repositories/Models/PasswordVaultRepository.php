<?php

namespace App\Repositories\Models;

use App\Models\PasswordVault;
use App\Services\Models\UserService;
use App\Services\Utils\PasswordGeneratorService;

class PasswordVaultRepository extends BaseRepository
{
    private $PasswordGeneratorService;

    private $userService;

    const RELATIONS = [];

    public function __construct(PasswordVault $model, PasswordGeneratorService $PasswordGeneratorService, UserService $userService)
    {
        parent::__construct($model, self::RELATIONS);
        $this->PasswordGeneratorService = $PasswordGeneratorService;
        $this->userService = $userService;
    }

    public function getModel($search = null, $startDate = null, $endDate = null)
    {
        $query = $this->model->withUserProfile();

        if ($this->userService->getAuthUser()->hasRole('Staff')) {
            $query->filterByUserAccess($this->userService->getAuthUserId());
        }

        if ($search || $startDate || $endDate) {
            $query->searchData($search, $startDate, $endDate);
        }

        return $query->latest()->paginate(5);
    }

    public function generatePassword($length = 12)
    {
        return $this->PasswordGeneratorService->generate($length);
    }

    public function storeData(array $data)
    {
        return $this->model->create($data);
    }

    public function getDataById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function updateData($id, array $data)
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);

        return $record;
    }

    public function deleteData($id)
    {
        $record = $this->model->findOrFail($id);

        return $record->delete();
    }
}
