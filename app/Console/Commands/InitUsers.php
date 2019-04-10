<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
class InitUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $users_data = [
        [
            'name' => 'mingzai',
            'email' => 'mingzai123@gmail.com',
            'password' => 'mingzai123',
        ],
        [
            'name' => 'sasa123',
            'email' => 'sasa123@gmail.com',
            'password' => 'sasa123',
        ],
        [
            'name' => 'yiwei123',
            'email' => 'yiwei123@gmail.com',
            'password' => 'yiwei123',
        ],
    ];


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
     * @return mixed
     */
    public function handle()
    {
        foreach($this->users_data as $user_data) {
            $user = new User;
            $user->name = $user_data['name'];
            $user->email = $user_data['email'];
            $user->password = bcrypt($user_data['password']);
            $user->save();

            echo "User $user->email created successfully\n";
        }
    }
}
