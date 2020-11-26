<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignAdminRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Назначить пользователю роль admin';

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

        if (!$email) {
            $this->error('Undefined key email! ');

            return false;
        }

        $user = User::findByEmail($email);

        if (!$user) {
            $this->error('Undefined user with ' . $email);

            return false;
        }

        $role = Role::findByName('admin');

        $user->assignRole($role);

        $this->info('Success!');

        return true;
    }
}
