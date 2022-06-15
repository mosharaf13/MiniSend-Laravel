<?php

namespace App\Http\Controllers;

use App\EmailHandlers\HtmlTemplateEmailHandler;
use App\EmailHandlers\PlainTextEmailHandler;
use App\EmailStorages\EloquentBasedDbStorage;
use App\Http\Requests\EmailSendRequest;
use App\Models\Eloquent\Email;
use App\Models\EmailRequest;
use App\Services\EmailService;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
    public function __construct(
        public EmailService $emailService,
        public PlainTextEmailHandler $plainTextEmailHandler,
        public HtmlTemplateEmailHandler $htmlTemplateEmailHandler,
        public EloquentBasedDbStorage $eloquentBasedDbStorage
    ) {
    }


    /**
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(Email::orderBy('created_at', 'desc')->paginate());
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return response()->json(Email::findOrFail($id));
    }

    /**
     * @param EmailSendRequest $request
     * @return JsonResponse
     */
    public function send(EmailSendRequest $request)
    {
        $this->emailService->send(
            new EmailRequest($request),
            $this->plainTextEmailHandler,
            $this->eloquentBasedDbStorage
        );

        return response()->json("Mail posted Successfully");
    }
}
