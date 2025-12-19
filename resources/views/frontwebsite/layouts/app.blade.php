@extends('layouts.app')

@section('content')
<div class="flex min-h-screen flex-col overflow-visible">
    @include('frontwebsite.partials.header')

    <main class="flex-1 overflow-visible">
        @yield('page')
    </main>

    @include('frontwebsite.partials.footer')
</div>
@endsection
