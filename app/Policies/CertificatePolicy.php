<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Certificate;

class CertificatePolicy
{
    /**
     * Determine whether the user can view the certificate.
     */
    public function view(User $user, Certificate $certificate): bool
    {
        return $user->id === $certificate->user_id 
            || $user->hasRole(User::ROLE_ADMIN)
            || $user->id === $certificate->event->organizer_id;
    }
}
