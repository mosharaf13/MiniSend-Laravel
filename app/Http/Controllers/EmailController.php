<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailSendRequest;
use App\Mail\PlainTextEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * @param EmailSendRequest $request
     * @return JsonResponse
     */
    public function send(EmailSendRequest $request)
    {
        Mail::to($request->get('to'))->send(
            new PlainTextEmail($request)
        );

        return response()->json("Mail posted Successfully");
    }
}
