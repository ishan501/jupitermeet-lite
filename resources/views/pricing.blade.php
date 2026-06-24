@extends('layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('include.user.header')
    @include('include.user.pricing')

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $("#monthlyBtn").click(function() {
                $(this).addClass("active");
                $("#yearlyBtn").removeClass("active");

                $(".plan-month").removeClass("d-none");
                $(".plan-year").addClass("d-none");
            });

            $("#yearlyBtn").click(function() {
                $(this).addClass("active");
                $("#monthlyBtn").removeClass("active");

                $(".plan-year").removeClass("d-none");
                $(".plan-month").addClass("d-none");
            });

            $("#monthlyBtn").click();
        });
    </script>
@endsection
