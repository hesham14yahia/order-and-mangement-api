# Order Management API

A Laravel-based REST API for managing orders, order items, and payment processing with support for multiple payment gateways.

## Features

- **Order Management**: Create and manage orders with order items
- **Payment Processing**: Support for multiple payment methods (Credit Card, PayPal)
- **Payment Tracking**: Track payment status and transaction references
- **User Management**: User authentication and order association
- **API Documentation**: Built-in API documentation with Scramble
- **Testing**: PHPUnit test and feature test

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd order-and-mangement-api
```

2. Create environment file:
```bash
cp .env.example .env
```

3. Generate application key:
```bash
php artisan key:generate
```

4. Run migrations:
```bash
php artisan migrate
```

## Development

### Start Development Server

```bash
php artisan serve
```
The API will be available at `http://localhost:8000`


## API Endpoints test and Documentation
`http://localhost:8000/docs/api`

## Testing
Run all tests:
```bash
php artisan test
```

## Code Quality
Format code with Pint:
```bash
./vendor/bin/pint
```
