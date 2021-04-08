@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Student Registration') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('registerStudent') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="student_number" class="col-md-4 col-form-label text-md-right">{{ __('Student Number') }}</label>

                            <div class="col-md-6">
                                <input id="student_number" type="number" class="form-control{{ $errors->has('student_number') ? ' is-invalid' : '' }}" name="student_number" value="{{ old('student_number') }}" autocomplete="student_number">

                                @if ($errors->has('student_number'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('student_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                                <label for="security_question" class="col-md-4 col-form-label text-md-right">{{ __('Security Question') }}</label>
    
                                <div class="col-md-6">
                                    <input id="security_question" type="text" class="form-control{{ $errors->has('security_question') ? ' is-invalid' : '' }}" name="security_question" value="{{ old('security_question') }}" required autocomplete="security_question" autofocus >
    
                                    @if ($errors->has('security_question'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('security_question') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                    <label for="security_answer" class="col-md-4 col-form-label text-md-right">{{ __('Security answer') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="security_answer" type="text" class="form-control{{ $errors->has('security_answer') ? ' is-invalid' : '' }}" name="security_answer" value="{{ old('security_answer') }}" required autocomplete="security_answer" autofocus >
        
                                        @if ($errors->has('security_answer'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('security_answer') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" autocomplete="new-password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
