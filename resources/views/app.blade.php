@extends('layout')

@section('content')
<p>{{ Auth::user()->name }}</p>
@if(Auth::check())
@include('layout.menu')
@endif
<div class="container-fluid p-0" id="app">

</div>
@endsection

@push('js')

<script src="{{ mix('js/app.js') }}"></script>

@endpush
