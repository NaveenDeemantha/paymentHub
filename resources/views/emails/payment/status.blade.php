<!DOCTYPE html>
<html>

<head>
    <title>Payment Status Update</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 5px;">
        <h2 style="color: #41598a; text-align: center;">Payment Status Update</h2>

        <p>Dear Customer,</p>

        <p>Your payment for <strong>{{ $transaction->description }}</strong> has been processed.</p>

        <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p><strong>Transaction ID:</strong> {{ $transaction->client_ref }}</p>
            <p><strong>Amount:</strong> {{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}</p>
            <p><strong>Status:</strong>
                @if($transaction->status === 'completed')
                    <span style="color: green; font-weight: bold;">SUCCESS</span>
                @elseif($transaction->status === 'failed')
                    <span style="color: red; font-weight: bold;">FAILED</span>
                @else
                    <span style="color: orange; font-weight: bold;">{{ strtoupper($transaction->status) }}</span>
                @endif
            </p>
            <p><strong>Date:</strong> {{ $transaction->updated_at->format('Y-m-d H:i:s') }}</p>
        </div>

        @if($transaction->status === 'completed')
            <p>Thank you for your payment! Your transaction has been successfully completed.</p>
        @elseif($transaction->status === 'failed')
            <p>Unfortunately, your payment could not be processed. Please try again or contact support if the issue
                persists.</p>
        @else
            <p>Your payment is currently being processed. We will notify you once the status updates.</p>
        @endif

        <p style="margin-top: 30px; font-size: 0.9em; color: #777;">
            If you have any questions, please contact our support team.
        </p>

        <div style="text-align: center; margin-top: 20px; font-size: 0.8em; color: #999;">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>