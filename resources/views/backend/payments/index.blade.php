@extends('backend.layout')
@section('title', 'Payments')
@section('content')
<h1 class="h3 mb-3">Payments</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
@endif

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered table-striped align-middle mb-0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Dealer</th>
						<th>Property</th>
						<th>Plan</th>
						<th>Amount</th>
						<th>Status</th>
						<th>Transaction ID</th>
						<th>Method</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				@forelse($payments as $payment)
					<tr>
						<td>{{ $payment->id }}</td>
						<td>{{ $payment->dealer?->full_name ?? '#'.$payment->dealer_id }}</td>
						<td>
							@if($payment->property)
								<a href="{{ route('admin.properties.show', $payment->property_id) }}">{{ \Illuminate\Support\Str::limit($payment->property->title, 30) }}</a>
							@else
								{{ $payment->property_id ?? '-' }}
							@endif
						</td>
						<td>{{ $payment->plan_name }}</td>
						<td>₹{{ number_format($payment->amount, 2) }}</td>
						<td>
							@if($payment->status === 'completed')
								<span class="badge bg-success">Completed</span>
							@elseif($payment->status === 'failed')
								<span class="badge bg-danger">Failed</span>
							@else
								<span class="badge bg-secondary">Pending</span>
							@endif
						</td>
						<td>{{ $payment->transaction_id }}</td>
						<td>{{ $payment->payment_method }}</td>
						<td>
							@if($payment->status !== 'completed')
								<form action="{{ route('admin.payments.approve', $payment->id) }}" method="POST" class="d-inline"
									  onsubmit="return confirm('Approve this payment? This will activate the property listing.');">
									@csrf
									<button type="submit" class="btn btn-sm btn-success">
										<i class="align-middle" data-feather="check"></i> Approve
									</button>
								</form>
							@else
								<span class="text-muted small">—</span>
							@endif
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="9" class="text-center">No payments found.</td>
					</tr>
				@endforelse
				</tbody>
			</table>
		</div>
		@if(method_exists($payments, 'links'))
			<div class="mt-3">{{ $payments->links() }}</div>
		@endif
	</div>
</div>
@endsection
