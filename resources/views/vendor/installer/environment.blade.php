@extends('vendor.installer.layouts.master')
@section('container')
    <form method="post" action="{{ route('LaravelInstaller::environmentSave') }}" id="env-form">
        <div class="card card-md">
            <div class="card-body py-4 p-sm-5">
                <h2 class="mb-5 text-center">Database Setup</h2>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">DB Hostname</label>
                            <input type="text" name="hostname" class="form-control"
                                placeholder="Enter DB Hostname, eg: localhost" autofocus>
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">DB Username</label>
                            <input type="text" name="username" class="form-control"
                                placeholder="Enter DB Username, eg: root">
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">DB Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter DB Password">
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">Database Name</label>
                            <input type="text" name="database" class="form-control" placeholder="Enter Database Name">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-center mt-3">
            <div class="col-4">
                <div class="progress progress-1">
                    <div class="progress-bar" style="width: 40%;" role="progressbar" aria-valuenow="50" aria-valuemin="0"
                        aria-valuemax="100" aria-label="40% Complete">
                        <span class="visually-hidden">40% Complete</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="btn-list justify-content-end">
                    <button type="submit" onclick="checkEnv();return false" class="btn btn-primary btn-2" id="nextButton">
                        Next </button>
                </div>
            </div>
        </div>
    </form>
    <script src="{{ asset('installer/js/jQuery-2.2.0.min.js') }}"></script>
    <script src="{{ asset('installer/froiden-helper/helper.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        function checkEnv() {
            document.getElementById('nextButton').disabled = true;
            $.easyAjax({
                url: "{!! route('LaravelInstaller::environmentSave') !!}",
                type: "GET",
                data: $("#env-form").serialize(),
                container: "#env-form",
                messagePosition: "inline",
                complete: function(response) {
                    document.getElementById('nextButton').disabled = false;
                }
            });
        }
    </script>
@stop
