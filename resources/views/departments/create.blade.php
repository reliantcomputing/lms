@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Department Registration') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('saveDepartment') }}">
                        @csrf

                        <div class="form-group row">
                                <label for="first_name" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>
    
                                <div class="col-md-6">
                                    <input id="first_name" type="first_name" class="form-control{{ $errors->has('First name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name">
    
                                    @if ($errors->has('first_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                    <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Surname') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="surname" type="surname" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname') }}" required autocomplete="surname">
        
                                        @if ($errors->has('surname'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('surname') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                        <label for="staff_number" class="col-md-4 col-form-label text-md-right">{{ __('Staff number') }}</label>
            
                                        <div class="col-md-6">
                                            <input id="staff_number" type="staff_number" class="form-control{{ $errors->has('staff_number') ? ' is-invalid' : '' }}" name="staff_number" value="{{ old('staff_number') }}" required autocomplete="staff_number">
            
                                            @if ($errors->has('staff_number'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('staff_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                        <div class="form-group row">
                            <label for="department_name" class="col-md-4 col-form-label text-md-right">{{ __('Department') }}</label>

                            <div class="col-md-6">
                                <input id="department_name" type="text" class="form-control{{ $errors->has('department_name') ? ' is-invalid' : '' }}" name="department_name" value="{{ old('department_name') }}" required autocomplete="department_name" autofocus pattern="^[a-zA-Z_ ]*$" title="Please make sure the field doesn't contain numbers and special charactors.">

                                @if ($errors->has('department_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('department_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
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
