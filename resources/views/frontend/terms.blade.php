@extends('frontend.layout')
@section('title', 'Terms of Service | ' . config('app.name'))
@section('meta_description', 'Review ' . config('app.name') . '\'s Terms of Service governing the use of our real estate portal for property listings, dealers, and users in Chandigarh Tricity.')
@section('canonical', url('/terms'))
@section('robots', 'index, follow')
@section('content')
@include('frontend.partials.terms')
@endsection
