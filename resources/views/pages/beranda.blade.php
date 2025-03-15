@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div>
        @include('partials.carousel')
    </div>

    <div>
        @include('partials.about')
    </div>

    <div>
        @include('partials.unggulan')
    </div>

    {{-- Main Section --}}
    <div class="container grid grid-cols-3 gap-4 max-w-screen-2xl mx-auto my-3 px-3">
        <div class="col-span-2">
            <section class="border">
                @include('partials.carousel')
            </section>
        </div>
        <div>
            <section class="border">
                @include('partials.carousel')
            </section>
        </div>
    </div>
@endsection
