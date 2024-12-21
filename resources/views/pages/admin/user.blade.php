<div class="container">
    <h2 class="mt-4 mb-4">User Details</h2>

    @php
        use App\Models\User;

        // Fetch all users where deleted is false
        $users = User::where('deleted', false)->get();
    @endphp

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact No</th>
                    <th>User Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->contact_no }}</td>
                        <td>{{ ucfirst($user->user_type) }}</td>
                        <td>
                            <!-- Update Button -->
                            <a href="{{ route('user.edit', ['id' => $user->user_id]) }}" class="btn btn-sm btn-primary">Update</a>
                            
                            <!-- Delete Button -->
                            <form action="{{ route('user.delete', ['id' => $user->user_id]) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>