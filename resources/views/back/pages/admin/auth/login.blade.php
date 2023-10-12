@extends('back.layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Admin Login')
@section('content')
    <div class="login-box bg-white box-shadow border-radius-10">
        <div class="login-title">
            <h2 class="text-center text-primary">Admin Login</h2>
        </div>
        <form id="login_form">
            @csrf
            <div class="alert-div"></div>
            <div class="input-group custom">
                <input type="text" class="form-control form-control-lg form_field" placeholder="Username" name="login_id"
                    id="login_id" value="{{ Cookie::get('laravecom_admin_email') ? Cookie::get('laravecom_admin_email') : "" }}">
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                </div>
            </div>
            <div class="d-block text-danger errors" style="font-size: 14px; font-weight: 500;" id="login_id_error"></div>
            <div class="input-group custom">
                <input type="password" class="form-control form-control-lg form-field" placeholder="**********"
                    name="password" id="password" value="{{ Cookie::get('laravecom_admin_password') ? Cookie::get('laravecom_admin_password') : "" }}">
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                </div>
            </div>
            <div class="d-block text-danger errors" style="font-size: 14px; font-weight: 500;" id="password_error"></div>
            <div class="row pb-30">
                <div class="col-6">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="remember">
                        <label class="custom-control-label" for="remember">Remember</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="forgot-password">
                        <a href="forgot-password.html">Forgot Password</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="input-group mb-0">
                        <!--
                                                    use code for form submit
                                                    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
                                                -->
                        <button class="btn btn-primary btn-lg btn-block" type="submit">Sign In</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            $(document).on('submit', '#login_form', function(e) {
                e.preventDefault();

                let form = new FormData(this);
                form.append('_token', csrfToken, );

                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.login_handler') }}',
                    data: form,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(response) {
                        $('.alert-div').html('');
                        console.log(response);
                        if (response.status === 'success') {
                            localStorage.setItem('status', 'welcome');
                            localStorage.setItem('message', response.message);
                            window.location.href = '{{ route('admin.home') }}';
                        } else if (response.status === 'error') {
                            $('.errors').text('');
                            $('.form-field').removeClass('border-danger');
                            $.each(response.message, function(field, errorMessage) {
                                $('#' + field + '_error').css({
                                    "margin-top": "-25px",
                                    "margin-bottom": "15px"
                                });
                                $('#' + field + '_error').text(errorMessage);
                                $('#' + field).addClass('border-danger');
                            })
                        } else if (response.status === 'alert') {
                            $('.alert-div').append(`<div class="alert alert-danger alert-dismissible fade show" id="error_alert" role="alert">
                                ` +
                                response.message +
                                `
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-top: -3px;">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`)
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        })
    </script>
@endsection
