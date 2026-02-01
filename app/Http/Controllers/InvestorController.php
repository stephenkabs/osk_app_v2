<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Investment;
use App\Models\InvestmentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvestorController extends Controller
{
    /* =======================
     | INVESTOR DASHBOARD
     |=======================*/
    public function index(Request $request)
    {
        $user = Auth::user();

        // Investor profile gate
        $partner = $user->partner ?? null;

        if (!$partner) {
            return redirect()
                ->route('partners.create')
                ->with('warning', 'Please create your investor profile first.');
        }

        if ($partner->status !== 'approved') {
            return redirect()
                ->route('partners.show', $partner->id)
                ->with('warning', 'Your investor profile is pending approval.');
        }

        $properties = Property::latest()->get();

        $stats = [
            'confirmed_investments' => Investment::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->count(),

            'total_invested' => Investment::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->sum('total_amount'),
        ];

        return view('dashboard.investor', compact(
            'user',
            'partner',
            'properties',
            'stats'
        ));
    }

    /* =======================
     | VIEW PROPERTY (INVEST)
     |=======================*/
    public function showProperty(Property $property)
    {
        $user = Auth::user();

        $availableShares = $property->availableShares();
        $pricePerShare   = (float) $property->qbo_unit_price;

        return view('investor.property', compact(
            'user',
            'property',
            'availableShares',
            'pricePerShare'
        ));
    }

    /* =======================
     | CREATE INVESTMENT
     | (RESERVE SHARES)
     |=======================*/
    public function storeInvestment(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'property_id' => ['required', 'exists:properties,id'],
            'shares'      => ['required', 'integer', 'min:1'],
        ]);

        $property = Property::findOrFail($data['property_id']);

        // 🔐 Prevent overselling
        $availableShares = $property->availableShares();

        if ($data['shares'] > $availableShares) {
            return back()->withErrors([
                'shares' => "Only {$availableShares} shares are available."
            ]);
        }

        $pricePerShare = (float) $property->qbo_unit_price;
        $totalAmount   = $pricePerShare * $data['shares'];

        $investment = Investment::create([
            'user_id'         => $user->id,
            'property_id'     => $property->id,
            'shares'          => $data['shares'],
            'price_per_share' => $pricePerShare,
            'total_amount'    => $totalAmount,
            'status'          => 'pending',
            'qbo_sync_status' => 'pending',
        ]);

        return redirect()
            ->route('investor.properties.show', $property->id)
            ->with('success', 'Investment created. Proceed to payment.');
    }

    /* =======================
     | DUMMY PAYMENT (NO GATEWAY)
     |=======================*/
    public function dummyPay(Investment $investment)
    {
        $user = Auth::user();

        abort_unless($investment->user_id === $user->id, 403);

        if ($investment->status !== 'pending') {
            return back()->withErrors([
                'payment' => 'This investment is not payable.'
            ]);
        }

        DB::transaction(function () use ($investment, $user) {

            $property = Property::lockForUpdate()->find($investment->property_id);

            // 🔐 Re-check shares at confirmation time
            $availableShares = $property->availableShares();

            if ($investment->shares > $availableShares) {
                throw new \Exception('Not enough shares available.');
            }

            // Record dummy payment
            InvestmentPayment::create([
                'investment_id' => $investment->id,
                'user_id'       => $user->id,
                'amount'        => $investment->total_amount,
                'currency'      => 'ZMW',
                'method'        => 'manual',
                'gateway_status'=> 'success',
                'gateway_payload' => [
                    'note' => 'Dummy payment confirmed (testing mode)'
                ],
            ]);

            // Confirm investment
            $investment->update([
                'status'        => 'confirmed',
                'confirmed_at'  => now(),
                'qbo_sync_status' => 'pending',
            ]);

            // ❌ DO NOT reduce qbo_qty_on_hand here
            // That will be handled by QBO sync job later
        });

        return redirect()
            ->route('investor.dashboard')
            ->with('success', 'Investment confirmed (dummy payment).');
    }
}
