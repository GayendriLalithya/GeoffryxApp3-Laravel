<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManageUserController extends Controller
{
    public function editUser($id)
{
    $user = User::findOrFail($id); // Find the user by ID
    return view('user_edit', compact('user')); // Pass the user data to an edit form
}

public function deleteUser($id)
{
    $user = User::findOrFail($id); // Find the user by ID
    $user->deleted = true; // Soft delete the user by setting `deleted` to true
    $user->save();

    return redirect()->route('user.details')->with('success', 'User deleted successfully.');
}

}
