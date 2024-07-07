<?php

namespace App\Actions\UserProfileActions;

use App\Http\Requests\UpdateUserProfileRequest;
use App\Traits\FileUpload;

class UpdateUserProfileImageAction
{
    use FileUpload;

    public function __invoke(UpdateUserProfileRequest $request): ?string
    {
        return $this->uploadFile(
            request: $request,
            inputName: 'avatar'
        );
    }
}
