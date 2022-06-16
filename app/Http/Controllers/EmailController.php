<?php

namespace App\Http\Controllers;

use App\AttachmentStorage;
use App\EmailHandlers\HtmlTemplateEmailHandler;
use App\EmailHandlers\PlainTextEmailHandler;
use App\EmailStorages\EloquentBasedDbStorage;
use App\Http\Requests\EmailSendRequest;
use App\Models\Eloquent\Email;
use App\Models\Eloquent\EmailAttachment;
use App\Models\EmailRequest;
use App\Services\EmailService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class EmailController extends Controller
{
    private array $filterTypeToDbKeyMap;

    public function __construct(
        public EmailService $emailService,
        public PlainTextEmailHandler $plainTextEmailHandler,
        public HtmlTemplateEmailHandler $htmlTemplateEmailHandler,
        public EloquentBasedDbStorage $eloquentBasedDbStorage,

    ) {
        $this->filterTypeToDbKeyMap = ['sender' => 'from', 'recipient' => 'to', 'subject' => 'subject'];
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $query = Email::select('id', 'from', 'to', 'subject', 'created_at', 'status');

        $this->getFilterAppliedQuery($query, $request);
        return response()->json(
            $query->orderBy(
                'created_at',
                'desc'
            )->paginate()
        );
    }

    /**
     * @return JsonResponse
     */
    public function getStatistics(Request $request)
    {
        $query = Email::query();
        $this->getFilterAppliedQuery($query, $request);

        return response()->json([
            'posted' => $this->getFilterAppliedQuery(Email::query(), $request)->where(
                'status',
                Email::STATUS_POSTED
            )->count(),
            'sent' => $this->getFilterAppliedQuery(Email::query(), $request)->where(
                'status',
                Email::STATUS_SENT
            )->count(),
            'failed' => $this->getFilterAppliedQuery(Email::query(), $request)->where(
                'status',
                Email::STATUS_FAILED
            )->count(),
        ]);
    }

    private function getFilterAppliedQuery(Builder $query, Request $request): Builder
    {
        if (!empty($request->get('filterValue'))) {
            $query->where(
                $this->filterTypeToDbKeyMap[$request->get('filterType')],
                'like',
                $request->get('filterValue') . '%'
            );
        }
        return $query;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return response()->json(Email::with('emailAttachments')->findOrFail($id));
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
