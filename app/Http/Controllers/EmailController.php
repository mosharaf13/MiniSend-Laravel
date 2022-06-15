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
use Illuminate\Support\Facades\App;

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
        $request = new EmailRequest($request);
        $this->emailService->send(
            $request,
            $this->getEmailHandler($request),
            $this->eloquentBasedDbStorage
        );

        return response()->json("Mail posted Successfully");
    }

    private function getEmailHandler(EmailRequest $request)
    {
        if ($request->getBodyType() == EmailRequest::BODY_TYPE_TEXT) {
            return App::make(PlainTextEmailHandler::class);
        }
        return App::make(HtmlTemplateEmailHandler::class);
    }
}
