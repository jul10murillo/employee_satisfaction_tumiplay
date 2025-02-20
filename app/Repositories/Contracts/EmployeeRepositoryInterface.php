<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\Employee;

interface EmployeeRepositoryInterface
{
    public function getAllEmployees(int $perPage = 5): AnonymousResourceCollection;
    public function searchEmployees(array $filters): AnonymousResourceCollection;

}