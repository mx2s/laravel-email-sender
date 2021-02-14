<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string recipient
 * @property mixed|string subject
 * @property mixed|string body
 */
class SentEmail extends Model
{
    use HasFactory;

    protected $table = 'sent_emails';
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'recipient',
        'subject',
        'body'
    ];

    public function attachments()
    {
        return $this->hasMany(SentEmailAttachment::class, 'sent_email_id');
    }
}
