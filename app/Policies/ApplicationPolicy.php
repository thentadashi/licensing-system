<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Application;

class ApplicationPolicy
{
    public function view(User $user, Application $application): bool
    {
        // allow if student owns it OR if user is admin
        return $application->user_id === $user->id || $user->hasRole(['super_admin','clerk']);
    }

    public function update(User $user, Application $application): bool
    {
        return $application->user_id === $user->id;
    }
}
