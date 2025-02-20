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

    /**
     * Return a JSON response containing the user's favorite employees.
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return response()->json($this->employeeRepository->getFavorites());
    }
}