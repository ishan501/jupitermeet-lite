<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [];

        $users = User::where('role', '<>', 'admin')->get();

        $freeUsers = $users->filter(function ($user) {
            return $user->plan_payment_gateway == '';
        });

        $paidUsers = $users->filter(function ($user) {
            return $user->plan_payment_gateway != '';
        });

        $data['meeting'] = Meeting::count();
        $data['user'] = $users->count();
        $data['income'] = Payment::sum('amount');
        $data['freeUsers'] = count($freeUsers);
        $data['paidUsers'] = count($paidUsers);

        $incomeGraph = Payment::select(DB::raw("SUM(amount) as income"), DB::raw("MONTH(created_at) as month"))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('income', 'month')
            ->toArray();

        $userGraph = User::select(DB::raw("count(*) as count"), DB::raw("MONTH(created_at) as month"))
            ->where('role', 'end-user')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $meetingGraph = Meeting::select(DB::raw("count(*) as count"), DB::raw("MONTH(created_at) as month"))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $data['montlyIncome'] = json_encode($incomeGraph);
        $data['userGraph'] = json_encode($userGraph);
        $data['meetingGraph'] = json_encode($meetingGraph);

        return view('admin.dashboard', [
            'page' => __('Dashboard'),
            'data' => $data,
        ]);
    }
}