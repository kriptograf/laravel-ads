<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;

/**
 * Назначить всем пользователям роль переданную в параметре команды
 *
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class AssignDefaultRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Назначение роли всем пользователям';

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
        $roleName = $this->argument('role');

        $users = User::all();

        foreach ($users as $user) {
            $user->assignRole($roleName);
        }

        $this->info('Success!');

        return true;
    }
}
