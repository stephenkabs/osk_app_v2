@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Package Subscribers</h4>
            <p class="text-muted mb-0">
                Overview of all active package subscriptions
            </p>
        </div>
    </div>

    <div class="card border-0 rounded-4 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light text-uppercase small text-muted">
                    <tr>
                        <th>User</th>
                        <th>WhatsApp</th>
                        <th>Package</th>
                        <th>Started</th>
                        <th>Days Remaining</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($users as $user)
                    @php
                        $package = $user->package;
                        $start   = $user->package_started_at;
                        $days    = $package && $start
                            ? max(
                                0,
                                \Carbon\Carbon::parse($start)
                                    ->addDays($package->duration_days)
                                    ->diffInDays(now(), false) * -1
                              )
                            : null;
                    @endphp

                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $user->name }}</div>
                            <small class="text-muted">{{ $user->email }}</small>
                        </td>

                        <td>
                            {{ $user->whatsapp_phone ?? '—' }}
                        </td>

                        <td>
                            <span class="badge bg-primary rounded-pill">
                                {{ $package->name ?? 'N/A' }}
                            </span>
                        </td>

                        <td>
                            {{ optional($start)->format('d M Y') ?? '—' }}
                        </td>

                        <td>
                            @if($days !== null)
                                @if($days > 7)
                                    <span class="badge bg-success">{{ $days }} days</span>
                                @elseif($days > 0)
                                    <span class="badge bg-warning">{{ $days }} days</span>
                                @else
                                    <span class="badge bg-danger">Expired</span>
                                @endif
                            @else
                                —
                            @endif
                        </td>

                        <td>
                            @if($days > 0)
                                <span class="text-success fw-semibold">Active</span>
                            @else
                                <span class="text-danger fw-semibold">Expired</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            No subscribers found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($users->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    @endif

</div>
@endsection
