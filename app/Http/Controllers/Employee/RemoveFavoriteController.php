<?php

namespace App\Http\Controllers\Employee;

use App\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Http\JsonResponse;

class RemoveFavoriteController
{
    protected $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }


    /**
     * Remove an employee from the user's favorites.
     *
     * @param int $id Employee ID
     * @return JsonResponse
     */
    public function __invoke(int $id): JsonResponse
    {
        return response()->json($this->employeeRepository->removeFavorite($id));
    }
}