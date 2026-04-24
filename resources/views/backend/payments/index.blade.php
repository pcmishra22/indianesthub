@extends('backend.layout')
@section('title', 'Payments')
@section('content')
<h1>Payments List</h1>
<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered table-striped align-middle mb-0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Dealer</th>
						<th>Property ID</th>
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
						<td>{{ $payment->dealer_id }}</td>
						<td>{{ $payment->property_id ?? '-' }}</td>
						<td>{{ $payment->plan_name }}</td>
						<td>{{ $payment->amount }}</td>
						<td>
							@if($payment->status)
								<span class="badge bg-success">Paid</span>
							@else
								<span class="badge bg-secondary">Pending</span>
							@endif
						</td>
						<td>{{ $payment->transaction_id }}</td>
						<td>{{ $payment->payment_method }}</td>
						<td>
							<!-- Actions (e.g., Approve) -->
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="8" class="text-center">No payments found.</td>
					</tr>
				@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
