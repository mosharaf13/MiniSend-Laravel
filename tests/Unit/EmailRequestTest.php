<?php

namespace Tests\Unit;

use App\Exceptions\BothTextAndHtmlBodyNotAllowedException;
use App\Models\AttachmentMeta;
use App\Models\EmailRequest;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\TestCase;

class EmailRequestTest extends TestCase
{
    private $httpRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $map = array(
            array('from', null, 'samplefrom@demo.com'),
            array('to', null, 'sampleto@demo.com'),
            array('subject', null, 'sample subject'),
            array('text_content', null, 'sample text')
        );
        $fileMap = array(
            array('attachments', [], [])
        );
        $this->httpRequest = $this->getMockBuilder(Request::class)->getMock();
        $this->httpRequest->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($map));

        $this->httpRequest->expects($this->any())
            ->method('file')
            ->will($this->returnValueMap($fileMap));
    }


    public function test_throws_exception_if_both_text_and_email_content_is_provided_with_http_request()
    {
        $this->expectException(BothTextAndHtmlBodyNotAllowedException::class);

        $map = array(
            array('from', null, 'samplefrom@demo.com'),
            array('to', null, 'sampleto@demo.com'),
            array('subject', null, 'sample subject'),
            array('text_content', null, 'sample text'),
            array('html_content', null, 'sample html')
        );
        $fileMap = array(
            array('attachments', [], [])
        );
        $httpRequest = $this->getMockBuilder(Request::class)->getMock();
        $httpRequest->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($map));

        $this->httpRequest->expects($this->any())
            ->method('file')
            ->will($this->returnValueMap($fileMap));


        $emailRequest = new EmailRequest($httpRequest);
    }

    public function test_get_from()
    {
        $emailRequest = new EmailRequest($this->httpRequest);
        $this->assertSame('samplefrom@demo.com', $emailRequest->getFrom());
    }

    public function test_get_to()
    {
        $emailRequest = new EmailRequest($this->httpRequest);
        $this->assertSame('sampleto@demo.com', $emailRequest->getTo());
    }

    public function test_get_subject()
    {
        $emailRequest = new EmailRequest($this->httpRequest);
        $this->assertSame('sample subject', $emailRequest->getSubject());
    }

    public function test_get_body()
    {
        $emailRequest = new EmailRequest($this->httpRequest);
        $this->assertSame('sample text', $emailRequest->getBody());
    }

    public function test_set_body()
    {
        $emailRequest = new EmailRequest($this->httpRequest);
        $emailRequest->setBody("another sample text");
        $this->assertSame('another sample text', $emailRequest->getBody());
    }

    public function test_get_attachments()
    {
        $emailRequest = new EmailRequest($this->httpRequest);
        $this->assertSame([], $emailRequest->getAttachments());
    }

    public function test_set_attachments_method_for_array_of_uploaded_file_object()
    {
        $files = [
          new UploadedFile(__FILE__, 'sample name'),
          new UploadedFile(__FILE__, 'sample second name'),
        ];
        $emailRequest = new EmailRequest($this->httpRequest);
        $emailRequest->setAttachments($files);
        $this->assertSame($files, $emailRequest->getAttachments());
    }

    public function test_set_attachments_method_for_array_of_attachment_meta_object()
    {
        $files = [
          new AttachmentMeta('sample path', 'sample name', 'sample extension'),
          new AttachmentMeta('sample path', 'sample second name', 'sample extension'),
        ];
        $emailRequest = new EmailRequest($this->httpRequest);
        $emailRequest->setAttachments($files);
        $this->assertSame($files, $emailRequest->getAttachments());
    }




}