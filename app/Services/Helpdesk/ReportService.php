<?php

namespace App\Services\Helpdesk;

use App\Contracts\Helpdesk\ReportServiceInterface;
use App\Models\Helpdesk\Ticket;
use Carbon\CarbonPeriod;

class ReportService implements ReportServiceInterface
{

    public function __construct()
    {

    }

    public function areaReport(): array
    {
        $startDate = now()->endOfDay()->subMonths(6);
        $endDate = now()->startOfDay();

        // $open = Ticket::open()->whereBetween('created_at', [$startDate, $endDate])->count();
        $open = Ticket::open()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('count', 'date');

        $resolved = Ticket::closed()
            ->selectRaw('DATE(resolved_at) as date, COUNT(*) as count')
            ->whereBetween('resolved_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('count', 'date');

        $counts = [
            'open' => $open,
            'resolved' => $resolved,
        ];

        $period = CarbonPeriod::create($startDate->format('Y-m-d'), '1 day', $endDate->format('Y-m-d'));

        $openTicketCounts = [];
        $resolvedTicketCounts = [];

        foreach ($period as $date) {

            $key = $date->format('Y-m-d');

            $openTicketCounts[] = (int) ($open[$key] ?? 0);
            $resolvedTicketCounts[] = (int) ($resolved[$key] ?? 0);
        }

        return [
            // 'counts' => $counts,
            'openTicketCount' => $openTicketCounts,
            'resolvedTicketCount' => $resolvedTicketCounts,
            'period' => $period->toArray(),
        ];
    }
}
