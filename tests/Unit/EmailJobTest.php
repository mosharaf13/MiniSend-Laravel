<?php

namespace Tests\Unit;

use App\Contracts\EmailHandler;
use App\Contracts\EmailStorage;
use App\Jobs\EmailJob;
use App\Models\EmailRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;

class EmailJobTest extends TestCase
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

    public function test_that_handle_method_should_call_send_of_email_handler_then_changeStatusToSent_of_email_storage()
    {
        $emailHandler = $this->getMockBuilder(EmailHandler::class)->getMock();
        $emailHandler->expects($this->once())
            ->method('send');

        $emailStorage = $this->getMockBuilder(EmailStorage::class)->getMock();
        $emailStorage->expects($this->once())
            ->method('changeStatusToSent');

        $emailRequest = new EmailRequest($this->httpRequest);
        $emailJob = new EmailJob($emailRequest, 1, $emailHandler, $emailStorage);
        $emailJob->handle();
    }

    public function test_that_failed_method_calls_changeStatusToFailed_and_error_logs()
    {
        Log::shouldReceive('error')
            ->once()
            ->withArgs(function ($message) {
                return strpos($message, "Couldn't send email from ") !== false;
            });

        $emailHandler = $this->getMockBuilder(EmailHandler::class)->getMock();

        $emailStorage = $this->getMockBuilder(EmailStorage::class)->getMock();
        $emailStorage->expects($this->once())
            ->method('changeStatusToFailed');

        $emailRequest = new EmailRequest($this->httpRequest);
        $emailJob = new EmailJob($emailRequest, 1, $emailHandler, $emailStorage);
        $emailJob->failed();
    }

}