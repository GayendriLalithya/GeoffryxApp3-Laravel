<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Checkout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script> -->
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Payment Checkout</h4>
            </div>
            <div class="card-body">
                <h5>User Information</h5>
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Contact:</strong> {{ $user->contact_no }}</p>
                <p><strong>Address:</strong> {{ $user->address }}</p>
                <hr>
                <h5>Project Information</h5>
                <p><strong>Project Name:</strong> {{ $work->name }}</p>
                <p><strong>Location:</strong> {{ $work->location }}</p>
                <p><strong>Amount:</strong> LKR {{ $work->budget }}</p>
                <hr>
                <button class="btn btn-success" id="payhere-payment">Pay with PayHere</button>
            </div>
        </div>
    </div>

    <script>
        payhere.onCompleted = function onCompleted(orderId) {
            alert("Payment completed! Order ID: " + orderId);
        };
        payhere.onDismissed = function onDismissed() {
            alert("Payment was dismissed. Please try again.");
        };
        payhere.onError = function onError(error) {
            alert("An error occurred: " + error);
        };
        var payment = {
            "merchant_id": "{{ $paymentData['merchant_id'] }}",
            "order_id": "{{ $paymentData['order_id'] }}",
            "items": "{{ $paymentData['items'] }}",
            "amount": "{{ $paymentData['amount'] }}",
            "currency": "{{ $paymentData['currency'] }}",
            "name": "{{ $paymentData['name'] }}",
            "email": "{{ $paymentData['email'] }}",
            "contact_no": "{{ $paymentData['contact_no'] }}",
            "address": "{{ $paymentData['address'] }}",
            "return_url": "{{ $paymentData['return_url'] }}",
            "cancel_url": "{{ $paymentData['cancel_url'] }}",
            "notify_url": "{{ $paymentData['notify_url'] }}",
        };
        document.getElementById('payhere-payment').onclick = function () {
            payhere.startPayment(payment);
        };
    </script>

<script>
    document.getElementById('payhere-payment').onclick = async function () {
        try {
            const response = await fetch("{{ route('payment.execute') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    work_id: {{ $work->work_id }},
                    user_id: {{ $user->user_id }},
                    amount: {{ $work->budget }}
                })
            });

            // Handle JSON response for success
            const result = await response.json();
            if (result.success) {
                alert('Payment completed successfully!');
                window.location.href = "{{ route('user.dashboard', ['tab' => 'projects']) }}";
            } else {
                alert(result.message || 'Payment failed. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An unexpected error occurred. Please try again.');
        }
    };
</script>

</body>
</html>
