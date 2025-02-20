<?php

namespace App\Http\Controllers\Employee;

use App\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AddFavoriteController
{
    protected $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * Add an employee to the user's favorites.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validatedData = $request->validate([
                'employee_id' => 'required|exists:employees,id'
            ]);

            // Add the employee to favorites
            $result = $this->employeeRepository->addFavorite($validatedData['employee_id']);

            // Return a success response
            return response()->json([
                'message' => 'Employee added to favorites successfully.',
                'data' => $result
            ], 200);

        } catch (ValidationException $e) {
            // Return a validation error response
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Return a general error response
            return response()->json([
                'message' => 'An error occurred while adding the employee to favorites.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
