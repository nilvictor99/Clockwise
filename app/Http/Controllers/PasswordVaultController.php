<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordVaultRequest;
use App\Models\PasswordVault;
use App\Services\Models\PasswordShareService;
use App\Services\Models\PasswordVaultService;
use App\Services\Models\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PasswordVaultController extends Controller
{
    private $passwordVaultService;

    private $passwordShareService;

    private $userService;

    public function __construct(PasswordVaultService $passwordVaultService, PasswordShareService $passwordShareService, UserService $userService)
    {
        $this->passwordVaultService = $passwordVaultService;
        $this->passwordShareService = $passwordShareService;
        $this->userService = $userService;
    }

    public function index()
    {
        return Inertia::render('PasswordVault');
    }

    public function list(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $users = $this->userService->getWithoutAuthUser();
        $passwordShare = $this->passwordShareService->getByShareWith();
        $passwordVaults = $this->passwordVaultService->getModel($search, $startDate, $endDate);

        return Inertia::render('PasswordVault/List', [
            'passwordVaults' => $passwordVaults,
            'search' => $search,
            'dateRange' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'users' => $users,
            'passwordShare' => $passwordShare,
        ]);
    }

    public function create()
    {
        return Inertia::render('PasswordVault/Create');
    }

    public function generate()
    {
        $generatedPassword = $this->passwordVaultService->generatePassword();

        return response()->json(['password' => $generatedPassword]);
    }

    public function store(PasswordVaultRequest $request)
    {
        $data = $request->validated();
        $this->passwordVaultService->storeData($data);

        return redirect()->route('password-vault.list')->banner('Contraseña Registrada');
    }

    public function show(PasswordVault $passwordVault)
    {
        //
    }

    public function edit(Request $request)
    {
        $data = $this->passwordVaultService->getDataById($request->id);

        return Inertia::render('PasswordVault/Edit', [
            'data' => $data,
        ]);
    }

    public function update(PasswordVaultRequest $request)
    {
        $data = $request->validated();
        $this->passwordVaultService->updateData($request->id, $data);

        return redirect()->route('password-vault.list')->banner('Contraseña Actualizada');
    }

    public function destroy(Request $request)
    {
        $this->passwordVaultService->deleteData($request->id);

        return redirect()->route('password-vault.list')->banner('Contraseña Eliminada');
    }
}
