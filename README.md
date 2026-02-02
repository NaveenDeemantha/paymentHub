# Paycenter Web 4.0 Integration

This is a Laravel + Vue.js application integrated with Paycenter Web 4.0 for payment processing.

## Setup

1.  **Clone the repository**:
    ```bash
    git clone https://github.com/NaveenDeemantha/paycenter.git
    cd paycenter
    ```

2.  **Install Dependencies**:
    ```bash
    composer install
    npm install
    ```

3.  **Environment Configuration**:
    Copy `.env.example` to `.env` and configure your database and Paycenter credentials:
    ```ini
    DB_DATABASE=paycenter
    
    PAYCENTER_CLIENT_ID=your_client_id
    PAYCENTER_AUTH_TOKEN=your_auth_token
    PAYCENTER_ENDPOINT_URL=https://sampath.paycorp.lk/rest/service/proxy
    ```

4.  **Run Migrations**:
    ```bash
    php artisan migrate
    ```

5.  **Build Frontend**:
    ```bash
    npm run build
    ```

6.  **Serve**:
    ```bash
    php artisan serve
    ```

## Usage

- Navigate to `/pay` to initiate a payment.
- The system uses `PaycenterService` to communicate with the Paycorp API.
