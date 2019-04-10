<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Bouncer;

class InitBouncer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:bouncer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $admin = Bouncer::role()->create([
            'name' => 'admin',
            'title' => 'Administrator',
        ]);

        $doctor = Bouncer::role()->create([
            'name' => 'doctor',
            'title' => 'doctor',
        ]);

        $patient = Bouncer::role()->create([
            'name' => 'patient',
            'title' => 'patient',
        ]);

        //Define abilities 

        $manageUser = Bouncer::ability()->create([
            'name' => 'manager-user',
            'title' => 'Manager User',
        ]);

        $manageAppointment = Bouncer::ability()->create([
            'name' => 'manager-appointment',
            'title' => 'Manager Appointment',
        ]);

        $viewAppointment = Bouncer::ability()->create([
            'name' => 'view-appointments',
            'title' => 'View Appointments',
        ]);

        // Assign abilities to roles
        Bouncer::allow($admin)->to($manageAppointment);
        Bouncer::allow($admin)->to($manageUser);
        Bouncer::allow($admin)->to($viewAppointment);

        Bouncer::allow($doctor)->to($viewAppointment);
        Bouncer::allow($patient)->to($viewAppointment);

        $user = User::where('email', 'mingzai123@gmail.com')->first();
        Bouncer::assign($admin)->to($user);

        $user = User::where('email', 'sasa123@gmail.com')->first();
        Bouncer::assign($doctor)->to($user);

        $user = User::where('email', 'yiwei123@gmail.com')->first();
        Bouncer::assign($patient)->to($user);
    }
}
