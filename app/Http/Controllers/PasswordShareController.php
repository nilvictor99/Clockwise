<?php

namespace App\Http\Controllers;

use App\Models\PasswordShare;
use App\Services\Models\PasswordShareService;
use App\Services\Models\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PasswordShareController extends Controller
{
    private $passwordShareService;

    private $userService;

    public function __construct(PasswordShareService $passwordShareService, UserService $userService)
    {
        $this->passwordShareService = $passwordShareService;
        $this->userService = $userService;
    }

    public function index()
    {
        return Inertia::render('PasswordShare');
    }

    public function list(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $passwordShares = $this->passwordShareService->getModel($search, $startDate, $endDate);

        return Inertia::render('PasswordShare/List', [
            'passwordShares' => $passwordShares,
            'search' => $search,
            'dateRange' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->passwordShareService->storeData($request->all());

        return redirect()->back()->banner('Contraseña compartida.');
    }

    public function show(PasswordShare $passwordShare)
    {
        //
    }

    public function edit(Request $request)
    {
        $data = $this->passwordShareService->getDataById($request->id);

        return Inertia::render('PasswordShare/Edit', [
            'data' => $data,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $this->passwordShareService->updateData($request->id, $data);

        return redirect()->route('password-share.list')->banner('Acceso compartido actualizado.');
    }

    public function destroy(Request $request)
    {
        $this->passwordShareService->deleteData($request->id);

        return redirect()->back()->banner('Accesos compartido Eliminado');
    }
}
