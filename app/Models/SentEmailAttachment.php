<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed|string file_base64
 * @property mixed|string file_name
 * @property mixed sentEmail
 */
class SentEmailAttachment extends Model
{
    use HasFactory;

    protected $table = 'sent_email_attachments';
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'file_name',
        'file_base64',
        'sent_email_id'
    ];

    public function sentEmail(): BelongsTo
    {
        return $this->belongsTo(SentEmail::class, 'id');
    }
}
