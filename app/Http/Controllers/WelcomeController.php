<?php
namespace App\Http\Controllers;

use App\Models\NotificationModel;
use App\Models\PortalModel;
use App\Models\ProformaModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        if (! Auth::check()) {
            return $this->welcome();
        }

        $user = Auth::user();
        $role = $user->role_id;

        return match ($role) {
            77 => $this->citizenDashboard($user),
            1 => $this->hodAssistantDashboard($user),
            2, 3, 4, 9 => $this->otherStaffDashboard($user),
            999, 5, 6, 8 => $this->superAdminDashboard($user),
            default => $this->welcome(),
        };
    }

    private function welcome()
    {
        $portal        = $this->getPortalData();
        $notifications = $this->getNotifications();

        return view('welcome', array_merge($portal, $notifications));
    }

    private function citizenDashboard($user)
    {
        $portal        = $this->getPortalData();
        $notifications = $this->getNotifications();

        $proforma = ProformaModel::where('uploaded_id', $user->id)->first();

        $status = match ($proforma->status ?? null) {
            null    => "Not yet Applied",
            0       => "You have not submitted your application.... Incomplete Application",
            1       => "Application Submitted",
            2       => "Verified",
            3, 4, 5    => "Under Process",
            default => "Appointment Given",
        };

        return view('admin/dashboard', array_merge($portal, $notifications, compact('user', 'status')));
    }

    private function hodAssistantDashboard($user)
    {
        return $this->staffDashboard($user, true);
    }

    private function otherStaffDashboard($user)
    {
        return $this->staffDashboard($user, false);
    }

    private function superAdminDashboard($user)
    {
        return $this->staffDashboard($user, false, true);
    }

    /**
     * Shared logic for staff/super admin dashboards
     */
    private function staffDashboard($user, $hod = false, $super = false)
    {
        $portal        = $this->getPortalData();
        $notifications = $this->getNotifications();

        $query = ProformaModel::query();
        if (! $super) {
            $query->where('dept_id', $user->dept_id);
        }

        $totalApplicants       = $query->count();
        $ProInCompleted        = (clone $query)->where('status', 0)->count();
        $verificationCompleted = (clone $query)->where('status', 2)->count();
        $notYetVerified        = (clone $query)->where('status', 1)->count();
        $underProcess          = (clone $query)->whereNotIn('status', [0, 6])->count();
        $ProCompleted          = (clone $query)->where('status', 6)->count();

        return view('admin/dashboard', array_merge(
            $portal,
            $notifications,
            compact(
                'user',
                'ProInCompleted',
                'notYetVerified',
                'verificationCompleted',
                'totalApplicants',
                'underProcess',
                'ProCompleted'
            )
        ));
    }

    /**
     * Helper: Portal common data
     */
    private function getPortalData()
    {
        $portal = PortalModel::find(1);

        return [
            'getProjectShortForm' => $portal->short_form_name,
            'getSoftwareName'     => $portal->software_name,
            'getDeptName'         => $portal->department_name,
            'getGovtName'         => $portal->govt_name,
            'getDeveloper'        => $portal->developed_by,
        ];
    }

    /**
     * Helper: Notification data
     */
    private function getNotifications()
    {
        return [
            'notificationsArray' => NotificationModel::all()->toArray(),
            'notifications'      => NotificationModel::latest('created_at')->paginate(5),
        ];
    }
}
