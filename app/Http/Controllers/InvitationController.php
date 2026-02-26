<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Invitation;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class InvitationController extends Controller
{
    public function create(Colocation $colocation)
    {
        $membership = $colocation->memberships()
            ->where('user_id', Auth::id())
            ->whereNull('left')
            ->first();

        if (!$membership || $membership->role !== 'OWNER') {
            abort(403, 'Only the owner can send invitations.');
        }

        return view('invitations.create', compact('colocation'));
    }

    public function store(Request $request, Colocation $colocation)
    {
        $membership = $colocation->memberships()
            ->where('user_id', Auth::id())
            ->whereNull('left')
            ->first();

        if (!$membership || $membership->role !== 'OWNER') {
            abort(403, 'Only the owner can send invitations.');
        }

        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $invitation = Invitation::create([
            'colocation_id' => $colocation->id,
            'email' => $validated['email'],
            'token' => Invitation::generateToken(),
            'expires_at' => now()->addDays(7),
        ]);

        return redirect()->route('colocations.index')
            ->with('success', 'Invitation sent successfully. It will appear in the recipient\'s notifications.');
    }

    public function show($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->status !== 'PENDING') {
            return redirect()->route('dashboard')
                ->with('error', 'This invitation has already been processed.');
        }

        if ($invitation->isExpired()) {
            return redirect()->route('dashboard')
                ->with('error', 'This invitation has expired.');
        }

        return view('invitations.show', compact('invitation'));
    }

    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->status !== 'PENDING') {
            return redirect()->route('dashboard')
                ->with('error', 'This invitation has already been processed.');
        }

        if ($invitation->isExpired()) {
            return redirect()->route('dashboard')
                ->with('error', 'This invitation has expired.');
        }

        if (Auth::user()->hasActiveMembership()) {
            return redirect()->route('dashboard')
                ->with('error', 'You cannot accept an invitation while having an active membership.');
        }

        Membership::create([
            'user_id' => Auth::id(),
            'colocation_id' => $invitation->colocation_id,
            'role' => 'MEMBER',
            'join' => now(),
        ]);

        $invitation->update(['status' => 'ACCEPTED']);

        return redirect()->route('colocations.index')
            ->with('success', 'You have joined the colocation.');
    }

    public function refuse($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->status !== 'PENDING') {
            return redirect()->route('dashboard')
                ->with('error', 'This invitation has already been processed.');
        }

        $invitation->update(['status' => 'REFUSED']);

        return redirect()->route('dashboard')
            ->with('success', 'Invitation refused.');
    }
}
