<?php

namespace App\Http\Controllers\Employee;

use App\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController
{
    protected $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * Search employees by given filters.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke(Request $request)
    {
        $filters = $request->only(['full_name', 'email', 'company', 'category','area']);
        return $this->employeeRepository->searchEmployees($filters);
    }
}