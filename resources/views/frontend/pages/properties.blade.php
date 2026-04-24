@extends('frontend.layout')
@php use Illuminate\Support\Str; @endphp
@section('title', 'Properties')
@section('content')
<div class="page-title light-background">
	<div class="container d-lg-flex justify-content-between align-items-center">
		<h1 class="mb-2 mb-lg-0">Properties</h1>
		<nav class="breadcrumbs">
			<ol>
				<li><a href="{{ url('/') }}">Home</a></li>
				<li class="current">Properties</li>
			</ol>
		</nav>
	</div>
</div>


@endsection