@extends(config('laravel-2fa.blade-extends', 'layouts.app'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-success">
                <div class="panel-heading text-center">{{ __('Two Factor Authentication') }}</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('2fa.provided') }}">
                        @csrf

                        <div class="form-group{{ $errors->has('2fa_token') ? ' has-error' : '' }}">
                            <label for="2fa_token" class="col-md-4 control-label">{{ __('Token') }}</label>

                            <div class="col-md-6">
                                <input id="2fa_token" type="text" class="form-control" name="2fa_token" required autocomplete="off" autofocus>

                                @error('2fa_token')
                                    <span class="help-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Validate') }}
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
