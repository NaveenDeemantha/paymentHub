# Payment Gateway API Documentation

## Overview

This API allows you to integrate payment processing into your website using our centralized payment gateway.

## Authentication

All API requests require an API key in the header:

```
X-API-Key: pk_your_api_key_here
```

Or as a query parameter:

```
?api_key=pk_your_api_key_here
```

## Base URL

**Development**: `http://localhost:8000/api`
**Production**: `https://payments.yourdomain.com/api`

---

## Endpoints

### 1. Initiate Payment

Creates a payment and returns a redirect URL to send your customer to.

**Endpoint**: `POST /api/payment/initiate`

**Headers**:
```
X-API-Key: pk_your_api_key_here
Content-Type: application/json
```

**Request Body**:
```json
{
  "amount": 5000,
  "currency": "LKR",
  "order_id": "ORDER-12345",
  "description": "Purchase of Product XYZ",
  "customer_email": "customer@example.com",
  "customer_phone": "+94771234567",
  "return_url": "https://yourwebsite.com/payment/success",
  "metadata": {
    "product_id": "123",
    "user_id": "456"
  }
}
```

**Parameters**:
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| amount | number | Yes | Payment amount |
| currency | string | No | Currency code (default: LKR) |
| order_id | string | Yes | Your unique order reference |
| description | string | No | Payment description |
| customer_email | string | No | Customer email |
| customer_phone | string | No | Customer phone |
| return_url | string | No | Override default return URL |
| metadata | object | No | Custom data to store with transaction |

**Response** (Success - 200):
```json
{
  "success": true,
  "transaction_id": 123,
  "client_ref": "5-ORDER-12345-1706889600",
  "payment_url": "https://paycenter.example.com/payment?reqid=xxx",
  "message": "Redirect user to payment_url to complete payment"
}
```

**Response** (Error - 401):
```json
{
  "success": false,
  "message": "Invalid or inactive client API key"
}
```

**Response** (Validation Error - 422):
```json
{
  "success": false,
  "errors": {
    "amount": ["The amount field is required."],
    "order_id": ["The order id field is required."]
  }
}
```

---

### 2. Check Payment Status

Check the status of a payment transaction.

**Endpoint**: `GET /api/payment/status/{clientRef}`

**Headers**:
```
X-API-Key: pk_your_api_key_here
```

**Response** (Success - 200):
```json
{
  "success": true,
  "transaction": {
    "id": 123,
    "client_ref": "5-ORDER-12345-1706889600",
    "amount": 5000,
    "currency": "LKR",
    "status": "completed",
    "payment_state": "COMPLETED",
    "transaction_id": "PC123456789",
    "created_at": "2026-02-02T10:30:00Z",
    "completed_at": "2026-02-02T10:35:00Z",
    "metadata": {
      "product_id": "123",
      "user_id": "456"
    }
  }
}
```

**Transaction Status Values**:
- `pending` - Payment not yet initiated
- `processing` - User redirected to payment page
- `completed` - Payment successful
- `failed` - Payment failed or cancelled

---

## Integration Flow

### Step 1: Initiate Payment

When a customer wants to pay on your website:

```javascript
// Frontend - When user clicks "Pay Now"
const initiatePayment = async () => {
  const response = await fetch('https://payments.yourdomain.com/api/payment/initiate', {
    method: 'POST',
    headers: {
      'X-API-Key': 'pk_your_api_key_here',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      amount: 5000,
      order_id: 'ORDER-12345',
      customer_email: 'customer@example.com',
      description: 'Product Purchase',
      return_url: 'https://yourwebsite.com/payment/result',
      metadata: {
        cart_id: '789'
      }
    })
  });

  const data = await response.json();

  if (data.success) {
    // Redirect user to payment page
    window.location.href = data.payment_url;
  } else {
    alert('Payment initiation failed: ' + data.message);
  }
};
```

### Step 2: User Pays

- User is redirected to Paycenter's secure payment page
- User enters card details and completes payment
- Paycenter processes the payment

### Step 3: Return to Your Website

After payment, user is redirected back to your `return_url` with query parameters:

**Success**:
```
https://yourwebsite.com/payment/result?status=success&client_ref=5-ORDER-12345-1706889600&transaction_id=PC123456789&amount=5000&currency=LKR
```

**Failed**:
```
https://yourwebsite.com/payment/result?status=failed&client_ref=5-ORDER-12345-1706889600&payment_state=DECLINED
```

### Step 4: Handle Return URL

```javascript
// On your return URL page
const urlParams = new URLSearchParams(window.location.search);
const status = urlParams.get('status');
const clientRef = urlParams.get('client_ref');
const transactionId = urlParams.get('transaction_id');

if (status === 'success') {
  // Payment successful!
  // Update your order status
  console.log('Payment completed:', transactionId);
} else {
  // Payment failed
  console.log('Payment failed');
}

// Optional: Verify status via API
const verifyPayment = async () => {
  const response = await fetch(
    `https://payments.yourdomain.com/api/payment/status/${clientRef}?api_key=pk_your_api_key_here`
  );
  const data = await response.json();
  console.log('Verified status:', data.transaction.status);
};
```

---

## Webhooks (Optional)

If you provided a `webhook_url` during client registration, you'll receive POST notifications when payment status changes.

**Webhook Payload**:
```json
{
  "event": "payment.completed",
  "transaction_id": 123,
  "client_ref": "5-ORDER-12345-1706889600",
  "amount": 5000,
  "currency": "LKR",
  "status": "completed",
  "payment_state": "COMPLETED",
  "paycenter_transaction_id": "PC123456789",
  "completed_at": "2026-02-02T10:35:00Z",
  "metadata": {
    "product_id": "123"
  }
}
```

**Webhook Events**:
- `payment.completed` - Payment successful
- `payment.failed` - Payment failed

**Webhook Headers**:
```
X-Webhook-Signature: abc123def456...
Content-Type: application/json
```

**Verify Webhook Signature**:
```javascript
const crypto = require('crypto');

const verifyWebhook = (payload, signature, secret) => {
  const expectedSignature = crypto
    .createHmac('sha256', secret)
    .update(JSON.stringify(payload))
    .digest('hex');

  return signature === expectedSignature;
};
```

---

## Testing

**Test Credentials**: Available after client registration

**Test Card Numbers**: Provided by Paycenter

---

## Error Codes

| Code | Description |
|------|-------------|
| 401 | Invalid API key |
| 404 | Transaction not found |
| 422 | Validation error |
| 400 | Payment initialization failed |

---

## Support

For API issues, contact: support@yourdomain.com

For Paycenter payment issues, contact Paycenter support.
