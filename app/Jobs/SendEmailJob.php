<?php

namespace App\Jobs;

use App\App\EmailAttachment;
use App\Models\SentEmail;
use App\Models\SentEmailAttachment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $recipient;
    private string $subject;
    private string $body;
    private array $attachments;

    /**
     * Create a new job instance.
     *
     * @param string $recipient
     * @param $subject
     * @param $body
     * @param EmailAttachment[] $attachments
     */
    public function __construct(string $recipient, $subject, $body, array $attachments = [])
    {
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->body = $body;
        $this->attachments = $attachments;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send([], [], function ($message) {
            $message->to($this->recipient)
                ->subject($this->subject)
                ->setBody($this->body, 'text/html');

            foreach ($this->attachments as $attachment) {
                $message->attachData(base64_decode($attachment->base64Content), $attachment->fileName);
            }
        });

        $sentEmail = SentEmail::create([
            'recipient' => $this->recipient,
            'subject' => $this->subject,
            'body' => $this->body
        ]);

        foreach ($this->attachments as $attachment) {
            SentEmailAttachment::create([
                'file_name' => $attachment->fileName,
                'file_base64' => $attachment->base64Content,
                'sent_email_id' => $sentEmail->id
            ]);
        }
    }
}
