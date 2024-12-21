<div class="container">
    <h2 class="mt-4 mb-4">Payment Records</h2>

    @php
        use Illuminate\Support\Facades\Auth;
        use App\Models\Payment;

        $userId = Auth::id(); // Get the logged-in user ID
        $payments = Payment::where('user_id', $userId)->get(); // Fetch payment records
    @endphp

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Work ID</th>
                    <th>User ID</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_id }}</td>
                        <td>{{ $payment->work_id }}</td>
                        <td>{{ $payment->user_id }}</td>
                        <td>LKR {{ number_format($payment->amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->date)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->time)->format('H:i:s') }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d H:i:s') }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->updated_at)->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No payment records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>