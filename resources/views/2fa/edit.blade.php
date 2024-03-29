@extends(config('laravel-2fa.blade-extends', 'layouts.app'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Update Two Factor Authentication') }}</div>

                <div class="card-body">
                    <form role="form" method="POST" action="{{ route('2fa.update') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="2fa_token" class="col-md-4 col-form-label text-md-right">{{ __('Verify old token') }}</label>

                            <div class="col-md-6">
                                <input id="old_2fa_token" type="text" class="form-control @error('old_2fa_token') is-invalid @enderror" name="old_2fa_token" required autocomplete="off" autofocus>

                                @error('old_2fa_token')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center col-md-12 mb-4">
                            <img src="data:image/png;base64,{{ $base64QrCode }}">
                        </div>

                        <div class="form-group row">
                            <label for="new_2fa_token" class="col-md-4 col-form-label text-md-right">{{ __('Verify new token') }}</label>

                            <div class="col-md-6">
                                <input id="new_2fa_token" type="text" class="form-control @error('new_2fa_token') is-invalid @enderror" name="new_2fa_token" required autocomplete="off" autofocus>

                                @error('new_2fa_token')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Validate') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <br><br>

            <div class="card">
                <div class="card-header">{{ __('Remove Two Factor Authentication') }}</div>

                <div class="card-body">
                    @if(TwoFactor::isRequired())
                        <div class="alert alert-warning">
                            {{ __('Two Factor Authentication is required for some of your permissions, removing Two Factor Authentication is not permitted.') }}
                        </div>
                    @else
                        <div class="alert alert-warning">
                            {{ __('Removing Two Factor Authentication will significantly decrease your account\'s security. This action is not recommended.') }}
                        </div>

                        <p>{{ __('To remove Two Factor Authentication from your account, confirm by filling in a verify token from the authenticator app:') }}</p>

                        <form role="form" method="POST" action="{{ route('2fa.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <div class="form-group row">
                                <label for="2fa_token" class="col-md-4 col-form-label text-md-right">{{ __('Verify token') }}</label>

                                <div class="col-md-6">
                                    <input id="2fa_token" type="text" class="form-control @error('2fa_token') is-invalid @enderror" name="2fa_token" required autocomplete="off" autofocus>

                                    @error('2fa_token')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-danger">
                                        {{ __('Remove Two Factor Authentication') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
