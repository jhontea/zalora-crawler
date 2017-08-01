<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;

class EmailTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email Test Command';

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
        Mail::send('emails.test', [], function ($message) {
            $message->to('hafizh@suitmedia.com')
                    ->subject('Test email');
        });
    }
}
