<?php

namespace Modules\Ppdb\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailAcceptPpdb extends Mailable
{
    use Queueable, SerializesModels;

    protected $namaSiswa;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($saveNamaSiswaFromObjectCaller)
    {
        $this->namaSiswa = $saveNamaSiswaFromObjectCaller;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('ppdb::layouts.ppdb.message_email')
            ->subject('Selamat, Anda diterima dalam PPDB sekolah!')
            ->with([
                'nama' => $this->namaSiswa,
            ]);
    }
}
