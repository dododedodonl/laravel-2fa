@extends(config('laravel-2fa.blade-extends', 'layouts.app'))

@section('content')
<div class="container">
    <div class="row col-md-offset-2">
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading text-center">{{ __('Update Two Factor Authentication') }}</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('2fa.update') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="2fa_token" class="col-md-4 col-form-label text-md-right">{{ __('Verify old token') }}</label>

                            <div class="col-md-6">
                                <input id="old_2fa_token" type="text" class="form-control @error('old_2fa_token') has-error @enderror" name="old_2fa_token" required autocomplete="off" autofocus>

                                @error('old_2fa_token')
                                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center col-md-12">
                            <img src="data:image/png;base64,{{ $base64QrCode }}">
                        </div>

                        <div class="form-group row">
                            <label for="new_2fa_token" class="col-md-4 col-form-label text-md-right">{{ __('Verify new token') }}</label>

                            <div class="col-md-6">
                                <input id="new_2fa_token" type="text" class="form-control @error('new_2fa_token') has-error @enderror" name="new_2fa_token" required autocomplete="off" autofocus>

                                @error('new_2fa_token')
                                <span class="help-block">
                            <strong>{{ $message }}</strong>
                        </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Validate') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="panel panel-danger">
            <div class="panel-heading text-center">{{ __('Remove Two Factor Authentication') }}</div>

            <div class="panel-body">
                @if(TwoFactor::isRequired())
                    <div class="alert alert-warning">
                        2FA is required for some of your permissions, removing 2FA is not permitted.
                    </div>
                @else
                    <div class="alert alert-warning">
                        Removing 2FA will significantly decrease your account's security. This action is not recommended.
                    </div>

                    <p>To remove 2FA from your account, Confirm by filling in a verify token from the authenticator app:</p>

                    <form class="form-horizontal" role="form" method="POST" action="{{ route('2fa.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <div class="form-group row">
                            <label for="2fa_token" class="col-md-4 col-form-label text-md-right">{{ __('Verify token') }}</label>

                            <div class="col-md-6">
                                <input id="2fa_token" type="text" class="form-control @error('2fa_token') has-error @enderror" name="2fa_token" required autocomplete="off" autofocus>

                                @error('2fa_token')
                                <span class="help-block">
                            <strong>{{ $message }}</strong>
                        </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-danger">
                                    {{ __('Remove 2FA') }}
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
