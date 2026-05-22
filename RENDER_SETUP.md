# Render + Flutter mobile setup

Production URL: **https://cycle-jgso.onrender.com**

Mobile API base URL: **https://cycle-jgso.onrender.com/api**

---

## Part 1 — Render environment variables

In [Render Dashboard](https://dashboard.render.com) → your **cycle** service → **Environment**:

### Required

| Key | Value | Notes |
|-----|--------|--------|
| `APP_NAME` | `Campus Mall` | |
| `APP_ENV` | `production` | |
| `APP_KEY` | `base64:...` | Run locally: `php artisan key:generate --show` and paste |
| `APP_DEBUG` | `false` | Never `true` in production |
| `APP_URL` | `https://cycle-jgso.onrender.com` | **No trailing slash** — fixes product image URLs in the app |
| `DB_CONNECTION` | `pgsql` | Matches Dockerfile (`pdo_pgsql`) |
| `DB_HOST` | *(from Render Postgres)* | Or use `DATABASE_URL` if you link a Render PostgreSQL database |
| `DB_PORT` | `5432` | |
| `DB_DATABASE` | *(your DB name)* | |
| `DB_USERNAME` | *(from Render)* | |
| `DB_PASSWORD` | *(from Render)* | |

If you attach a **Render PostgreSQL** instance, Render may set `DATABASE_URL` automatically. Laravel 11+ can read it when you map it in `config/database.php`, or copy host/user/password into the `DB_*` vars above.

### Sessions & cache (recommended on Render)

| Key | Value |
|-----|--------|
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `database` |

### Optional (features you use)

| Key | Value |
|-----|--------|
| `CLOUDINARY_URL` | `cloudinary://...` | Product/message images |
| `PAYPAL_MODE` | `sandbox` or `live` | |
| `PAYPAL_SANDBOX_CLIENT_ID` | *your id* | |
| `PAYPAL_SANDBOX_CLIENT_SECRET` | *secret* | |
| `RESEND_API_KEY` | *key* | If using email |

### After saving env vars

1. **Manual Deploy** (or push to Git if auto-deploy is on).
2. Ensure deploy runs migrations (your Dockerfile already runs `php artisan migrate --force`).

### Verify API after deploy

Open in a browser:

- https://cycle-jgso.onrender.com/up — should say application up
- https://cycle-jgso.onrender.com/api/health — should return `{"status":"ok",...}`

If `/api/health` is **404**, redeploy the latest code that includes `routes/api.php` and Sanctum.

---

## Part 2 — Flutter app connection

The app does **not** run on Render. It only needs your API URL.

### Production (APK / release build)

Already set in `campus_mall_mobile/lib/config/api_config.dart`:

```
https://cycle-jgso.onrender.com/api
```

Build:

```bash
cd campus_mall_mobile
flutter build apk --release
```

### Debug on a physical phone

Your PC’s localhost is not reachable from the phone. Either:

1. **Use Render** — Profile → Settings → API URL:
   `https://cycle-jgso.onrender.com/api`

2. **Or use local Laravel** on the same Wi‑Fi:
   `http://YOUR_PC_IP:8000/api`  
   Run: `php artisan serve --host=0.0.0.0 --port=8000`

### Debug on emulator

- Android emulator + local Laravel: `http://10.0.2.2:8000/api` (default in debug)
- Or set Render URL in Settings to test production

---

## Part 3 — Connection diagram

```
┌─────────────────┐         HTTPS          ┌──────────────────────────────┐
│  Flutter app    │  ───────────────────►  │  Render                      │
│  (user phone)   │  Authorization:       │  cycle-jgso.onrender.com     │
│                 │  Bearer {token}       │  ├── /        → web shop       │
│                 │                       │  └── /api/*  → mobile API      │
└─────────────────┘                       └──────────────────────────────┘
```

1. User opens app → loads products from `GET /api/home`
2. User registers/logs in → `POST /api/register` or `/api/login` → receives **token**
3. App stores token and sends it on cart/orders/profile requests

---

## Part 4 — Common issues

| Problem | Fix |
|---------|-----|
| App “network error” | Check API URL ends with `/api`, use `https://` |
| Images broken | Set `APP_URL=https://cycle-jgso.onrender.com` on Render |
| 404 on `/api/*` | Redeploy Laravel with API routes + `php artisan route:clear` |
| Slow first request | Render free tier cold start — wait ~30s and retry |
| Login works on web, not app | Use a **shopper** account; admin accounts are blocked on the API |

---

## Part 5 — Security on Render

- Remove or protect `GET /create-admin` in `routes/web.php` before relying on production.
- Keep `APP_DEBUG=false`.
- Do not commit `.env` to Git — only set variables in Render.
