@extends('reports.master')

@php
    $this_title = mb_strtoupper($title, 'UTF-8');
@endphp

@section('titulo', $this_title)

@section('body')

    <body>
        @yield('header')

        @yield('content')

        @yield('footer')
    </body>
@endsection
