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

    /**
     * Create a new repository instance.
     *
     * @param  Employee  $employee
     * @return void
     */
    public function __construct(Employee $employee)
    {
        $this->model = $employee;
    }

    /**
     * Retrieve a paginated list of all employees with their associated companies.
     *
     * @param int $perPage The number of employees per page.
     * @return AnonymousResourceCollection A resource collection containing the paginated employees and pagination details.
     */

    public function getAllEmployees(int $perPage = 5): AnonymousResourceCollection
    {
        $employees = $this->model->with('company')->paginate($perPage);

        return $this->formatPaginationResponse($employees);
    }

    /**
     * Retrieve a paginated list of employees filtered by the given criteria.
     *
     * @param array $filters An associative array of column names to search values.
     *     Example: ['name' => 'John', 'email' => 'example.com']
     * @return AnonymousResourceCollection A resource collection containing the paginated filtered employees and pagination details.
     */
    public function searchEmployees(array $filters): AnonymousResourceCollection
    {
        $query = $this->model->with('company'); // 🔹 Asegurar que carga la relación
        foreach ($filters as $key => $value) {
            $query->where($key, 'LIKE', "%$value%");
        }

        $employees = $query->paginate(5);
        return $this->formatPaginationResponse($employees);
    }

    /**
     * Get the list of favorited employees, including the count.
     *
     * @return array An associative array containing:
     *     'data' => An EmployeeResource collection of the favorited employees,
     *     'count' => The count of favorited employees
     */
    public function getFavorites(): array
    {
        $favorites = Cache::get('favorites', []);
        return [
            'data' => EmployeeResource::collection(Employee::whereIn('id', $favorites)->get()),
            'count' => count($favorites),
        ];
    }

    /**
     * Add an employee to the user's list of favorites.
     *
     * @param int $employeeId Employee ID to be added to favorites.
     * @return array An associative array containing:
     *     'message' => A confirmation message,
     *     'favorites' => The updated list of favorited employee IDs.
     */

    public function addFavorite(int $employeeId): array
    {
        $favorites = Cache::get('favorites', []);

        if (!in_array($employeeId, $favorites)) {
            $favorites[] = $employeeId;
            Cache::put('favorites', $favorites, now()->addDays(7)); // Expira en 7 días
        }

        return ['message' => 'Empleado añadido a favoritos', 'favorites' => $favorites];
    }

    /**
     * Remove an employee from the user's list of favorites.
     *
     * @param int $employeeId Employee ID to be removed from favorites.
     * @return array An associative array containing:
     *     'message' => A confirmation message,
     *     'favorites' => The updated list of favorited employee IDs.
     */

    public function removeFavorite(int $employeeId): array
    {
        $favorites = array_filter(Cache::get('favorites', []), fn($id) => $id !== $employeeId);
        Cache::put('favorites', $favorites, now()->addDays(7));

        return ['message' => 'Empleado eliminado de favoritos', 'favorites' => $favorites];
    }



    /**
     * Format the pagination response of an employee list into an AnonymousResourceCollection.
     *
     * This method takes a LengthAwarePaginator object and returns an AnonymousResourceCollection
     * containing the employees, with pagination data included in the 'pagination' key.
     *
     * @param LengthAwarePaginator $employees A LengthAwarePaginator object
     * @return AnonymousResourceCollection A collection of EmployeeResource objects
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