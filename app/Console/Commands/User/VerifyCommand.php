<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;

class VerifyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:verify {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Верификация пользователя';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('Undefined user with ' . $email);

            return false;
        }

        if ($user->hasVerified()) {
            $this->error('This user with ' . $email . ' already verified!');

            return false;
        }

        if (false !== $user->setVerified()) {
            $this->info('Success!');

            return true;
        }

        $this->error('Произошла неведомая ошибка!');

        return false;
    }
}
