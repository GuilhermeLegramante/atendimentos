@extends('reports.master')

@php
$this_title = ucwords(strtolower($title));
@endphp

@section('titulo', $this_title)

@section('body')

<body>
    @yield('header')

    @yield('content')

    @yield('footer')
</body>
@endsection