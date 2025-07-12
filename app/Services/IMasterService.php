<?php

namespace App\Services;

use App\Http\Requests\UpdateMasterInfoRequest;
use App\Models\MasterInfo;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface IMasterService
{
    public function searchData(): array;

    public function searchResult(Request $request): Collection;

    public function getMasterInfo(User $user, string $sort);

    public function updateMasterInfo(array $data, MasterInfo $master, UpdateMasterInfoRequest $request);

    public function setServices(int $id, Request $request);

    public function getMessagesInfo(int $id): array;
}
