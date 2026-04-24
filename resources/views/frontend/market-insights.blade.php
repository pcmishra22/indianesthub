@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Property Market Insights</h1>
    <ul>
        <li>Average Price: ${{ number_format($insights['average_price']) }}</li>
        <li>Total Properties: {{ $insights['total_properties'] }}</li>
        <li>Hot Areas: {{ implode(', ', $insights['hot_areas']) }}</li>
    </ul>
</div>
@endsection