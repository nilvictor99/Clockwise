<?php

namespace App\Http\Controllers;

use App\Services\Models\TimesheetService;
use App\Services\Models\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    private $timeSheetService;

    private $userService;

    public function __construct(TimesheetService $timeSheetService, UserService $userService)
    {
        $this->timeSheetService = $timeSheetService;
        $this->userService = $userService;
    }

    public function index()
    {
        return Inertia::render('Dashboard');
    }

    public function show()
    {
        $staffs = $this->userService->getStaffs();
        $staffsPresentToday = $this->userService->getStaffsPresentToday();
        $staffsAbsentToday = $this->userService->getStaffsAbsentToday();

        return Inertia::render('Dashboard/Show', [
            'staffs' => $staffs,
            'staffsPresentToday' => $staffsPresentToday,
            'staffsAbsentToday' => $staffsAbsentToday,
        ]);
    }

    public function uploadbg(Request $request)
    {
        $this->userService->updateBackgroundImage($request->all());

        return redirect()->route('dashboard.show')->with('success', 'Background image updated successfully.');
    }
}
