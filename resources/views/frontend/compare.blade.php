@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Property Comparison</h1>
    <form method="GET" action="{{ route('property.compare') }}">
        <label>Select properties to compare (2–4):</label>
        <select name="properties[]" multiple required class="form-control">
            @foreach($allProperties as $property)
                <option value="{{ $property->id }}" @if(in_array($property->id, request('properties', []))) selected @endif>{{ $property->title }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary mt-2">Compare</button>
    </form>
    @if(count($properties) >= 2)
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Feature</th>
                @foreach($properties as $property)
                    <th>{{ $property->title }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Price</td>
                @foreach($properties as $property)
                    <td @if($property->price == min($properties->pluck('price')->toArray())) style="background:#dff0d8;" @endif>{{ $property->price }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Area</td>
                @foreach($properties as $property)
                    <td @if($property->area == max($properties->pluck('area')->toArray())) style="background:#f2dede;" @endif>{{ $property->area }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Rating</td>
                @foreach($properties as $property)
                    <td>{{ $property->rating ?? 'N/A' }}</td>
                @endforeach
            </tr>
            <!-- Add more features as needed -->
        </tbody>
    </table>
    <div class="mt-3">
        <label>Sort by:</label>
        <a href="?sort=price&properties[]={{ implode('&properties[]=', request('properties', [])) }}" class="btn btn-sm btn-outline-primary">Price</a>
        <a href="?sort=area&properties[]={{ implode('&properties[]=', request('properties', [])) }}" class="btn btn-sm btn-outline-primary">Area</a>
        <a href="?sort=rating&properties[]={{ implode('&properties[]=', request('properties', [])) }}" class="btn btn-sm btn-outline-primary">Rating</a>
    </div>
    @endif
</div>
@endsection