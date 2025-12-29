<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\Leave;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getStats()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 1. Basic Stats
        $totalEmployees = Employee::count();
        $todayAttendance = Attendance::whereDate('date_here', $today)->count();
        $monthlyPayroll = Payroll::whereBetween('pay_month', [$startOfMonth, $endOfMonth])->sum('amount');
        $pendingLeaves = Leave::where('status', 'pending')->orWhere('status', 'ລໍຖ້າອະນຸມັດ')->count();

        // 2. Department Distribution (for Doughnut Chart)
        $deptStats = Department::withCount('employees')
            ->get()
            ->map(function($dept) {
                return [
                    'name' => $dept->name,
                    'count' => $dept->employees_count
                ];
            });

        // 3. Last 6 Months Payroll Trend (for Line Chart)
        $payrollTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $sum = Payroll::whereYear('pay_month', $month->year)
                ->whereMonth('pay_month', $month->month)
                ->sum('amount');
            
            $payrollTrend[] = [
                'month' => $month->format('M Y'),
                'amount' => $sum
            ];
        }

        // 4. Attendance Stats for last 7 days
        $attendanceTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Attendance::whereDate('date_here', $date)->count();
            $attendanceTrend[] = [
                'date' => $date->format('d/m'),
                'count' => $count
            ];
        }

        return response()->json([
            'stats' => [
                'employees' => $totalEmployees,
                'attendance' => $todayAttendance,
                'payroll' => $monthlyPayroll,
                'leaves' => $pendingLeaves,
            ],
            'deptStats' => $deptStats,
            'payrollTrend' => $payrollTrend,
            'attendanceTrend' => $attendanceTrend
        ]);
    }
}
