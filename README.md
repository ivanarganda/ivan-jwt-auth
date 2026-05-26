# ivan/jwt-auth

A lightweight PHP REST API starter with **PDO + MySQL**, **dynamic user registration** (adapts to your `users` table columns), **interactive API docs**, and a clear path toward **JWT authentication**.

---

## What you get today

| Feature | Status |
|--------|--------|
| `POST /register` — create users (hashed passwords, dynamic columns) | ✅ Live |
| `GET /show` — list users with pagination | ✅ Live |
| Interactive documentation at `/` | ✅ Live |
| `POST /login` + JWT | 🚧 Coming soon |
| Protected routes / middleware | 🚧 Roadmap |

---

## Requirements

- **PHP** 8.0+ (extensions: `pdo_mysql`, `json`)
- **MySQL** 5.7+ or **MariaDB**
- **Apache** with `mod_rewrite` (WAMP, XAMPP, Laragon, or Linux Apache)
- **Composer** (for `firebase/php-jwt`, used in a future step)

---

## Quick start (developer checklist)

Follow these steps in order the first time you set up the project.

### 1. Clone and install dependencies

```bash
git clone https://github.com/your-username/ivan-jwt-auth.git
cd ivan-jwt-auth
composer install
```

### 2. Configure environment (`.env`)

Create a `.env` file in the **project root** (same level as `composer.json`):

```env
LOCAL_DB_HOST=localhost
LOCAL_DB_NAME=ivan_jwt_auth
LOCAL_DB_USER=root
LOCAL_DB_PASS=
LOCAL_DB_CHARSET=utf8mb4
LOCAL_DB_COLLATION=utf8mb4_unicode_ci
LOCAL_DB_PORT=3306
```

> `.env` is gitignored. Never commit real passwords.

`config/parameters.php` loads this file and exposes `DB_*` constants for `config/db.php`.

### 3. Create the database and table

**Option A — new database**

```sql
CREATE DATABASE ivan_jwt_auth CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ivan_jwt_auth;
```

Then run the reference script:

```bash
# From project root — adjust path for your OS
mysql -u root -p ivan_jwt_auth < database/schema.sql
```

**Option B — you already have a `users` table**

You do **not** need `schema.sql` if your table already has the columns you need. The API reads the real columns with `DESCRIBE users` and adapts inserts/selects automatically.

Minimum columns expected for registration (see `config/users_table.php`):

- `uuid`, `first_name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`

Optional columns (e.g. `last_name`, `deleted_at`) are used when present.

### 4. Point Apache to the `api/` folder

All HTTP traffic must go through **`/api`** (front controller).

#### WAMP (Windows) example

1. Enable **Apache `mod_rewrite`** (Wamp Manager → Apache → Modules).
2. Edit `httpd-vhosts.conf` and add:

```apache
<VirtualHost *:80>
    ServerName ivan-jwt-auth.api
    DocumentRoot "C:/wamp64/www/ivan-jwt-auth/api"
    <Directory "C:/wamp64/www/ivan-jwt-auth/api">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

3. Add to `C:\Windows\System32\drivers\etc\hosts`:

```text
127.0.0.1   ivan-jwt-auth.api
```

4. Restart Apache.

#### Without a virtual host

You can also use:

```text
http://localhost/ivan-jwt-auth/api/
```

Use that URL as your **base URL** in the steps below.

### 5. Verify it works

| What | URL |
|------|-----|
| Documentation UI | http://ivan-jwt-auth.api/ |
| List users | http://ivan-jwt-auth.api/show |
| Register (POST) | http://ivan-jwt-auth.api/register |

**Browser:** open the docs URL and use **Try it** on each endpoint.

**curl (register):**

```bash
curl -X POST http://ivan-jwt-auth.api/register \
  -H "Content-Type: application/json" \
  -d "{\"first_name\":\"Ivan\",\"email\":\"you@example.com\",\"password\":\"secret12\"}"
```

**curl (list users):**

```bash
curl "http://ivan-jwt-auth.api/show?limit=10&offset=0"
```

### 6. Run the automated test suite (optional)

From the project root (PowerShell):

```powershell
.\tests\run-api-tests.ps1
```

Custom base URL:

```powershell
.\tests\run-api-tests.ps1 -BaseUrl "http://localhost/ivan-jwt-auth/api"
```

You should see all checks pass (e.g. `20 / 20 passed`).

---

## Base URL

After setup, your API base URL is typically:

```text
http://ivan-jwt-auth.api
```

All endpoints below are relative to that base (no `/api` in the path if DocumentRoot is already `api/`).

---

## API endpoints

### `GET /show` — List users

Returns users from the `users` table. Sensitive fields (`password`, `token`) are never included.

**Query parameters**

| Param | Default | Description |
|-------|---------|-------------|
| `limit` | `50` | Rows per page (1–100) |
| `offset` | `0` | Pagination offset |

**Example response (200)**

```json
{
  "status": "success",
  "message": "Users retrieved successfully",
  "data": [
    {
      "id": 1,
      "uuid": "…",
      "first_name": "Ivan",
      "email": "ivan@email.com",
      "role": "user",
      "status": "active"
    }
  ],
  "meta": {
    "total": 1,
    "count": 1,
    "limit": 50,
    "offset": 0
  }
}
```

---

### `POST /register` — Register a user

**Headers:** `Content-Type: application/json`

**Body (client sends)**

| Field | Required | Rules |
|-------|----------|--------|
| `first_name` | Yes | Min. 2 characters |
| `email` | Yes | Valid email, unique |
| `password` | Yes | Min. 8 chars + at least one digit |
| `last_name` | No | If column exists in DB |
| Other table columns | No | Sent only if column exists and is not blocked |

**Server generates (when columns exist):** `uuid`, `created_at`, `updated_at`, and defaults `role=user`, `status=active`.

**Example request**

```json
{
  "first_name": "Ivan",
  "last_name": "Gonzalez",
  "email": "ivan@email.com",
  "password": "secret12"
}
```

**Success (201)**

```json
{
  "status": "success",
  "message": "User registered successfully",
  "data": {
    "id": 1,
    "uuid": "…",
    "first_name": "Ivan",
    "email": "ivan@email.com"
  }
}
```

**Common errors**

| HTTP | Meaning |
|------|---------|
| 400 | Invalid or empty JSON body |
| 422 | Validation failed |
| 409 | Email already exists |
| 405 | Wrong HTTP method |

---

### `POST /login` — Login (stub)

Currently returns a placeholder response. **JWT login is planned next.**

```json
{
  "status": false,
  "message": "Registration is currently unavailable"
}
```

---

## How routing works

```text
Request
   → api/.htaccess (clean URLs: /register, /show, /login)
   → api/index.php (front controller, ?page=…)
   → src/{page}.php (thin controller)
   → Service → Validator → Repository → PDO
   → JSON response
```

- **HTML docs:** `GET /` loads `src/doc.php` + assets from `api/styles.php` and `api/js.php`.
- **JSON API:** any other allowed `src/*.php` file (except `doc.php`) is included when the route matches.

---

## Project structure

```text
ivan-jwt-auth/
├── api/                    # DocumentRoot — entry point
│   ├── index.php           # Front controller + router
│   ├── .htaccess           # URL rewriting
│   ├── styles.php          # Docs CSS
│   └── js.php              # Docs JS (Try it, history, token storage)
├── config/
│   ├── parameters.php      # Loads .env
│   ├── db.php              # PDO connection
│   └── users_table.php     # Business rules for users (required columns, defaults)
├── core/
│   ├── database.php        # ORM wrapper (prepared statements)
│   ├── Validator.php       # HTTP / JSON helpers
│   ├── TableSchema.php     # Reads DB columns, filters input/output
│   └── Uuid.php
├── repositories/
│   └── UserRepository.php
├── services/
│   ├── RegisterService.php
│   └── UserListService.php
├── validators/
│   └── RegisterValidator.php
├── src/                    # Endpoints (one file per route)
│   ├── register.php
│   ├── show.php
│   ├── login.php
│   └── doc.php
├── database/
│   └── schema.sql          # Reference users table
├── tests/
│   ├── run-api-tests.ps1
│   └── requests/           # Sample JSON bodies
├── .env                    # Local config (not in git)
└── composer.json
```

---

## Dynamic `users` table

The API does **not** hardcode every column name in SQL for registration and listing:

1. `DESCRIBE users` loads current columns.
2. `config/users_table.php` defines required fields, defaults, and blocked/hidden columns.
3. Inserts and selects only use columns that exist.

To add a new field (e.g. `phone`):

1. Add the column in MySQL: `ALTER TABLE users ADD phone VARCHAR(20) NULL;`
2. If clients should send it, ensure it is not listed in `blocked_input` in `config/users_table.php`.
3. No repository change required for a simple optional field.

---

## Adding a new endpoint

1. Create `src/profile.php` (thin controller: method check → service → JSON).
2. Add a rewrite rule in `api/.htaccess`:

```apache
RewriteRule ^profile$ index.php?page=profile [QSA,L]
```

3. Document it in `src/doc.php` (optional but recommended).
4. Add a test case in `tests/run-api-tests.ps1` if needed.

The file must live in `src/` and must **not** be named `doc.php` (that file is excluded from API routing).

---

## Security notes

- Passwords stored with `password_hash()` (never returned in JSON).
- PDO prepared statements (`ATTR_EMULATE_PREPARES => false`).
- Input blocked for sensitive columns (`token`, timestamps, etc.) — see `config/users_table.php`.
- CORS is currently `*` on JSON routes (fine for local dev; restrict origins in production).
- JWT middleware and protected routes are **not** implemented yet.

---

## Troubleshooting

| Problem | What to check |
|---------|----------------|
| 404 on `/register` | DocumentRoot must be `api/`; `mod_rewrite` on; `AllowOverride All` |
| Connection failed | `.env` values; MySQL running; database exists |
| Unknown column `name` | Use `first_name`, not `name` (see register docs) |
| Invalid JSON (400) from PowerShell | Use `curl -d "@file.json"` or the docs UI — escaping breaks inline JSON |
| Register 500 schema error | Table missing required columns from `config/users_table.php` |
| Tests fail on duplicate email | Run `.\tests\run-api-tests.ps1` — duplicate test creates user first |

---

## Roadmap

- [ ] `LoginService` + JWT (`firebase/php-jwt`)
- [ ] Auth middleware for protected routes
- [ ] Welcome email template on login
- [ ] Profile endpoint
- [ ] Refresh tokens
- [ ] Publish as Composer package

---

## Philosophy

**Build once → reuse everywhere.**

Avoid rebuilding register, validation, and DB wiring on every project. Start from this base, extend endpoints, and keep controllers thin.

---

## Author

**Ivan Gonzalez**

---

## License

MIT License
