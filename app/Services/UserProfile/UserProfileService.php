<?php

namespace App\Services\UserProfile;

use App\Actions\UserProfileActions\UpdateUserProfileAction;
use App\Actions\UserProfileActions\UpdateUserProfileImageAction;
use App\Helpers\OperationResult;
use App\Http\Requests\UpdateUserProfileRequest;
use Illuminate\Support\Facades\Auth;

class UserProfileService
{
    public function updateUserProfile(UpdateUserProfileRequest $request): bool|OperationResult
    {
        $avatarPath = app(UpdateUserProfileImageAction::class)($request);
        return app(UpdateUserProfileAction::class)($request, Auth::user(), $avatarPath);
    }
}
