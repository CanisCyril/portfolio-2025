<?php

namespace App\Http\Controllers\Helpdesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Helpdesk\Ticket;
use Carbon\CarbonPeriod;
use App\Contracts\Helpdesk\ReportServiceInterface;



class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportServiceInterface $reportService)
    {

        $this->reportService = $reportService;
    }

    public function index()
    {
        $startDate = now()->endOfDay()->subMonths(6);
        $endDate = now()->startOfDay();

        $open = Ticket::open()->whereBetween('created_at', [$startDate, $endDate])->count();
        $resolved = Ticket::closed()->whereBetween('resolved_at', [$startDate, $endDate])->count();

        $areaReport = $this->reportService->areaReport();

        return inertia('Helpdesk/Report', [
            'areaReport' => $areaReport,
            'counts' => [
                'open' => $open,
                'closed' => $resolved,
            ],
        ]);
    }
}
