<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\EmployeeResource;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    protected $model;

    public function __construct(Employee $employee)
    {
        $this->model = $employee;
    }

    public function getAllEmployees(int $perPage = 5): AnonymousResourceCollection
    {
        $employees = $this->model->with('company')->paginate($perPage);

        return $this->formatPaginationResponse($employees);
    }

    public function searchEmployees(array $filters): AnonymousResourceCollection
    {
        $query = $this->model->query();
        foreach ($filters as $key => $value) {
            $query->where($key, 'LIKE', "%$value%");
        }

        $employees = $query->paginate(5);

        return $this->formatPaginationResponse($employees);
    }


    /**
     * Format the pagination response from Laravel's LengthAwarePaginator.
     *
     * @param LengthAwarePaginator $employees
     * @return array
     */
    private function formatPaginationResponse(LengthAwarePaginator $employees): AnonymousResourceCollection
    {
        return EmployeeResource::collection($employees)->additional([
            'pagination' => [
                'total' => $employees->total(),
                'per_page' => $employees->perPage(),
                'last_page' => $employees->lastPage(),
                'current_page' => $employees->currentPage(),
                'next_page_url' => $employees->nextPageUrl(),
                'prev_page_url' => $employees->previousPageUrl(),
                'from' => $employees->firstItem(),
                'to' => $employees->lastItem(),
            ]
        ]);
    }
}