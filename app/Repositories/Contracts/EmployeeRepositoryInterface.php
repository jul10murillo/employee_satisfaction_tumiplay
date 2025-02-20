<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface EmployeeRepositoryInterface
{
    /**
     * Retrieve a paginated list of all employees.
     * 
     * @param int $perPage The number of items to return per page. Defaults to 5.
     * 
     * @return AnonymousResourceCollection A collection of EmployeeResource objects
     */
    public function getAllEmployees(int $perPage = 5): AnonymousResourceCollection;

    /**
     * Retrieve a paginated list of employees filtered by the given criteria.
     * 
     * @param array $filters An associative array of column names to search values.
     *     Example: ['name' => 'John', 'email' => 'example.com']
     * 
     * @return AnonymousResourceCollection A collection of EmployeeResource objects
     */
    public function searchEmployees(array $filters): AnonymousResourceCollection;

    /**
     * Add an employee to the user's favorites.
     * 
     * @param int $employeeId The ID of the employee to be added to the user's favorites
     * 
     * @return array An associative array containing the message and the list of favorited employees
     */
    public function addFavorite(int $employeeId): array;

    /**
     * Remove an employee from the user's favorites.
     * 
     * @param int $employeeId The ID of the employee to be removed from the user's favorites
     * 
     * @return array An associative array containing the message and the list of favorited employees
     */
    public function removeFavorite(int $employeeId): array;

    /**
     * Get the list of favorited employees, including the count.
     *
     * @return array An associative array containing:
     *     'data' => An EmployeeResource collection of the favorited employees,
     *     'count' => The count of favorited employees
     */
    public function getFavorites(): array;
}