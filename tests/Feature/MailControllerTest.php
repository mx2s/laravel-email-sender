<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MailControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var string
     */
    private string $sendRouteUrl = '/api/send';

    /**
     * @return string
     */
    private function getAuthToken() {
        $user = User::factory()->create();
        return $user->api_token;
    }

    /***
     * @return void
     */
    public function test_sendEmails_shouldPass()
    {
        $url = "{$this->sendRouteUrl}?api_token={$this->getAuthToken()}";
        $response = $this->postJson($url, [
            [
                'recipient' => 'someemail@rt.com',
                'subject' => 'Hey there',
                'body' => '<b>HTML body</b>',
                'attachments' => [
                    [
                        'fileName' => 'file.txt',
                        'fileContent' => 'MTIzNDU2'
                    ],
                    [
                        'fileName' => 'file2.txt',
                        'fileContent' => 'MTIzNDU2Nzg='
                    ],
                ]
            ]
        ]);

        $response->assertStatus(200);
        $response->assertExactJson([
            "message" => "all emails were queued for sending",
        ]);
    }

    /***
     * @return void
     */
    public function test_sendEmails_shouldCheckAuth()
    {
        $url = "{$this->sendRouteUrl}?api_token=noToken";
        $response = $this->post($url);

        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "invalid API token",
        ]);
    }

    /***
     * @return void
     */
    public function test_sendEmails_shouldValidateRequestPayload()
    {
        $url = "{$this->sendRouteUrl}?api_token={$this->getAuthToken()}";
        $response = $this->postJson($url, [
            [
                'recipient' => 'invalidEmail',
                'body' => 25,
                'attachments' => "abc"
            ]
        ]);

        $response->assertStatus(422);
        $response->assertExactJson( [
            "errors" => [
                "attachments" => [
                    "The attachments must be an array."
                ],
                "body" => [
                    "The body must be a string."
                ],
                "recipient" => [
                    "The recipient must be a valid email address."
                ],
                "subject" => [
                    "The subject field is required."
                ]
            ],
            "message" => "invalid payload structure for email with recipient: invalidEmail"
        ]);
    }
}
