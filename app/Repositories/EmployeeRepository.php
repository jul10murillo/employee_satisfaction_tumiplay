<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\EmployeeResource;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    protected $model;

    public function __construct(Employee $employee)
    {
        $this->model = $employee;
    }

    public function getAllEmployees(int $perPage = 5): AnonymousResourceCollection
    {
        return EmployeeResource::collection($this->model->with('company')->paginate($perPage));
    }

}