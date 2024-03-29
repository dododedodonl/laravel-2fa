<?php

namespace Dododedodonl\Laravel2fa\Console;

use BaconQrCode\Writer;
use Illuminate\Console\Command;
use BaconQrCode\Renderer\PlainTextRenderer;
use Dododedodonl\Laravel2fa\Console\Helpers\TerminalTextRenderer;

use Dododedodonl\Laravel2fa\Traits\SharedMethods;

class GenerateOtpSecret extends Command
{
    use SharedMethods;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2fa:generate {username?} {--terminal}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a 2fa secret for a user.';

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

        $user = resolve('laravel-2fa')->userQuery()->where('username', $username)->firstOrFail();
        if( ! is_null($user->otp_secret)) {
            if( ! $this->confirm('This user already has a secret, are you sure you want to continue?')) {
                return;
            }
        }

        $otp = $this->newOtp($username);
        $user->otp_secret = $otp->getSecret();

        $r = $this->option('terminal') ? new TerminalTextRenderer() : new PlainTextRenderer();
        $w = new Writer($r);
        echo $w->writeString($otp->getProvisioningUri());

        $this->line('');
        $this->line('Note: some android devices are impossibly stupid and are incapable to scan a white on black qr code.');
        $this->line('');

        $token = $this->ask('Verify');

        if($this->verifyToken($user, $token)) {
            $this->info('2fa setup correctly');
            $user->save();
        } else {
            $this->error('2fa not working, please try again');
        }
    }
}
