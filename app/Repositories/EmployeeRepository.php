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
        $query = $this->model->with('company'); // 🔹 Asegurar que carga la relación
        foreach ($filters as $key => $value) {
            $query->where($key, 'LIKE', "%$value%");
        }

        $employees = $query->paginate(5);
        //dd($employees->toArray());
        return $this->formatPaginationResponse($employees);
    }

    public function getFavorites(): array
    {
        $favorites = Cache::get('favorites', []);
        return [
            'data' => EmployeeResource::collection(Employee::whereIn('id', $favorites)->get()),
            'count' => count($favorites),
        ];
    }

    public function addFavorite(int $employeeId): array
    {
        $favorites = Cache::get('favorites', []);

        if (!in_array($employeeId, $favorites)) {
            $favorites[] = $employeeId;
            Cache::put('favorites', $favorites, now()->addDays(7)); // Expira en 7 días
        }

        return ['message' => 'Empleado añadido a favoritos', 'favorites' => $favorites];
    }

    public function removeFavorite(int $employeeId): array
    {
        $favorites = array_filter(Cache::get('favorites', []), fn($id) => $id !== $employeeId);
        Cache::put('favorites', $favorites, now()->addDays(7));

        return ['message' => 'Empleado eliminado de favoritos', 'favorites' => $favorites];
    }


    /**
     * Format the pagination response from Laravel's LengthAwarePaginator.
     *
     * @param LengthAwarePaginator $employees
     * @return array
     */
    private function formatPaginationResponse(LengthAwarePaginator $employees): AnonymousResourceCollection
    {
        //dd($employees->toArray());
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