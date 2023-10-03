<?php

namespace Modules\Ppdb\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Modules\Ppdb\Emails\SendEmailAcceptPpdb;

class SendEmailPpdbJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dataSiswa;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($saveDataSiswaFromObjectCaller)
    {
        $this->dataSiswa = $saveDataSiswaFromObjectCaller;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $buildMessageEmail = new SendEmailAcceptPpdb($this->dataSiswa['nama']);
        Mail::to($this->dataSiswa['email'])->send($buildMessageEmail);
    }
}
