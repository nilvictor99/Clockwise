<?php

namespace App\Repositories\Models;

use App\Models\Timesheet;
use App\Services\Models\UserService;
use App\Services\Utils\LocationIpService;
use App\Services\Utils\QrGeneratorService;
use Carbon\Carbon;

class TimesheetRepository extends BaseRepository
{
    private $qrGeneratorService;

    private $userService;

    const RELATIONS = [];

    public function __construct(Timesheet $model, QrGeneratorService $qrGeneratorService, UserService $userService)
    {
        parent::__construct($model, self::RELATIONS);
        $this->qrGeneratorService = $qrGeneratorService;
        $this->userService = $userService;
    }

    public function getModel($search = null, $startDate = null, $endDate = null, $staffId = null, $perPage = 5)
    {
        $query = $this->model->withStaffProfile();

        if ($this->userService->getAuthUser()->hasRole('Staff')) {
            $query->filterByUserAccess($this->userService->getAuthUserId());
        }

        if ($search || $startDate || $endDate || $staffId) {
            $query->searchData($search, $startDate, $endDate, $staffId);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function generateQrCode($data): array
    {
        $options = [
            'format' => 'png',
            'size' => 300,
            'margin' => 10,
            'errorCorrectionLevel' => 'high',
        ];
        $qrCode = $this->qrGeneratorService->generate($data, $options);

        return ['qrCode' => $qrCode, 'name' => $data['name'] ?? ''];
    }

    public function getDataById($id)
    {
        return $this->model->withStaffProfile()->findOrFail($id);
    }

    public function updateData($id, array $data)
    {
        $timesheet = $this->model->findOrFail($id);
        $updateData = $this->processUpdateData($data);
        $timesheet->update($updateData);

        return $timesheet;
    }

    private function processUpdateData(array $data): array
    {
        $dayIn = $data['date'].' '.$data['day_in'].':00';
        $dayOut = $data['date'].' '.$data['day_out'].':00';
        $hoursTime = $this->formatHoursToTime($dayIn.','.$dayOut);

        return [
            'day_in' => $dayIn,
            'day_out' => $dayOut,
            'hours' => $hoursTime,
        ];
    }

    private function extractIdFromQr(string $qrData): ?string
    {
        $lines = explode("\n", trim($qrData));
        foreach ($lines as $line) {
            if (strpos($line, 'id:') === 0) {
                return trim(str_replace('id:', '', $line));
            }
        }

        return null;
    }

    private function prepareData(array $data): array
    {
        $qrData = $data['qr_data'] ?? null;
        $staffId = $this->extractIdFromQr($qrData);
        $userId = $this->userService->getAuthUserId();
        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();
        $newType = $data['type'] ?? 'work';
        $location = $this->getLocationFromIp();

        if ($userId == $staffId) {
            $authUser = $this->userService->getAuthUser();
            if ($authUser->hasRole('Staff')) {
                throw new \Exception('Asistencia No registrada.');
            }
        }

        return [
            'staffId' => $staffId,
            'userId' => $userId,
            'currentDate' => $currentDate,
            'currentTime' => $currentTime,
            'newType' => $newType,
            'location' => $location,
        ];
    }

    private function getLocationFromIp(): array
    {
        $locationService = app(LocationIpService::class);
        $coordinates = $locationService->getCoordinates();

        return [
            'latitude' => $coordinates['latitude'] ?? null,
            'longitude' => $coordinates['longitude'] ?? null,
            'location' => $coordinates['city'] ?? null,
        ];
    }

    private function handleTimesheetRegister(array $prepared): Timesheet
    {
        $staffId = $prepared['staffId'];
        $userId = $prepared['userId'];
        $currentDate = $prepared['currentDate'];
        $currentTime = $prepared['currentTime'];
        $newType = $prepared['newType'];
        $location = json_encode($prepared['location']);

        $openTimesheet = $this->model->findOpenByType($staffId, $currentDate, $newType)->first();

        if ($openTimesheet) {
            return $this->closeTimesheet($openTimesheet, $currentDate, $currentTime, $location);
        }

        return $this->createNewTimesheet(
            $userId,
            $staffId,
            $currentDate,
            $currentTime,
            $newType,
            $location
        );
    }

    private function closeTimesheet(Timesheet $timesheet, string $currentDate, string $currentTime, string $location): Timesheet
    {
        $dayOut = $currentDate.' '.$currentTime;
        $hoursTime = $this->formatHoursToTime($timesheet->day_in.','.$dayOut);

        $timesheet->update([
            'day_out' => $dayOut,
            'hours' => $hoursTime,
            'location' => $location,
        ]);

        return $timesheet;
    }

    private function createNewTimesheet(
        string $userId,
        string $staffId,
        string $currentDate,
        string $currentTime,
        string $type,
        string $location
    ): Timesheet {
        return $this->model->create([
            'user_id' => $userId,
            'staff_id' => $staffId,
            'calendar' => Carbon::now()->year,
            'day_in' => $currentDate.' '.$currentTime,
            'day_out' => null,
            'hours' => null,
            'type' => $type,
            'location' => $location,
        ]);
    }

    public function formatHoursToTime($input): string
    {
        if (is_string($input)) {
            [$dayIn, $dayOut] = explode(',', $input);
            $dayInCarbon = Carbon::parse($dayIn);
            $dayOutCarbon = Carbon::parse($dayOut);
            $diffInMinutes = $dayInCarbon->diffInMinutes($dayOutCarbon);
        } else {
            $diffInMinutes = round($input * 60);
        }

        $hours = floor($diffInMinutes / 60);
        $minutes = $diffInMinutes % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }

    public function storeData(array $data): Timesheet
    {
        $prepared = $this->prepareData($data);

        return $this->handleTimesheetRegister($prepared);
    }
}
