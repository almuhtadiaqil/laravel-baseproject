<?php

namespace App\Services;

use App\Http\Controllers\API\Exceptions\ApiRequestException;
use App\Http\Requests\RequestPaginate;
use App\Repositories\User\UserInterface;

class UserService
{
    protected UserInterface $repository;

    public function __construct(UserInterface $repo)
    {
        $this->repository = $repo;
    }

    public function getPagination(RequestPaginate $request): mixed
    {
        try {
            return $this->repository->getPagination($request);
        } catch (\Exception) {
            throw new ApiRequestException();
        }
    }
}
