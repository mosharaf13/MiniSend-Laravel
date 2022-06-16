<?php

namespace Tests\Feature\EmailHandlers;
use App\EmailHandlers\HtmlTemplateEmailHandler;
use App\Mail\HtmlTemplateEmail;
use App\Models\EmailRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HtmlTemplateEmailHandlerTest extends TestCase
{
    private $request;
    private $emailRequest;
    private $htmlTemplateEmailHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new Request([
            'from' => 'samplefrom@demo.com',
            'to' => 'sampleto@demo.com',
            'subject' => 'sample subject',
            'html_content' => 'sample body'
        ]);
        $this->emailRequest = new EmailRequest($this->request);
        $this->htmlTemplateEmailHandler = new HtmlTemplateEmailHandler();
    }

    public function test_plain_text_emails_can_be_sent()
    {
        Mail::fake();

        $this->htmlTemplateEmailHandler->send($this->emailRequest);

        Mail::assertSent(HtmlTemplateEmail::class);
        Mail::assertSent(HtmlTemplateEmail::class, function ($mail) {
            return $mail->hasTo('sampleto@demo.com');
        });
    }

    /**
     * @dataProvider htmlEmailBodyProvider
     */
    public function test_sanitizes_mail_body(string $input, string $expected)
    {
        $emailRequest = new EmailRequest($this->request);
        $emailRequest->setBody($input);
        $emailRequest = $this->htmlTemplateEmailHandler->sanitizeEmailRequest($emailRequest);
        $this->assertSame(
            $expected,
            $emailRequest->getBody()
        );
    }

    public function htmlEmailBodyProvider()
    {
        $cases = [
            // Text
            [
                'hello world',
                'hello world',
            ],
            [
                '< Hello',
                ' Hello',
            ],
            [
                '<unknown>Lorem ipsum</unknown>',
                '',
            ],

            //scripts
            [
                '<div>Lorem ipsum dolor sit amet, consectetur adipisicing elit.<script>alert(\'ok\');</script></div>',
                '<div>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div>',
            ],


            // Inspired by https://www.youtube.com/watch?v=kz7wmRV9xsU
            [
                '＜script＞alert(\'ok\');＜/script＞',
                '&#xFF1C;script&#xFF1E;alert(&#039;ok&#039;);&#xFF1C;/script&#xFF1E;',
            ],

            // Inspired by https://twitter.com/brutelogic/status/1066333383276593152?s=19
            [
                '"><svg/onload=confirm(1)>"@x.y',
                '&#34;&gt;',
            ],

            // Styles
            [
                '<style>body { background: red; }</style>',
                '',
            ],
            [
                '<div>Lorem ipsum dolor sit amet, consectetur.<style>body { background: red; }</style></div>',
                '<div>Lorem ipsum dolor sit amet, consectetur.</div>',
            ],
            [
                '<img src="https://trusted.com/img/example.jpg" style="position:absolute;top:0;left:0;width:9000px;height:9000px;" />',
                '<img src="https://trusted.com/img/example.jpg" style="position:absolute;top:0;left:0;width:9000px;height:9000px;" />',
            ],
            [
                '<a style="font-size: 40px; color: red;">Lorem ipsum dolor sit amet, consectetur.</a>',
                '<a style="font-size: 40px; color: red;">Lorem ipsum dolor sit amet, consectetur.</a>',
            ],

            // Comments
            [
                'Lorem ipsum dolor sit amet, consectetur<!--if[true]> <script>alert(1337)</script> -->',
                'Lorem ipsum dolor sit amet, consectetur',
            ],
            [
                'Lorem ipsum<![CDATA[ <!-- ]]> <script>alert(1337)</script> <!-- -->',
                'Lorem ipsum  ',
            ],
            // Normal Tags
            [
                '<img src="/img/example.jpg" alt="Image alternative text" title="Image title">',
                '<img src="/img/example.jpg" alt="Image alternative text" title="Image title" />',
            ],
            [
                '<img src="http://trusted.com/img/example.jpg" alt="Image alternative text" title="Image title" />',
                '<img src="http://trusted.com/img/example.jpg" alt="Image alternative text" title="Image title" />',
            ]
        ];

        foreach ($cases as $case) {
            yield $case[0] => $case;
        }
    }
}