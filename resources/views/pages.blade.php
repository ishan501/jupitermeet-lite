@extends('layouts.app')

@section('title', $pageTitle)

@section('styles')
    <style lang="scss">
        .ql-align-right {
            text-align: right;
        }

        .ql-align-center {
            text-align: center;
        }

        .ql-align-left {
            text-align: left;
        }

        .ql-align-justify {
            text-align: justify;
        }
    </style>
@endsection

@section('content')
    <div class="page jm-dashboard">
        @include('include.user.header')
        <div class="hero">
            <div class="container">
                <h1 class="hero-title">{{ $page->title }}</h1>
                <p class="hero-description hero-description-wide">
                    {!! $page->content !!}
                </p>
            </div>
        </div>
    </div>
@endsection
