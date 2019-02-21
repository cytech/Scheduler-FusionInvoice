@extends('layouts.master')

@section('head')
    @parent
    @include('Scheduler::partials._head')
    @include('Scheduler::partials._js_datetimepicker')

@endsection

{{--for FusionInvoice--}}
@section('javascript')

    @yield('javascript')

@endsection
