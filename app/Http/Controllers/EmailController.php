<?php

namespace App\Http\Controllers;

use App\EmailHandlers\PlainTextEmailHandler;
use App\Http\Requests\EmailSendRequest;
use App\Models\Eloquent\Email;
use App\Models\EmailRequest;
use App\Services\EmailService;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
    public function __construct(public EmailService $emailService, public PlainTextEmailHandler $plainTextEmailHandler)
    {
    }


    /**
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(Email::paginate());
    }

    /**
     * @param EmailSendRequest $request
     * @return JsonResponse
     */
    public function send(EmailSendRequest $request)
    {
        $this->emailService->send(new EmailRequest($request), $this->plainTextEmailHandler);

        return response()->json("Mail posted Successfully");
    }
}
