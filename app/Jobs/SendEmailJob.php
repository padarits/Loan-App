<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to;
    protected $subject;
    protected $view;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to, $subject, $view, $data = [])
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->view = $view;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::send($this->view, $this->data, function ($message) {
                $message->to($this->to)->subject($this->subject);
            });
            return true; // Ja nosūtīšana ir veiksmīga
        } catch (\Exception $e) {
            Log::error("Email could not be sent to {$this->to}. Error: {$e->getMessage()}");
            return false; // Ja rodas kļūda
        }
    }
}
