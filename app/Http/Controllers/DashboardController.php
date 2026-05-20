<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Shipment;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Handle Period Filter
        $period = $request->get('period', 'monthly');
        $dateRange = match($period) {
            'weekly' => [now()->startOfWeek(), now()->endOfWeek()],
            'yearly' => [now()->startOfYear(), now()->endOfYear()],
            default  => [now()->startOfMonth(), now()->endOfMonth()],
        };

        // ==========================================
        // 2. TOP STATS CARDS DATA (Total & % Change)
        // ==========================================
        $now = Carbon::now();
        $last7Days = $now->copy()->subDays(7);
        $previous7Days = $now->copy()->subDays(14);

        // Shipments
        $shipmentsTotal = Shipment::count();
        $shipmentsChange = $this->calculatePercentageChange(
            Shipment::where('created_at', '>=', $last7Days)->count(),
            Shipment::whereBetween('created_at', [$previous7Days, $last7Days])->count()
        );

        // Patients
        $patientsTotal = Patient::count();
        $patientsChange = $this->calculatePercentageChange(
            Patient::where('created_at', '>=', $last7Days)->count(),
            Patient::whereBetween('created_at', [$previous7Days, $last7Days])->count()
        );

        // Appointments
        $appointmentsTotal = Appointment::count();
        $appointmentsChange = $this->calculatePercentageChange(
            Appointment::where('appointment_date', '>=', $last7Days)->count(),
            Appointment::whereBetween('appointment_date', [$previous7Days, $last7Days])->count()
        );

        // Revenue
        $revenueTotal = Invoice::sum('total_amount');
        $revenueChange = $this->calculatePercentageChange(
            Invoice::where('invoice_date', '>=', $last7Days)->sum('total_amount'),
            Invoice::whereBetween('invoice_date', [$previous7Days, $last7Days])->sum('total_amount')
        );

        // ==========================================
        // 3. GRAPH SECTION DATA (Filtered by Period)
        // ==========================================
        $baseQuery = Appointment::whereBetween('appointment_date', $dateRange);
        
        $allAppointments       = $baseQuery->count();
        $cancelledAppointments = (clone $baseQuery)->where('status', 'cancelled')->count();
        $pendingAppointments   = (clone $baseQuery)->whereIn('status', ['schedule', 'confirmed'])->count();
        $completedAppointments = (clone $baseQuery)->whereIn('status', ['checked_in', 'checked_out'])->count();

        // Chart Data Points
        $chartLabels = [];
        $chartData   = [];
        $days = $period === 'weekly' ? 7 : ($period === 'yearly' ? 12 : date('t'));
        
        for ($i = 0; $i < $days; $i++) {
            $date = $period === 'yearly' 
                ? Carbon::now()->subMonths($days - $i - 1)->format('M') 
                : Carbon::now()->subDays($days - $i - 1)->format('d M');
            
            $chartLabels[] = $date;
            $chartData[]   = Appointment::whereDate('appointment_date', Carbon::now()->subDays($days - $i - 1))->count();
        }

        // ==========================================
        // 4. RECENT APPOINTMENTS (Fixing the Error)
        // ==========================================
        $recentAppointments = Appointment::with('patient')
            ->where('appointment_date', '>=', now()->toDateString())
            ->orderBy('appointment_date', 'asc')
            ->limit(5)
            ->get();

            $recentShipments = \App\Models\Shipment::latest()->take(5)->get();
        // ==========================================
        // 5. RETURN TO VIEW (Passing ALL variables)
        // ==========================================
        return view('pages.dashboard', compact(
            'period',
            'chartLabels', 'chartData',
            'allAppointments', 'cancelledAppointments', 'pendingAppointments', 'completedAppointments',
            'recentAppointments', // <--- This was missing!
            'shipmentsTotal', 'shipmentsChange',
            'patientsTotal', 'patientsChange',
            'appointmentsTotal', 'appointmentsChange',
            'revenueTotal', 'revenueChange',
             'recentShipments' // ✅ Added this
        ));
    }

    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return round((($current - $previous) / $previous) * 100);
    }
}