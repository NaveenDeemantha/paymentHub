# Paycenter Web 4.0 Integration - Laravel + Vue

A Laravel + Vue.js payment gateway application that integrates with **Paycenter Web 4.0** using the **Hosted Redirect Method** (Method #2).

## Features

✅ **Secure Hosted Redirect Integration** - No PCI compliance burden  
✅ **Transaction Management** - Full transaction logging and status tracking  
✅ **HMAC Security** - Request signature generation and verification  
✅ **User-Friendly UI** - Modern Vue.js frontend with Inertia.js  
✅ **Real-time Callbacks** - Automatic payment status verification  
✅ **Transaction History** - View all past transactions  

## Architecture

### Integration Method: Hosted Redirect (Method #2)

This implementation uses Paycenter's **Hosted Redirect** method:

1. **Payment Initiation**: User fills payment form → Laravel creates transaction → Calls Paycenter API
2. **Redirect to Paycenter**: User is redirected to Paycenter's secure hosted payment page
3. **Payment Processing**: User completes payment on Paycenter's platform (card details never touch our servers)
4. **Callback**: Paycenter redirects user back to our callback URL with payment result
5. **Verification**: Laravel verifies payment status with Paycenter API
6. **Result Display**: User sees success/failure page with transaction details

**Why Hosted Redirect?**
- 🔒 **No PCI Compliance** required on your servers
- 🛡️ **Secure** - Card details never touch your application
- 🚀 **Easy to implement** for multiple merchants
- ✅ **Recommended** by Paycenter for non-PCI merchants

## Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Paycenter Web 4.0 merchant account

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/NaveenDeemantha/paycenter.git
cd paycenter
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Configure your environment variables:

```ini
APP_NAME="Paycenter Gateway"
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paycenter
DB_USERNAME=root
DB_PASSWORD=your_password

# Paycenter Web 4.0 Configuration
PAYCENTER_CLIENT_ID=your_client_id_here
PAYCENTER_AUTH_TOKEN=your_auth_token_here
PAYCENTER_ENDPOINT_URL=https://sampath.paycorp.lk/rest/service/proxy
PAYCENTER_HMAC_SECRET=your_hmac_secret_here
```

**Important**: Replace the Paycenter credentials with your actual values from Paycenter merchant portal.

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Database Migrations

```bash
php artisan migrate
```

This will create the `transactions` table for logging all payment transactions.

### 7. Build Frontend Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### 8. Start the Application

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Usage

### Making a Payment

1. **Register/Login** to the application
2. Navigate to `/pay` or use the payment link in the dashboard
3. Fill in payment details:
   - Amount (required)
   - Order ID (optional - auto-generated if empty)
   - Description (optional)
   - Email & Phone (optional)
4. Click **"Proceed to Payment"**
5. You'll be **redirected to Paycenter's secure payment page**
6. Complete payment on Paycenter's platform
7. After payment, you'll be **redirected back** to see the result

<!-- ### Payment Flow

```
User fills form → Laravel creates transaction → Redirect to Paycenter
                                                      ↓
User sees result ← Laravel verifies status ← Paycenter redirects back
```

### Viewing Transactions

- All transactions are logged in the `transactions` table
- Each transaction includes:
  - Client reference (Order ID)
  - Paycenter request ID (`reqid`)
  - Transaction ID from Paycenter
  - Amount and currency
  - Status (pending, processing, completed, failed)
  - Payment state from Paycenter
  - Request and response data (JSON)
  - Timestamps

## API Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/pay` | Show payment initiation form |
| POST | `/pay/initiate` | Initiate payment and redirect to Paycenter |
| GET | `/pay/callback` | Handle callback from Paycenter after payment |

All routes require authentication (`auth` middleware). -->
<!-- 
## Configuration

### Paycenter Configuration File

[config/paycenter.php](config/paycenter.php)

```php
return [
    'client_id' => env('PAYCENTER_CLIENT_ID'),
    'auth_token' => env('PAYCENTER_AUTH_TOKEN'),
    'endpoint_url' => env('PAYCENTER_ENDPOINT_URL'),
    'hmac_secret' => env('PAYCENTER_HMAC_SECRET'),
];
```

## Security Features

### HMAC Signature

The implementation includes HMAC (Hash-based Message Authentication Code) for request authentication:

- **Generation**: `hash_hmac('sha256', clientId + requestDate, hmacSecret)`
- **Verification**: Used in callback handling to ensure requests are from Paycenter
- **Location**: `app/Services/PaycenterService.php`

### Request Validation

- All payment requests are validated
- User authentication required
- Transaction logging for audit trail

## Development

### Running Tests

```bash
php artisan test
```

### Code Structure

```
app/
├── Http/Controllers/
│   └── PayController.php          # Payment endpoints
├── Models/
│   └── Transaction.php            # Transaction model
└── Services/
    └── PaycenterService.php       # Paycenter API integration

resources/js/Pages/Paycenter/
├── Index.vue                      # Payment form
├── Payment.vue                    # Redirect loader (legacy)
└── Result.vue                     # Payment result page

database/migrations/
└── 2024_01_01_000001_create_transactions_table.php
```

### PaycenterService Methods

- `initiatePayment($data)` - Initiate payment and get redirect URL
- `verifyPayment($reqid)` - Verify payment status after callback
- `generateHmac()` - Generate HMAC signature for security
- `verifyHmac()` - Verify HMAC from Paycenter callbacks

## Troubleshooting

### Payment Initiation Fails

1. Check Paycenter credentials in `.env`
2. Verify endpoint URL is correct
3. Check Laravel logs: `storage/logs/laravel.log`
4. Enable debug mode: `APP_DEBUG=true`

### Callback Not Working

1. Ensure callback URL is accessible from internet
2. Check route is registered: `php artisan route:list`
3. Verify CSRF token handling (should be excluded for callbacks)
4. Check Paycenter merchant portal callback configuration

### Database Errors

1. Verify database credentials
2. Run migrations: `php artisan migrate`
3. Check MySQL is running

## Production Deployment

### Important Steps

1. **Set `APP_ENV=production`** in `.env`
2. **Set `APP_DEBUG=false`**
3. **Configure proper APP_URL** (your production domain)
4. **Update Paycenter callback URL** in merchant portal
5. **Use HTTPS** for security
6. **Optimize autoloader**: `composer install --optimize-autoloader --no-dev`
7. **Cache configuration**: `php artisan config:cache`
8. **Cache routes**: `php artisan route:cache`
9. **Build assets**: `npm run build`

### Environment Variables for Production

```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

PAYCENTER_ENDPOINT_URL=https://sampath.paycorp.lk/rest/service/proxy
# Use production credentials from Paycenter
```

## Support

For issues related to:
- **This implementation**: Open an issue on GitHub
- **Paycenter API**: Contact Paycenter support
- **Laravel/Vue**: Check official documentation

## License

This project is open-source. See LICENSE file for details.

## Credits -->

Developed with Laravel 10, Vue 3, Inertia.js, and Tailwind CSS.  
Paycenter Web 4.0 Integration by NaveenDeemantha
