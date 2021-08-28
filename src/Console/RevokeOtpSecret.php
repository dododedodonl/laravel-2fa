<?php

namespace Dododedodonl\Laravel2fa\Console;

use BaconQrCode\Writer;
use Illuminate\Console\Command;

use Dododedodonl\Laravel2fa\Traits\SharedMethods;

class RevokeOtpSecret extends Command
{
    use SharedMethods;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2fa:revoke {username?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revokes a 2fa secret for a user.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $username = $this->argument('username');

        if( ! $username) {
            $username = $this->ask('Username');
        }

        $user = resolve('laravel-2fa')->userQuery()->where('username', $username)->first();
        if( ! is_null($user->otp_secret)) {
            if($this->confirm('Are you sure you want to revoke the secret? This action is irreversible.')) {
                $user->otp_secret = null;
                $user->save();

                $this->info('Secret revoked.');
                return;
            }
        }

        $this->info('Secret not revoked.');
    }
}
