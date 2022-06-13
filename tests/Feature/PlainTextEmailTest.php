<?php

namespace Tests\Feature;

use App\Mail\PlainTextEmail;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\TestCase;

class PlainTextEmailTest extends TestCase
{

    /**
     * @test
     */
    public function plain_text_emails_can_be_sent()
    {
        Mail::fake();
        $request = new Request([
            'from' => 'samplefrom@demo.com',
            'to' => 'sampleto@demo.com',
            'subject' => 'sample subject',
            'body' => 'sample body'
        ]);
        $email = new Email($request);
        Mail::to($request->get('to'))->send(new PlainTextEmail($email));

        Mail::assertSent(PlainTextEmail::class);
        Mail::assertSent(PlainTextEmail::class, function ($mail) {
            return $mail->hasTo('sampleto@demo.com');
        });
    }


}