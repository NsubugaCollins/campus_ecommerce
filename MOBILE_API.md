# Campus Mall Mobile API

REST API for the Flutter app (`../campus_mall_mobile`). Uses **Laravel Sanctum** bearer tokens.

## Setup

```bash
php artisan migrate   # includes personal_access_tokens table
php artisan serve --host=0.0.0.0 --port=8000
```

Set `APP_URL` in `.env` so product image URLs resolve correctly for the app.

## Authentication

| Method | Endpoint | Body |
|--------|----------|------|
| POST | `/api/login` | `email`, `password` |
| POST | `/api/register` | `name`, `email`, `password`, `password_confirmation`, optional `phone`, `referral_code` |
| POST | `/api/logout` | Bearer token required |
| GET | `/api/user` | Bearer token required |

Header for protected routes: `Authorization: Bearer {token}`

## Public

- `GET /api/health`
- `GET /api/home` — flash sales, recommended, categories
- `GET /api/categories`
- `GET /api/products?search=&category=&page=`
- `GET /api/products/{id}`
- `GET /api/categories/{category}/products`

## Authenticated (shopper only, not admin)

- Cart: `GET/POST /api/cart`, `PATCH/DELETE /api/cart/{cartItem}`
- Orders: `GET /api/orders`, `POST /api/orders`, `GET /api/orders/{order}`
- Profile: `GET/PUT /api/profile`, `PUT /api/profile/password`

### User-only (requires `user` role)

- Messages: `GET/POST /api/messages`, react/delete as documented in `routes/api.php`
- Trade-in: `GET/POST /api/trade-in`, accept/reject offers
- Ratings: `POST /api/ratings`
- PayPal: `POST /api/orders/{order}/paypal` → `{ approval_url }`

### Admin-only (`/api/admin/*`, requires `admin` role)

- Dashboard, analytics, earnings
- Products CRUD, orders, users, settings
- Message threads with customers
- Trade-in management (offers & status)

Checkout body for `POST /api/orders`:

```json
{
  "shipping_address": "Hall A, Room 12",
  "payment_method": "cash",
  "points_to_use": 0
}
```

`payment_method`: `cash` or `paypal` (PayPal completion still via web for now).
