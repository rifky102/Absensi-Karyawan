<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->employeeDashboard();
    }

    /**
     * Admin dashboard with statistics
     */
    private function adminDashboard()
    {
        $totalUsers = User::where('is_active', true)->count();
        $totalGuru = User::where('role', 'guru')->where('is_active', true)->count();
        $totalAttendanceToday = Attendance::where('attendance_date', Carbon::today())->count();
        $presentToday = Attendance::where('attendance_date', Carbon::today())
            ->where('status', 'present')
            ->count();

        return view('dashboard.admin', compact('totalUsers', 'totalGuru', 'totalAttendanceToday', 'presentToday'));
    }

    /**
     * Employee dashboard
     */
    private function employeeDashboard()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('attendance_date', $today)
            ->first();

        $recentAttendances = Attendance::where('user_id', $user->id)
            ->orderByDesc('attendance_date')
            ->limit(5)
            ->get();

        $attendanceStats = [
            'present' => Attendance::where('user_id', $user->id)->where('status', 'present')->count(),
            'absent' => Attendance::where('user_id', $user->id)->where('status', 'absent')->count(),
            'late' => Attendance::where('user_id', $user->id)->where('status', 'late')->count(),
        ];

        return view('dashboard.employee', compact('todayAttendance', 'recentAttendances', 'attendanceStats'));
    }

    /**
     * Show reports page
     */
    public function reports()
    {
        return view('reports.index');
    }

    /**
     * Show attendance report
     */
    public function attendanceReport(Request $request)
    {
        $query = Attendance::with('user', 'location');

        if ($request->filled('date_from')) {
            $query->whereDate('attendance_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('attendance_date', '<=', $request->date_to);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderByDesc('attendance_date')->paginate(20);
        $users = User::where('is_active', true)->get();

        return view('reports.attendance', compact('attendances', 'users'));
    }
}
