<?php

namespace App\Filament\Widgets;

use App\Models\Timesheet;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Filament\Support\RawJs;
use Illuminate\Contracts\Support\Htmlable;

class TimesheetChart extends AdvancedChartWidget
{
    use HasWidgetShield;

    public function getHeading(): string|Htmlable|null
    {
        return __('Attendances');
    }

    public function getDescription(): ?string
    {
        $dateRange = match ($this->filter) {
            'today' => __('Today: :date', ['date' => today()->format('Y-m-d')]),
            'week' => __('Week: :start to :end', [
                'start' => now()->startOfWeek()->format('Y-m-d'),
                'end' => now()->endOfWeek()->format('Y-m-d'),
            ]),
            'month' => __('Month: :start to :end', [
                'start' => now()->startOfMonth()->format('Y-m-d'),
                'end' => now()->endOfMonth()->format('Y-m-d'),
            ]),
            'year' => __('Year: :start to :end', [
                'start' => now()->startOfYear()->format('Y-m-d'),
                'end' => now()->endOfYear()->format('Y-m-d'),
            ]),
            default => __('All time'),
        };

        return __('Number of attendances registered by period. Filtered: :range', ['range' => $dateRange]);
    }

    public ?string $filter = 'today';

    protected function getFilters(): ?array
    {
        return [
            'today' => __('Today'),
            'week' => __('Week'),
            'month' => __('Month'),
            'year' => __('Year'),
        ];
    }

    protected function getData(): array
    {
        $labels = match ($this->filter) {
            'today' => [now()->format('Y-m-d')],
            'week' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'month' => array_map(fn ($d) => sprintf('%02d', $d), range(1, now()->daysInMonth)),
            default => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        };

        $dateFormat = match ($this->filter) {
            'today' => 'Y-m-d',
            'week' => 'D',
            'month' => 'd',
            default => 'M',
        };

        $query = Timesheet::query()
            ->when($this->filter, function ($q) {
                return match ($this->filter) {
                    'today' => $q->whereDate('created_at', today()),
                    'week' => $q->whereBetween('created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek(),
                    ]),
                    'month' => $q->whereMonth('created_at', now()->month),
                    'year' => $q->whereYear('created_at', now()->year),
                };
            });

        $records = $query->get();

        $grouped = $records->groupBy(function ($item) use ($dateFormat) {
            return __(date($dateFormat, strtotime($item->created_at)));
        });

        $data = collect($labels)->map(fn ($label) => $grouped->get($label, collect())->count())->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('Attendances'),
                    'data' => $data,
                    'backgroundColor' => 'rgb(16, 185, 129)', // green
                    'borderColor' => 'transparent',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
    {
        scales: {
            y: {
                beginAtZero: true,
            },
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.parsed.y + ' attendances';
                    }
                }
            }
        }
    }
    JS);
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
