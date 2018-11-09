<?php

namespace App\Console\Commands;

use App\Helpers\JengaApi;
use Illuminate\Console\Command;
use anlutro\LaravelSettings\Facade as Setting;


class GenerateApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jenga:generateToken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate token to be used in jenga api interactions';

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
        //
        $token = JengaApi::generateToken();
        $data = ['token' => $token['access_token']];

        Setting::forget('api-token');

        Setting::set('api-token',$data);
        Setting::save();

        $this->info("token generated successfully");
    }
}
