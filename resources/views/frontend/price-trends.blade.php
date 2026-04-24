@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Dynamic Price Trends Chart</h1>
    <table class="table">
        <thead><tr><th>Month</th><th>Price</th></tr></thead>
        <tbody>
        @foreach($trends as $trend)
            <tr><td>{{ $trend['month'] }}</td><td>${{ number_format($trend['price']) }}</td></tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection