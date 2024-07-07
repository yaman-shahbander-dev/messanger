<?php

namespace App\Http\Controllers;

use App\Helpers\OperationResult;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Services\UserProfile\UserProfileService;
use Illuminate\Http\JsonResponse;

class UserProfileController extends Controller
{
    public function update(UpdateUserProfileRequest $request): JsonResponse
    {
        $result = app(UserProfileService::class)->updateUserProfile($request);

        if ($result instanceof OperationResult) {
            return response()->failed($result->getMessage());
        }

        notyf()->addSuccess('Updated Successfully!');

        return response()->ok();
    }
}
