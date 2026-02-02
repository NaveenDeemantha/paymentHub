# Multi-Client Payment Gateway - Quick Start Guide

## 🎉 Implementation Complete!

Your payment gateway now supports multiple client websites!

---

## What Was Added

### 1. **Client Management**
- Database table for managing client websites
- Each client gets unique API key for authentication
- Webhook support for payment notifications

### 2. **REST API**
- `POST /api/payment/initiate` - Create payment
- `GET /api/payment/status/{ref}` - Check status

### 3. **Client Dashboard**
- `/clients` - View all clients
- `/clients/create` - Add new client
- `/clients/{id}` - View client details & API key

---

## Getting Started

### Step 1: Access Client Management

1. Login to your gateway: `http://localhost:8000`
2. Navigate to: `http://localhost:8000/clients`
3. Click "Create Client" to add a new client website

### Step 2: Create a Client

Fill in the form:
- **Name**: Client website name (e.g., "My E-commerce Site")
- **Domain**: Client domain (e.g., "mystore.com")
- **Return URL**: Where to redirect after payment (e.g., "https://mystore.com/payment/result")
- **Webhook URL** (optional): For payment notifications

After creation, you'll get:
- **API Key**: `pk_xxxxxxxxxxxxx` (keep this secret!)
- **Webhook Secret**: `whsec_xxxxxxxxxxxxx` (for verifying webhooks)

### Step 3: Test the API

A demo client has been created. Get the API key:

```bash
php artisan tinker
```

Then run:
```php
$client = \App\Models\Client::first();
echo "API Key: " . $client->api_key;
echo "\nWebhook Secret: " . $client->webhook_secret;
```

---

## How Clients Use Your Gateway

### Example: From Client Website

```javascript
// 1. Client initiates payment
const response = await fetch('http://localhost:8000/api/payment/initiate', {
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
    metadata: { product_id: '789' }
  })
});

const data = await response.json();
console.log(data);
// {
//   "success": true,
//   "payment_url": "https://paycenter.com/payment?reqid=xxx",
//   "client_ref": "1-ORDER-12345-1706889600"
// }

// 2. Redirect customer to payment URL
window.location.href = data.payment_url;

// 3. User pays on Paycenter → Returns to client's return_url
// Example: https://mystore.com/payment/result?status=success&client_ref=1-ORDER-12345-1706889600
```

---

## Payment Flow Diagram

```
┌─────────────────┐
│ Client Website  │
└────────┬────────┘
         │ 1. API Call: POST /api/payment/initiate
         ↓
┌─────────────────┐
│  Your Gateway   │
└────────┬────────┘
         │ 2. Forward to Paycenter
         ↓
┌─────────────────┐
│   Paycenter     │ 3. User pays here
└────────┬────────┘
         │ 4. Callback to gateway
         ↓
┌─────────────────┐
│  Your Gateway   │ 5. Process result
└────────┬────────┘
         │ 6. Redirect back
         ↓
┌─────────────────┐
│ Client Website  │ 7. Show success/failure
└─────────────────┘
```

---

## API Endpoints

### POST /api/payment/initiate

**Request**:
```json
{
  "amount": 5000,
  "currency": "LKR",
  "order_id": "ORDER-123",
  "customer_email": "customer@example.com",
  "customer_phone": "+94771234567",
  "description": "Product purchase",
  "return_url": "https://clientsite.com/payment/result",
  "metadata": { "custom": "data" }
}
```

**Response**:
```json
{
  "success": true,
  "transaction_id": 45,
  "client_ref": "1-ORDER-123-1706889600",
  "payment_url": "https://paycenter.com/payment?reqid=xxx",
  "message": "Redirect user to payment_url to complete payment"
}
```

### GET /api/payment/status/{clientRef}

**Response**:
```json
{
  "success": true,
  "transaction": {
    "client_ref": "1-ORDER-123-1706889600",
    "amount": 5000,
    "status": "completed",
    "payment_state": "COMPLETED",
    "transaction_id": "PC123456789"
  }
}
```

---

## Webhook Notifications

If client provided webhook URL, they'll receive POST notifications:

**Payload**:
```json
{
  "event": "payment.completed",
  "client_ref": "1-ORDER-123-1706889600",
  "amount": 5000,
  "status": "completed",
  "transaction_id": "PC123456789",
  "metadata": { "custom": "data" }
}
```

**Verify Signature**:
```javascript
const crypto = require('crypto');

app.post('/webhooks/payment', (req, res) => {
  const signature = req.headers['x-webhook-signature'];
  const payload = JSON.stringify(req.body);
  const secret = 'whsec_your_webhook_secret';

  const expected = crypto
    .createHmac('sha256', secret)
    .update(payload)
    .digest('hex');

  if (signature === expected) {
    // Webhook is authentic
    console.log('Payment completed:', req.body.client_ref);
    res.sendStatus(200);
  } else {
    res.sendStatus(401);
  }
});
```

---

## Testing

1. **Get API key from demo client**:
   ```bash
   php artisan tinker
   \App\Models\Client::first()->api_key
   ```

2. **Test API with cURL**:
   ```bash
   curl -X POST http://localhost:8000/api/payment/initiate \
     -H "X-API-Key: pk_your_api_key" \
     -H "Content-Type: application/json" \
     -d '{"amount":5000,"order_id":"TEST-001","customer_email":"test@example.com"}'
   ```

3. **View in database**:
   ```bash
   php artisan tinker
   \App\Models\Transaction::latest()->first()
   ```

---

## Production Checklist

- [ ] Set up real Paycenter credentials in `.env`
- [ ] Deploy gateway to production server (e.g., `payments.yourdomain.com`)
- [ ] Enable HTTPS (required for production)
- [ ] Update callback URL in Paycenter merchant portal
- [ ] Create production clients via `/clients/create`
- [ ] Provide API keys to client websites
- [ ] Test end-to-end flow with real payment
- [ ] Monitor logs: `storage/logs/laravel.log`

---

## Managing Clients

**View All Clients**: `http://localhost:8000/clients`

**Create New Client**:
1. Click "Create Client"
2. Fill in details
3. Copy API key & webhook secret
4. Give to client developer

**Regenerate API Key**: On client detail page, click "Regenerate API Key"

**View Transactions**: Each client page shows their transaction history

---

## Benefits

✅ **One Paycenter account** serves all clients
✅ **Centralized transaction logging**
✅ **Easy onboarding** - just issue API key
✅ **Webhook support** for real-time notifications
✅ **Revenue tracking** per client
✅ **Security** - API keys, HMAC signatures

---

## Documentation

- **For Clients**: See [CLIENT_API_DOCUMENTATION.md](CLIENT_API_DOCUMENTATION.md)
- **For You**: All files are in place and working!

---

## Support

Your gateway is now ready to accept payments from multiple client websites! 🚀

Need help? Check the logs or review the API documentation.
