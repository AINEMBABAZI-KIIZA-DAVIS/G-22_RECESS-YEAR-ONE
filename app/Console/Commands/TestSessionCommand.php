<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class TestSessionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test session and CSRF token functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Test session
        $testValue = 'test_' . Str::random(10);
        Session::put('test_key', $testValue);
        $this->info('Session test value set: ' . $testValue);

        // Test CSRF token
        $token = csrf_token();
        $this->info('CSRF Token: ' . $token);
        $this->info('CSRF Token Length: ' . strlen($token));

        // Verify session is working
        $sessionValue = Session::get('test_key');
        if ($sessionValue === $testValue) {
            $this->info('✅ Session test passed!');
        } else {
            $this->error('❌ Session test failed!');
        }

        // Check session config
        $this->info("\nSession Configuration:");
        $this->table(
            ['Setting', 'Value'],
            [
                ['Driver', config('session.driver')],
                ['Lifetime', config('session.lifetime') . ' minutes'],
                ['Expire on close', config('session.expire_on_close') ? 'true' : 'false'],
                ['Secure', config('session.secure') ? 'true' : 'false'],
                ['HTTP Only', config('session.http_only') ? 'true' : 'false'],
                ['Same Site', config('session.same_site')],
            ]
        );

        // Check session storage path if using file driver
        if (config('session.driver') === 'file') {
            $path = config('session.files');
            $this->info("\nSession files location: " . storage_path('framework/sessions'));
            
            if (is_dir(storage_path('framework/sessions'))) {
                $files = glob(storage_path('framework/sessions/*'));
                $this->info('Session files count: ' . count($files));
            } else {
                $this->warn('Session directory does not exist!');
            }
        }
    }
}
