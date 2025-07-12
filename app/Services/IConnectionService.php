<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;

interface IConnectionService
{
    public function checkFeedback(array $feedbackData): RedirectResponse;

    public function checkConnect(int $userId, int $masterId): bool;

    public function setNewRating(int $id): void;

    public function createSupportTicket(array $support);
}
