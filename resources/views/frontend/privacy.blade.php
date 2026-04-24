@extends('frontend.layout')
@section('title', 'Privacy Policy | ' . config('app.name'))
@section('meta_description', 'Read ' . config('app.name') . '\'s Privacy Policy. Learn how we collect, use and protect your personal information on our Chandigarh Tricity real estate portal.')
@section('canonical', url('/privacy'))
@section('robots', 'index, follow')
@section('content')
@include('frontend.partials.privacy')
@endsection
