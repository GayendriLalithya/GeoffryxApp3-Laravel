<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Work;
use App\Models\Payment;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    private $merchant_id;
    private $merchant_secret;

    public function __construct()
    {
        $this->merchant_id = env('PAYHERE_MERCHANT_ID');
        $this->merchant_secret = env('PAYHERE_MERCHANT_SECRET');
    }

    /**
     * Generate the hash to send with payment request
     */
    private function generateHash($order_id, $amount, $currency)
    {
        return strtoupper(
            md5(
                $this->merchant_id .
                $order_id .
                number_format($amount, 2, '.', '') .
                $currency .
                strtoupper(md5($this->merchant_secret))
            )
        );
    }

    /**
     * Initiate Payment Request to PayHere
     */
    public function initiatePayment(Request $request, $work_id)
    {
        // Fetch work details
        $work = Work::findOrFail($work_id);

        // Fetch user details
        $user = User::findOrFail($work->user_id);

        // Generate hash for the payment
        $hash = $this->generateHash("Project_" . $work->work_id, $work->budget, 'LKR');

        // Prepare payment data
        $paymentData = [
            'return_url' => route('payment.return'),
            'cancel_url' => route('payment.cancel'),
            'notify_url' => route('payment.notify'),
            'name' => $user->name,
            'email' => $user->email,
            'contact_no' => $user->contact_no,
            'address' => $user->address,
            'amount' => $work->budget,
            'merchant_id' => $this->merchant_id,
            'currency' => 'LKR',
            'hash' => $hash,
            'order_id' => "Project_" . $work->work_id,
            'items' => "Project " . $work->name,
        ];

        return view('pages.common.payment', compact('paymentData', 'work', 'user'));
    }

    /**
     * Handle Payment Completion
     */
    public function paymentComplete(Request $request)
    {
        DB::transaction(function () use ($request) {
            // Create a new payment record
            Payment::create([
                'work_id' => $request->work_id,
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'date' => now()->toDateString(),
                'time' => now()->toTimeString(),
            ]);

            // Retrieve the team and send notifications
            $team = Team::where('work_id', $request->work_id)->first();
            if ($team) {
                $teamMembers = TeamMember::where('team_id', $team->team_id)->get();

                // Send notifications to the customer and professionals
                foreach ($teamMembers as $teamMember) {
                    Log::info("Notification sent to Professional User ID: {$teamMember->user_id}");
                }
            }

            Log::info("Notification sent to Customer User ID: {$request->user_id}");
        });

        return redirect()->route('user.dashboard', ['tab' => 'projects'])
            ->with('alert-success', 'Payment completed successfully.');
    }

    public function paymentReturn(Request $request)
    {
        Log::info('Payment Return: ', $request->all());
        return redirect()->route('user.dashboard', ['tab' => 'projects'])
            ->with('alert-success', 'Payment completed successfully!');
    }

    public function paymentCancel(Request $request)
    {
        Log::warning('Payment Cancelled: ', $request->all());
        return redirect()->route('user.dashboard', ['tab' => 'projects'])
            ->with('alert-error', 'Payment was cancelled.');
    }

    public function paymentNotify(Request $request)
    {
        $merchant_id = $request->merchant_id;
        $order_id = $request->order_id;
        $payhere_amount = $request->payhere_amount;
        $payhere_currency = $request->payhere_currency;
        $status_code = $request->status_code;
        $md5sig = $request->md5sig;
    
        $local_md5sig = strtoupper(
            md5(
                $merchant_id .
                $order_id .
                $payhere_amount .
                $payhere_currency .
                $status_code .
                strtoupper(md5($this->merchant_secret))
            )
        );
    
        if ($local_md5sig === $md5sig && $status_code == 2) {
            Log::info('Payment successful: ', $request->all());
            return response()->json(['status' => 'success'], 200);
        } else {
            Log::error('Payment failed or invalid signature: ', $request->all());
            return response()->json(['status' => 'failure'], 400);
        }
    }

    public function executePayment(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'work_id' => 'required|integer|exists:work,work_id',
                'user_id' => 'required|integer|exists:users,user_id',
                'amount' => 'required|numeric|min:0',
            ]);
        
            // Call the stored procedure
            DB::statement('CALL ProcessPayment(?, ?, ?)', [
                $request->work_id,
                $request->user_id,
                $request->amount,
            ]);
        
            // Return a JSON response for successful payment
            return response()->json(['success' => true, 'message' => 'Payment completed successfully!']);
        } catch (\Exception $e) {
            Log::error('Payment execution failed:', ['error' => $e->getMessage()]);
        
            // Return a JSON response for errors
            return response()->json(['success' => false, 'message' => 'Payment failed. Please try again.'], 500);
        }
    }

}
