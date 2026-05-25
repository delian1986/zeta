<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIncomingEmailRequest;
use App\Services\EmailIngestionService;
use Illuminate\Http\Response;

class EmailController extends Controller
{
    public function __construct(
        private readonly EmailIngestionService $emailIngestionService,
    ) {}

    public function store(StoreIncomingEmailRequest $request): Response
    {
        $this->emailIngestionService->storeIncoming(
            $request->validated('from'),
            $request->validated('subject'),
            $request->validated('body'),
        );

        return response('created', 201)->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
