<?php

namespace Tests\Feature\Mailables;
use App\Mail\HtmlTemplateEmail;
use App\Mail\PlainTextEmail;
use App\Models\EmailRequest;
use Illuminate\Http\Request;
use Tests\TestCase;

class HtmlTemplateEmailTest extends TestCase
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
            'html_content' => '<div style="background-color:powderblue;">
                                <h1>This is a heading</h1>
                                <p>This is a paragraph.</p>
                                </div>'
        ]);
        $this->emailRequest = new EmailRequest($this->request);
    }


    public function test_plain_text_mail_content()
    {
        $mailable = new HtmlTemplateEmail($this->emailRequest);
        $mailable->assertSeeInHtml('This is a heading');
        $mailable->assertSeeInOrderInHtml(['This is a heading', 'This is a paragraph.']);
    }
}