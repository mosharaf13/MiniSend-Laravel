<?php

namespace Tests\Feature\Mailables;
use App\Mail\PlainTextEmail;
use App\Models\EmailRequest;
use Illuminate\Http\Request;
use Tests\TestCase;

class PlainTextEmailTest extends TestCase
{
    private $request;
    private $emailRequest;

    protected function setUp():void
    {
        parent::setUp();
        $this->request = new Request([
            'from' => 'samplefrom@demo.com',
            'to' => 'sampleto@demo.com',
            'subject' => 'sample subject',
            'text_content' => 'sample body for Plain text email'
        ]);
        $this->emailRequest = new EmailRequest($this->request);
    }


    public function test_plain_text_mail_content()
    {
        $mailable = new PlainTextEmail($this->emailRequest);
        $mailable->assertSeeInText($this->emailRequest->getBody());
    }
}