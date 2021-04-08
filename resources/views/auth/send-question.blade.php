@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Enter your email') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('process-question') }}">
                        @csrf
                    <input type="hidden" name="email" value="{{$passwordReset->email}}">
                            <div class="form-group row">
                                    <label for="security_answer" class="col-md-4 col-form-label text-md-right">{{ __($passwordReset->question) }}</label>
        
                                    <div class="col-md-6">
                                        <input id="security_answer" type="text" class="form-control{{ $errors->has('security_answer') ? ' is-invalid' : '' }}" name="security_answer" value="{{ old('security_answer') }}" required autocomplete="security_answer" autofocus >
        
                                        @if ($errors->has('security_answer'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('security_answer') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
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
