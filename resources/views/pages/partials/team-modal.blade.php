@php

    use Illuminate\Support\Facades\DB;

    $teamMembersByTeamId = [];

    foreach ($teams as $team) {
        // Call the stored procedure and pass the team_id
        $teamMembers = DB::select('CALL GetTeamMembersByTeamId(?)', [$team->team_id]);

        // Add member tasks manually to each team member
        foreach ($teamMembers as $member) {
            $member->memberTasks = DB::select('SELECT * FROM member_tasks WHERE team_member_id = ?', [$member->team_member_id]);
        }

        $teamMembersByTeamId[$team->team_id] = $teamMembers;
    }
@endphp

@if ($teams->isNotEmpty())
    @foreach ($teams as $team)
        <!-- Team Members Modal -->
        <div class="modal fade" id="teamModal-{{ $team->team_id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $team->work->name ?? 'N/A' }} - Team Members</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-card">
                            <h5>Team Members</h5>
                            <div class="row mb-2 p-1" style="background-color: #e6fbff;">
                                <div class="col-3">
                                    <label><b>Name</b></label>
                                </div>
                                <div class="col-3">
                                    <label><b>Professional Type</b></label>
                                </div>
                                <div class="col-3">
                                    <label><b>Work Status</b></label>
                                </div>
                                <div class="col-3">
                                    <label><b>Actions</b></label>
                                </div>
                            </div>
                            @foreach ($teamMembersByTeamId[$team->team_id] as $member)
                                <div class="row mb-2 p-1">
                                    <div class="col-3">
                                        <span>{{ $member->member_name }}</span>
                                    </div>
                                    <div class="col-3">
                                        <span>{{ $member->professional_type ?? 'Not a Professional' }}</span>
                                    </div>
                                    <div class="col-3">
                                        <span>{{ ucfirst($member->member_status) }}</span>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#taskList-{{ $member->team_member_id }}">Tasks</button>
                                    </div>
                                </div>

                                <div id="taskList-{{ $member->team_member_id }}" class="collapse">
                                    <div class="task-list">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($member->memberTasks as $task)
                                                    <tr>
                                                        <td>{{ $task->description }}</td>
                                                        <td>{{ ucfirst($task->status) }}</td>
                                                        <td>
                                                            <form method="POST" action="{{ route('tasks.update', $task->member_task_id) }}" style="display:inline-block;">
                                                                @csrf
                                                                @method('PUT')
                                                                <select name="status" class="form-select d-inline-block w-auto" {{ $task->status == 'done' ? 'disabled' : '' }}>
                                                                    <option value="not started" {{ $task->status == 'not started' ? 'selected' : '' }}>Not Started</option>
                                                                    <option value="in progress" {{ $task->status == 'in progress' ? 'selected' : '' }}>In Progress</option>
                                                                    <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Done</option>
                                                                </select>
                                                                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i></button>
                                                            </form>
                                                            <form method="POST" action="{{ route('tasks.delete', $task->member_task_id) }}" style="display:inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if ($member->user_id == $userId)
                                            <form method="POST" action="{{ route('tasks.store') }}">
                                                @csrf
                                                <input type="hidden" name="team_member_id" value="{{ $member->team_member_id }}">
                                                <input type="hidden" name="team_id" value="{{ $team->team_id }}">
                                                <div class="row mb-2">
                                                    <div class="col-6">
                                                        <input type="text" name="description" class="form-control" placeholder="New Task Description" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <button type="submit" class="btn btn-success btn-sm">Add Task</button>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    
                        <div class="row mt-3">
                            <div class="col">
                                <label>Project Status</label>
                            </div>
                            <div class="col">
                                @php
                                    // Calculate project status
                                    $totalMembers = $teamMembersByTeamId[$team->team_id] ? count($teamMembersByTeamId[$team->team_id]) : 0;
                                    $completedMembers = collect($teamMembersByTeamId[$team->team_id])->where('member_status', 'completed')->count();
                                    $notStartedMembers = collect($teamMembersByTeamId[$team->team_id])->where('member_status', 'not started')->count();

                                    // Determine project status and color
                                    if ($totalMembers === $completedMembers) {
                                        $statusText = 'Completed';
                                        $statusColor = 'green';
                                    } elseif ($totalMembers === $notStartedMembers) {
                                        $statusText = 'Not Started';
                                        $statusColor = 'red';
                                    } else {
                                        $statusText = 'In Progress';
                                        $statusColor = 'orange';
                                    }
                                @endphp
                                <span style="color: {{ $statusColor }}; font-weight: bold;">{{ $statusText }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="{{ route('group-chat.view', ['id' => $team->work->work_id, 'email' => Auth::user()->email]) }}" 
                           class="btn btn-primary" 
                           target="_blank">
                            Group Chat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <p>No teams found for this user.</p>
@endif

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
