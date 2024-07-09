<?php

namespace App\Actions\MessengerActions;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class GetContactsAction
{
    public function __invoke(): LengthAwarePaginator
    {
        return Message::query()
            ->join('users', function ($q) {
                $q->on('messages.from_id', '=', 'users.id')
                    ->orOn('messages.to_id', '=', 'users.id');
            })
            ->where(function ($q) {
                $q->where('messages.from_id', Auth::user()->id)
                    ->orWhere('messages.to_id', Auth::user()->id);
            })
            ->where('users.id', '!=', Auth::user()->id)
            ->select([
                'users.id',
                'users.avatar',
                'name',
                'user_name',
                'email',
                DB::raw('MAX(messages.created_at) AS max_created_at')
            ])
            ->orderBy('max_created_at', 'desc')
            ->groupBy([
                'users.id',
                'users.avatar',
                'name',
                'user_name',
                'email'
            ])
            ->paginate(10);
    }
}
