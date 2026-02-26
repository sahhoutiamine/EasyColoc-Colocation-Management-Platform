<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ColocationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeMembership = $user->activeMembership();
        
        return view('colocations.index', compact('activeMembership'));
    }

    public function create()
    {
        if (Auth::user()->hasActiveMembership()) {
            return redirect()->route('colocations.index')
                ->with('error', 'You cannot create a new colocation while having an active membership.');
        }

        return view('colocations.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->hasActiveMembership()) {
            return redirect()->route('colocations.index')
                ->with('error', 'You cannot create a new colocation while having an active membership.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $colocation = Colocation::create($validated);

        Membership::create([
            'user_id' => Auth::id(),
            'colocation_id' => $colocation->id,
            'role' => 'OWNER',
            'join' => now(),
        ]);

        return redirect()->route('colocations.index')
            ->with('success', 'Colocation created successfully.');
    }

    public function edit(Colocation $colocation)
    {
        $membership = $colocation->memberships()
            ->where('user_id', Auth::id())
            ->whereNull('left')
            ->first();

        if (!$membership || $membership->role !== 'OWNER') {
            abort(403, 'Only the owner can edit this colocation.');
        }

        return view('colocations.edit', compact('colocation'));
    }

    public function update(Request $request, Colocation $colocation)
    {
        $membership = $colocation->memberships()
            ->where('user_id', Auth::id())
            ->whereNull('left')
            ->first();

        if (!$membership || $membership->role !== 'OWNER') {
            abort(403, 'Only the owner can update this colocation.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $colocation->update($validated);

        return redirect()->route('colocations.index')
            ->with('success', 'Colocation updated successfully.');
    }

    public function destroy(Colocation $colocation)
    {
        $membership = $colocation->memberships()
            ->where('user_id', Auth::id())
            ->whereNull('left')
            ->first();

        if (!$membership || $membership->role !== 'OWNER') {
            abort(403, 'Only the owner can delete this colocation.');
        }

        $colocation->recalculateBalances();

        // Reputation logic for all members
        foreach ($colocation->memberships()->whereNull('left')->get() as $m) {
            $hasDebt = $colocation->settlements()->where('from_user_id', $m->user_id)->where('is_paid', false)->exists();
            $m->user->increment('reputation', $hasDebt ? -1 : 1);
        }

        $colocation->update(['status' => 'CANCELLED']);
        $colocation->memberships()->whereNull('left')->update(['left' => now()]);

        return redirect()->route('colocations.index')
            ->with('success', 'Colocation cancelled and reputation updated for members.');
    }

    public function leave(Colocation $colocation)
    {
        $membership = $colocation->memberships()
            ->where('user_id', Auth::id())
            ->whereNull('left')
            ->first();

        if (!$membership) {
            abort(403, 'You are not a member of this colocation.');
        }

        if ($membership->role === 'OWNER') {
            return redirect()->route('colocations.index')
                ->with('error', 'Owner cannot leave the colocation. Delete it instead.');
        }

        // Check for debt and impute to owner if necessary
        $colocation->recalculateBalances();
        $debtAmount = $colocation->settlements()->where('from_user_id', Auth::id())->where('is_paid', false)->sum('amount');
        
        if ($debtAmount > 0) {
            // Redistribution: owner takes over the debt
            $owner = $colocation->memberships()->where('role', 'OWNER')->whereNull('left')->first();
            if ($owner && $owner->user_id !== Auth::id()) {
                \App\Models\Settlement::create([
                    'colocation_id' => $colocation->id,
                    'from_user_id' => Auth::id(), // Member balance increases
                    'to_user_id' => $owner->user_id, // Owner balance decreases
                    'amount' => $debtAmount,
                    'is_paid' => true,
                ]);
            }
        }

        Auth::user()->increment('reputation', $debtAmount > 0.01 ? -1 : 1);

        $membership->update(['left' => now()]);
        $colocation->recalculateBalances();

        return redirect()->route('colocations.index')
            ->with('success', 'You have left the colocation. Reputation updated.');
    }

    public function removeMember(Colocation $colocation, $userId)
    {
        $ownerMembership = $colocation->memberships()
            ->where('user_id', Auth::id())
            ->whereNull('left')
            ->first();

        if (!$ownerMembership || $ownerMembership->role !== 'OWNER') {
            abort(403, 'Only the owner can remove members.');
        }

        $memberMembership = $colocation->memberships()
            ->where('user_id', $userId)
            ->whereNull('left')
            ->first();

        if (!$memberMembership) {
            abort(404, 'Member not found.');
        }

        if ($memberMembership->role === 'OWNER') {
            return redirect()->back()
                ->with('error', 'Cannot remove the owner.');
        }

        // Debt imputation logic: If member has debt, it's transferred to owner
        $colocation->recalculateBalances();
        $debtAmount = $colocation->settlements()
            ->where('from_user_id', $userId)
            ->where('is_paid', false)
            ->sum('amount');

        if ($debtAmount > 0) {
            \App\Models\Settlement::create([
                'colocation_id' => $colocation->id,
                'from_user_id' => $userId, // Member balance increases
                'to_user_id' => Auth::id(), // Owner balance decreases
                'amount' => $debtAmount,
                'is_paid' => true,
            ]);
        }

        $memberMembership->user->increment('reputation', $debtAmount > 0.01 ? -1 : 1);

        $memberMembership->update(['left' => now()]);
        $colocation->recalculateBalances();

        return redirect()->back()
            ->with('success', 'Member removed. Their remaining debts were transferred to you.');
    }
}
