<?php

namespace App\Actions\UserProfileActions;

use App\Enums\OperationResultEnum;
use App\Helpers\OperationResult;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests\UpdateUserProfileRequest;

class UpdateUserProfileAction
{
    public function __invoke(UpdateUserProfileRequest $request, Authenticatable $user, ?string $avatarPath): OperationResult|bool
    {
        $avatar = $avatarPath ?? $user->avatar;

        $password = $this->fillUserPassword($request, $user);

        $result = $this->updateUserData($request, $user, $password, $avatar);

        return $this->getResult($result);
    }

    private function fillUserPassword(UpdateUserProfileRequest $request, Authenticatable $user): string
    {
        return $request->filled('current_password') ? bcrypt($request->current_password) : $user->password;
    }

    private function updateUserData(UpdateUserProfileRequest $request, Authenticatable $user, string $password, string $avatar): bool
    {
        return User::query()
            ->where('id', $user->id)
            ->update([
                'name' => $request->name,
                'user_name' => $request->user_id,
                'email' => $request->email,
                'avatar' => $avatar,
                'password' => $password,
            ]);
    }

    private function getResult(bool $result): OperationResult|bool
    {
        if ($result) return true;

        return new OperationResult(OperationResultEnum::FAILURE->value, 'Failed to update the user profile!');
    }
}
