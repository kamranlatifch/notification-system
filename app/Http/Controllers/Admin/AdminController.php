<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SystemNotification;
use Backpack\CRUD\app\Http\Controllers\CrudController;




class AdminController extends Controller
{
    public function loadAdminPanel(Request $request) // Use Request type for consistency
    {
        if (backpack_user()) {
            $user = backpack_user();
            $notifications = SystemNotification::where(function ($query) use ($user) {
                $query->where('to', 'like', '%' . $user->email . '%')
                    ->orWhere('cc', 'like', '%' . $user->email . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

            if ($notifications->isEmpty()) {
                // No notifications found, return the view with a message
                return view('admin.panel', ['notifications' => $notifications])
                    ->with('message', 'No notifications found.');
            }

            return view('admin.panel', ['notifications' => $notifications]);
        } else {
            // User is not authenticated, handle the case
            return redirect('admin/login');
        }
    }
}