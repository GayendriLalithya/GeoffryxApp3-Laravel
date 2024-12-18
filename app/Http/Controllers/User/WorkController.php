<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class WorkController extends Controller
{
    public function store(Request $request)
    {
        // Check if the cancel button was clicked
        if ($request->has('cancel')) {
            return redirect()->route('user.dashboard', ['tab' => 'profile'])
                             ->with('alert-error', 'Project creation was cancelled.');
        }

        try {
            // Validate the incoming request data
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'budget' => 'required|numeric',
                'requirements' => 'required|string',
                'professionals' => 'required|array'
            ]);

            // Retrieve the authenticated user's ID
            $userId = auth()->id();
            // Encode the professional IDs as a JSON string
            $professionalIds = json_encode($data['professionals']);

            // Execute the stored procedure to create the project
            DB::statement('CALL create_project_with_professionals(?, ?, ?, ?, ?, ?, ?, ?)', [
                $userId,
                $data['name'],
                $data['location'],
                $data['start_date'],
                $data['end_date'],
                $data['budget'],
                $data['requirements'],
                $professionalIds
            ]);

            // Redirect back with a success message
            return redirect()->route('user.dashboard', ['tab' => 'professional'])
                             ->with('alert-success', 'Project created successfully.');

        } catch (Exception $e) {
            // Check if the exception message matches the custom signal
            if (strpos($e->getMessage(), 'Cannot select yourself as a professional for the project.') !== false) {
                // Display only the custom error message
                return redirect()->back()->with('alert-error', 'Cannot select yourself as a professional for the project.');
            }

            // Display a generic error message for other exceptions
            return redirect()->back()->with('alert-error', 'An unexpected error occurred. Please try again.');
        }
    }
}
