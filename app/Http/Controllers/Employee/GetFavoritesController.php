<?php

namespace App\Http\Controllers\Employee;

use App\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Http\JsonResponse;

class GetFavoritesController
{
    protected $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function __invoke(): JsonResponse
    {
        return response()->json($this->employeeRepository->getFavorites());
    }
}