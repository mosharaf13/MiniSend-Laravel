<?php

namespace Tests\Feature;

use App\EmailHandlers\PlainTextEmailHandler;
use App\Mail\PlainTextEmail;
use App\Models\EmailRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\TestCase;

class PlainTextEmailHandlerTest extends TestCase
{
    private $request;
    private $emailRequest;
    private $plainTextEmailHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new Request([
            'from' => 'samplefrom@demo.com',
            'to' => 'sampleto@demo.com',
            'subject' => 'sample subject',
            'body' => 'sample body'
        ]);
        $this->emailRequest = new EmailRequest($this->request);
        $this->plainTextEmailHandler = new PlainTextEmailHandler();
    }

    /**
     * @test
     */
    public function plain_text_emails_can_be_sent()
    {
        Mail::fake();

        $this->plainTextEmailHandler->send($this->emailRequest);

        Mail::assertSent(PlainTextEmail::class);
        Mail::assertSent(PlainTextEmail::class, function ($mail) {
            return $mail->hasTo('sampleto@demo.com');
        });
    }

    /**
     * @test
     * @dataProvider plainTextEmailBodyProvider
     */
    public function sanitizes_mail_body(string $input, string $expected)
    {
        $emailRequest = new EmailRequest($this->request);
        $emailRequest->setBody($input);
        $emailRequest = $this->plainTextEmailHandler->sanitizeEmailRequest($emailRequest);
        $this->assertSame(
            $expected,
            $emailRequest->getBody()
        );
    }

    public function plainTextEmailBodyProvider()
    {
        $cases = [
            [
                'This is some <b>bold</b> text.',
                'This is some &lt;b&gt;bold&lt;/b&gt; text.',
            ],
            [
                'I love "PHP".',
                'I love &quot;PHP&quot;.'
            ]
        ];

        foreach ($cases as $case) {
            yield $case[0] => $case;
        }
    }


}