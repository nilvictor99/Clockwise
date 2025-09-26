<?php

namespace App\Repositories\Models;

use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserRepository extends BaseRepository
{
    private $AuthService;

    const RELATIONS = [];

    public function __construct(User $model, AuthService $authService)
    {
        parent::__construct($model, self::RELATIONS);
        $this->AuthService = $authService;
    }

    public function getStaffsWithTimeSheets()
    {
        return $this->model->WithStaffs()->get();
    }

    public function getAuthUser()
    {
        $user = $this->AuthService->getAuthUser();
        $user = $this->model->withBasicData()->find($user->id)->setAppends([]);

        return $user;
    }

    public function getBasicAuthUserWithQr()
    {
        $user = $this->AuthService->getAuthUser();
        $user = $this->model->withDataQr()->find($user->id)->setAppends([]);

        return $user;
    }

    public function getAuthUserId()
    {
        return $this->AuthService->getAuthUser()->id;
    }

    public function getWithoutAuthUser()
    {
        return $this->model->withNotEmail()
            ->whereNot('id', $this->AuthService->getAuthUser()->id)
            ->get();
    }

    public function getStaffs()
    {
        return $this->model->staffOnly()->count();
    }

    public function getStaffsPresentToday()
    {
        return $this->model->PresentToday()->get();
    }

    public function getStaffsAbsentToday()
    {
        return $this->model->AbsentToday()->get();
    }

    public function updateBackgroundImage(array $data)
    {
        $user = $this->AuthService->getAuthUser();
        $user = $this->model->find($user->id);
        $extension = $data['bg_image']->getClientOriginalExtension();
        $filename = Str::slug($user->name).'_bg.'.$extension;

        if ($user->bg_image) {
            Storage::disk('public')->delete($user->bg_image);
        }

        $user->bg_image = $data['bg_image']
            ->storeAs('backgrounds', $filename, 'public');

        $user->save();

        return $user;
    }

    public function uploadQrCode($userId, $qrCode)
    {
        $user = $this->model->find($userId);
        $user->update([
            'qr_code' => $qrCode['qrCode'] ?? $qrCode,
        ]);

        return $user;
    }
}
