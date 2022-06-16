<?php

namespace Tests\Unit;

use App\Exceptions\BothTextAndHtmlBodyNotAllowedException;
use App\Models\EmailRequest;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class EmailRequestTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function test_throws_exception_if_both_text_and_email_content_is_provided_with_http_request()
    {
        $this->expectException(BothTextAndHtmlBodyNotAllowedException::class);

        $map = array(
            array('from', null, 'samplefrom@demo.com'),
            array('to', null, 'sampleto@dmeo.com'),
            array('subject', null, 'sample subject'),
            array('text_content', null, 'sample text'),
            array('html_content', null, 'sample html')
        );
        $httpRequest = $this->getMockBuilder(Request::class)->getMock();
        $httpRequest->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($map));

        $emailRequest = new EmailRequest($httpRequest);
    }
}