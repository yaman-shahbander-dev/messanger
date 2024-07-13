<?php

namespace App\Actions\MessengerActions;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Events\Message AS MessageEvent;
use App\Traits\FileUpload;
use Illuminate\Support\Facades\Auth;
use App\Helpers\OperationResult;
use App\Enums\OperationResultEnum;

class StoreMessageAction
{
    use FileUpload;

    public function __invoke(StoreMessageRequest $request)
    {
        $attachment = $this->storeAttachment($request);

        $message = Message::query()
            ->create([
                'from_id' => Auth::user()->id,
                'to_id' => $request->id,
                'body' => $request->message,
                'attachment' => $attachment,
            ]);

        if (!$message) return new OperationResult(OperationResultEnum::FAILURE->value, 'Failed to store the message!');

        $pusherMessage = $message;
        $pusherMessage->attachment = json_decode($pusherMessage->attachment);
        MessageEvent::dispatch($pusherMessage);

        return $message;
    }

    public function storeAttachment(StoreMessageRequest $request)
    {
        if (!$request->attachment) return null;

        return json_encode($this->uploadFile(
            $request,
            'attachment'
        ));
    }
}
