<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use App\Mail\PartnerThankYouMail;
use App\Mail\PartnerApprovedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::latest()->paginate(15);
        return view('partners.index', compact('partners'));
    }

    public function create()
    {
        return view('partners.create');
    }


        public function investor_kyc()
    {
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }

        $id = Auth::user()->id;
        $profileData = User::find($id);
    // $units = Unit::all();

        return view('pages.investor_kyc', compact('profileData'));
    }


public function updateStatus(Request $request, Partner $partner)
{
    $request->validate([
        'status' => 'required|in:pending,approved,rejected',
    ]);

    $previousStatus = $partner->status;
    $partner->update(['status' => $request->status]);

    // ✅ Only sync when status becomes "approved"
    if ($previousStatus !== 'approved' && $partner->status === 'approved') {

        // -- Prep/sanitize payload a bit
        $payload = [
            'partner_id' => $partner->id,
            'name'       => $partner->name,
            'email'      => strtolower(trim($partner->email)),
            'phone'      => str_replace('o', '0', (string) $partner->phone_number),
            'nrc_no'     => $partner->nrc_no,
            'address'    => $partner->previous_address,
        ];

        $url = config('services.make.webhook_url_investor'); // <-- your Customer Upsert webhook

        if (!$url) {
            Log::error('QuickBooks sync failed: services.make.webhook_url_customer not set');
        } else {
            try {
                $response = Http::timeout(20)
                    ->acceptJson()
                    ->post($url, $payload);

                // Log the full response for visibility
                Log::info("QB webhook response for partner {$partner->id}", [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                    'json'   => $response->json(),
                ]);

                $qbId = data_get($response->json(), 'quickbooks_customer_id');

                if ($response->ok() && $qbId) {
                    $partner->update(['quickbooks_customer_id' => $qbId]);
                } else {
                    Log::warning("QB customer upsert returned no ID for partner {$partner->id}", [
                        'status' => $response->status(),
                        'body'   => $response->body(),
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error("QB customer upsert failed for partner {$partner->id}: {$e->getMessage()}");
            }
        }

        // ✅ Send congratulations email (only if we have a deliverable address)
        // if (optional($partner->user)->email) {
        //     Mail::to($partner->user->email)->send(new PartnerApprovedMail($partner));
        // } else {
        //     Log::warning("Partner {$partner->id} approved but no user email found for notification.");
        // }
    }

    return redirect()
        ->route('partners.index')
        ->with('status', 'Partner status updated.');
}

public function syncWithQBO(Partner $partner)
{
    // Reuse your existing payload & webhook logic
    $payload = [
        'partner_id' => $partner->id,
        'name'       => $partner->name,
        'email'      => strtolower(trim($partner->email)),
        'phone'      => str_replace('o', '0', (string) $partner->phone_number),
        'nrc_no'     => $partner->nrc_no,
        'address'    => $partner->previous_address,
    ];

    $url = config('services.make.webhook_url_investor');

    try {
        $response = Http::timeout(20)->acceptJson()->post($url, $payload);

        $qbId = data_get($response->json(), 'quickbooks_customer_id');
        if ($response->ok() && $qbId) {
            $partner->update(['quickbooks_customer_id' => $qbId]);
            return back()->with('status', "Partner synced to QuickBooks (#{$qbId}).");
        } else {
            return back()->withErrors(['qbo' => 'Failed to sync with QuickBooks.']);
        }
    } catch (\Throwable $e) {
        Log::error("QuickBooks sync failed for partner {$partner->id}: {$e->getMessage()}");
        return back()->withErrors(['qbo' => 'Sync failed. Please try again later.']);
    }
}



public function downloadAgreement(Partner $partner)
{
    if (!$partner->agreement_signature) {
        return redirect()->back()->with('error', 'This partner has not signed an agreement yet.');
    }

    $signaturePath = storage_path('app/public/' . $partner->agreement_signature);

    $data = [
        'partner' => $partner,
        'agreementText' => $partner->agreement_text,
        'agreementVersion' => $partner->agreement_version,
        'agreementDate' => $partner->agreement_accepted_at,
        'signature' => $signaturePath,
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('partners.agreement_pdf', $data)->setPaper('a4');
    return $pdf->download("agreement-{$partner->slug}.pdf");
}






public function store(Request $request)
{
    $data = $request->validate([
        'name'             => ['required', 'string', 'max:255'],
        'email'            => ['nullable', 'string', 'max:255'],
        'nrc_no'           => ['required', 'string', 'max:100'],
        'phone_number'     => ['nullable', 'string', 'max:50'],
        'previous_address' => ['nullable', 'string', 'max:255'],
        'nrc_image'        => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        'status'           => ['nullable', 'in:pending,approved,rejected'],
        'agree_terms'      => ['accepted'],
        'signature_data'   => ['required', 'string'],
    ]);

    $data['user_id'] = auth()->id();
    $data['email']   = auth()->user()->email ?? null; // ✅ ensure email comes from logged-in user

    // Upload NRC file if available
    if ($request->hasFile('nrc_image')) {
        $data['nrc_image'] = $request->file('nrc_image')->store('partners/nrcs', 'public');
    }

    // Unique slug from name + nrc_no
    $base = Str::slug($data['name'] . '-' . $data['nrc_no']);
    $slug = $base;
    $i = 1;
    while (Partner::where('slug', $slug)->exists()) {
        $slug = $base . '-' . $i++;
    }
    $data['slug'] = $slug;

    // Agreement fields
    $data['agreement_accepted']    = true;
    $data['agreement_accepted_at'] = now();
    $data['agreement_version']     = config('partner_agreement.version', 'v1.0');
    $data['agreement_text']        = config('partner_agreement.text', 'Default partner agreement text');
    $data['agreement_ip']          = $request->ip();
    $data['agreement_user_agent']  = substr((string) $request->userAgent(), 0, 255);

    // Save signature PNG
    [$meta, $b64] = explode(',', $request->input('signature_data'), 2);
    $png = base64_decode($b64);
    $sigPath = 'partners/signatures/' . $data['slug'] . '.png';
    Storage::disk('public')->put($sigPath, $png);
    $data['agreement_signature'] = $sigPath;

    unset($data['agree_terms'], $data['signature_data']);

    // ✅ Create Partner in Laravel
    $partner = Partner::create($data);


    // Mail::to(auth()->user()->email)->send(new PartnerThankYouMail($partner));

    return redirect()->back()
        ->with('success', 'Thank you for registering this Partner.');
}



    // {
    //     $data = $request->validate([
    //         'name'             => ['required', 'string', 'max:255'],
    //         'email'             => ['required', 'string', 'max:255'],
    //         'nrc_no'           => ['required', 'string', 'max:100'],
    //         'phone_number'     => ['nullable', 'string', 'max:50'],
    //         'previous_address' => ['nullable', 'string', 'max:255'],
    //         'nrc_image'        => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
    //         'status'           => ['nullable', 'in:pending,approved,rejected'], // ✅
    //         // Online agreement (no upload)
    //         'agree_terms'      => ['accepted'],
    //         'signature_data'   => ['required', 'string'], // data:image/png;base64,...
    //     ]);

    //     $data['user_id'] = auth()->id();

    //     if ($request->hasFile('nrc_image')) {
    //         $data['nrc_image'] = $request->file('nrc_image')->store('partners/nrcs', 'public');
    //     }

    //     // Unique slug from name + nrc_no
    //     $base = Str::slug($data['name'] . '-' . $data['nrc_no']);
    //     $slug = $base;
    //     $i = 1;
    //     while (Partner::where('slug', $slug)->exists()) {
    //         $slug = $base . '-' . $i++;
    //     }
    //     $data['slug'] = $slug;

    //     // Agreement fields
    //     $data['agreement_accepted']    = true;
    //     $data['agreement_accepted_at'] = now();
    //     $data['agreement_version']     = config('partner_agreement.version', 'v1.0');
    //     $data['agreement_text']        = config('partner_agreement.text', 'Default partner agreement text');
    //     $data['agreement_ip']          = $request->ip();
    //     $data['agreement_user_agent']  = substr((string) $request->userAgent(), 0, 255);

    //     // Save signature PNG
    //     [$meta, $b64] = explode(',', $request->input('signature_data'), 2);
    //     $png = base64_decode($b64);
    //     $sigPath = 'partners/signatures/' . $data['slug'] . '.png';
    //     Storage::disk('public')->put($sigPath, $png);
    //     $data['agreement_signature'] = $sigPath;

    //     unset($data['agree_terms'], $data['signature_data']);

    //     $partner = Partner::create($data);

    //     // Send thank-you email
    //     Mail::to(auth()->user()->email)->send(new PartnerThankYouMail($partner));

    //     return view('partners.thank_update', compact('partner'))
    //         ->with('message', 'Thank you for registering this Partner.');
    // }

public function show(Partner $partner)
{
    $user = auth()->user();

    // Allow admin to see any partner
    if ($user->hasRole('admin')) {
        $partner->load(['payments' => fn($q) => $q->where('status', '!=', 'failed')
                                                 ->with(['division.property', 'unit.property'])
                                                 ->latest()]);
        return view('partners.show', compact('partner'));
    }

    // If the user is NOT an admin:
    // Must have investor role AND must own this partner profile
    if (! $user->hasRole('investor') || ! $user->partner || $user->partner->id !== $partner->id) {
        abort(403, 'Unauthorized');
    }

    $partner->load(['payments' => fn($q) => $q->where('status', '!=', 'failed')
                                             ->with(['division.property', 'unit.property'])
                                             ->latest()]);
    return view('partners.show', compact('partner'));
}





    public function edit(Partner $partner)
    {
        return view('partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'name'             => ['sometimes', 'string', 'max:255'],
            'nrc_no'           => ['sometimes', 'string', 'max:100'],
            'phone_number'     => ['sometimes', 'nullable', 'string', 'max:50'],
            'previous_address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'nrc_image'        => ['sometimes', 'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],

            // Optional re-accept on edit
            'agree_terms'      => ['sometimes', 'accepted'],
            'signature_data'   => ['sometimes', 'string'],
        ]);

        $data['user_id'] = auth()->id();

        if ($request->hasFile('nrc_image')) {
            if ($partner->nrc_image) {
                Storage::disk('public')->delete($partner->nrc_image);
            }
            $data['nrc_image'] = $request->file('nrc_image')->store('partners/nrcs', 'public');
        }

        if (isset($data['name']) || isset($data['nrc_no'])) {
            $name = $data['name'] ?? $partner->name;
            $nrc  = $data['nrc_no'] ?? $partner->nrc_no;
            $base = Str::slug($name . '-' . $nrc);
            $slug = $base;
            $i = 1;
            while (Partner::where('slug', $slug)->where('id', '!=', $partner->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $data['slug'] = $slug;
        }

        if ($request->filled('signature_data') && $request->boolean('agree_terms')) {
            [$meta, $b64] = explode(',', $request->input('signature_data'), 2);
            $png = base64_decode($b64);
            $sigPath = 'partners/signatures/' . ($data['slug'] ?? $partner->slug) . '.png';
            Storage::disk('public')->put($sigPath, $png);

            $data['agreement_accepted']    = true;
            $data['agreement_accepted_at'] = now();
            $data['agreement_version']     = config('partner_agreement.version', 'v1.0');
            $data['agreement_text']        = config('partner_agreement.text', 'Default partner agreement text');
            $data['agreement_ip']          = $request->ip();
            $data['agreement_user_agent']  = substr((string) $request->userAgent(), 0, 255);
            $data['agreement_signature']   = $sigPath;
        }

        unset($data['agree_terms'], $data['signature_data']);

        $partner->update($data);


        $previousStatus = $partner->status ?? 'pending'; // ✅ safe default

        $partner->update($data);

        // if ($previousStatus !== 'approved' && $partner->status === 'approved') {
        //     Mail::to($partner->user->email)->send(new PartnerApprovedMail($partner));
        // }


        return redirect()->route('partners.show', $partner)
            ->with('status', 'Partner updated.');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->nrc_image) Storage::disk('public')->delete($partner->nrc_image);
        if ($partner->agreement_signature) Storage::disk('public')->delete($partner->agreement_signature);
        $partner->delete();

        return redirect()->route('partners.index')->with('status', 'Partner deleted.');
    }
}
