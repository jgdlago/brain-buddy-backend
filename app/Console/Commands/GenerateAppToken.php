<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class GenerateAppToken extends Command
{
    protected $signature = 'app:generate-token';
    protected $description = 'Generate a fixed API token for mobile app';

    public function handle()
    {
        $appUser = User::firstOrCreate(
            ['email' => config('app.default_users.game_sync.email')],
            [
                'name' => config('app.default_users.game_sync.name'),
                'password' => config('app.default_users.game_sync.password'),
            ]
        );

        PersonalAccessToken::where('tokenable_id', $appUser->id)->delete();
        $token = $appUser->createToken('mobile-app-token')->plainTextToken;

        $this->info("API key:");
        $this->line($token);

        return 0;
    }
}
