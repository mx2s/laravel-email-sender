<?php


namespace App\Http\Controllers\Api;

use App\App\EmailAttachment;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\SentEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class MailController
 * @package App\Http\Controllers\Api
 */
class MailController extends Controller
{
    private $emailSchema = [
        "recipient" => 'required|email',
        "subject" => 'required|string',
        "body" => 'required|string',
        "attachments" => 'array',
        "attachments.*.fileName" => 'required|string',
        "attachments.*.fileContent" => 'required|string',
    ];

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendEmails(Request $request): JsonResponse
    {
        $emailsArray = json_decode($request->getContent(), true);

        if ($emailsArray == null) {
            return response()->json([
                "message" => "json body is missing or invalid"
            ], 422);
        }

        if (!is_array($emailsArray)) {
            return response()->json([
                "message" => "json body must be an array"
            ], 422);
        }

        foreach ($emailsArray as $emailToSend) {
            $validator = Validator::make($emailToSend, $this->emailSchema);

            if ($emailToSend == null) {
                continue;
            }

            $recipient = array_key_exists('recipient', $emailToSend) ? $emailToSend['recipient'] : 'unknown';

            if ($validator->fails()) {
                return response()->json([
                    "message" => "invalid payload structure for email with recipient: {$recipient}",
                    "errors" => $validator->errors()
                ], 422);
            }

            $attachments = [];

            if (array_key_exists('attachments', $emailToSend)) {
                foreach ($emailToSend['attachments'] as $payloadAttachment) {
                    $attachment = new EmailAttachment();
                    $attachment->fileName = $payloadAttachment['fileName'];
                    $attachment->base64Content = $payloadAttachment['fileContent'];
                    $attachments[] = $attachment;
                }
            }

            SendEmailJob::dispatch(
                $emailToSend['recipient'],
                $emailToSend['subject'],
                $emailToSend['body'],
                $attachments
            );
        }

        return response()->json([
            "message" => "all emails were queued for sending",
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function listEmails(): JsonResponse
    {
        $emails = SentEmail::orderBy('id', 'DESC')->get();

        foreach ($emails as $email) {
            $email['attachments'] = $email->attachments;
        }

        return response()->json([
            "sent_emails" => $emails
        ]);
    }
}
