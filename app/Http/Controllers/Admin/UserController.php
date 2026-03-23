<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()
            ->addSelect([
                'units_bought' => DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereColumn('orders.user_id', 'users.id')
                    ->whereIn('orders.status', ['ongoing', 'completed'])
                    ->selectRaw('COALESCE(SUM(order_items.quantity), 0)'),
            ])
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:admin,customer'],
        ]);

        $user->role = $request->role;
        $user->save();

        return back()->with('status', 'user-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $user->is_active = (bool) $request->is_active;
        $user->save();

        // If the user is being deactivated and is the currently authenticated user, log them out and redirect
        if (!$user->is_active && \Illuminate\Support\Facades\Auth::id() === $user->id) {
            \Illuminate\Support\Facades\Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('status', 'Your account has been deactivated.');
        }

        return back()->with('status', 'user-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('status', 'user-deleted');
    }
}
