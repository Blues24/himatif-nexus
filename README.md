# HIMATIF Nexus

> The unified management platform for **Himpunan Mahasiswa Teknik Informatika (HIMATIF)**.

**HIMATIF Nexus** is a monolith-SPA that consolidates the organization's four core operations — content publishing, event management, merchandise, and asset management — into a single application.

## Architecture

| Layer | Technology |
|---|---|
| **Backend** | Laravel 13 (PHP 8.5+) |
| **Frontend** | Vue 3 + Inertia.js v2 |
| **Styling** | Tailwind CSS |
| **Build** | Vite + Laravel Vite Plugin |
| **Database** | PostgreSQL |
| **Auth** | Laravel Breeze + Sanctum |
| **RBAC** | Custom (`app/Models/Role` + `User::hasRole()`) — no Spatie dependency |
| **Page Builder** | Puck (planned) — drag-and-drop content editor for Infokom |

## Modules

The application is divided into four functional modules, each owned by a dedicated team:

| Module | Team | Description |
|---|---|---|
| **BPH** | Admin | Organization management — user accounts, roles, permissions |
| **Infokom** | Content | Blog articles and activity/event listings |
| **Danus Shop** | Commerce | Product catalog, transactions, and merchandise management |
| **Portal** | Asset | Equipment inventory and rental request workflow |

## Quick Start

```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database (PostgreSQL required; ensure PDO pgsql extension is loaded)
php artisan migrate --seed

# Build assets
npm run dev
```

For the full setup script:

```bash
composer run setup
```

## Database

The project uses **PostgreSQL** for both development and production. The `.env.example` contains MySQL defaults as a fallback for XAMPP/Laragon users.

### Production (PostgreSQL)

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=himatif_nexus
DB_USERNAME=himatif
DB_PASSWORD=<secret>
```

### Local development (MySQL / XAMPP / Laragon)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=himatif_nexus
DB_USERNAME=root
DB_PASSWORD=
```

> **Note:** The test suite in `phpunit.xml` hard-codes `pgsql`. Contributors without PostgreSQL must either install the `pdo_pgsql` extension or temporarily switch the test config to SQLite.

## Documentation

- [**ARCHITECTURE.md**](./ARCHITECTURE.md) — Full system architecture, technical stack, database schema, team folder ownership, and directory structure (also available in [Bahasa Indonesia](./ARCHITECTURE.id-ID.md)).

## Project Conventions

- Follow Laravel conventions — use `php artisan make:` commands to generate new files.
- Use **named routes** and the `route()` helper for all links.
- PHP 8+ constructor property promotion, explicit return types, and curly braces for all control structures.
- Run `vendor/bin/pint --dirty --format agent` after modifying PHP files.
- Feature tests over unit tests. Use `php artisan test --compact` to run the suite.

See [AGENTS.md](./AGENTS.md) for the complete coding and AI agent guidelines.

## Team Collaboration

To avoid merge conflicts, each team has designated ownership of specific directories. Read the **Team Folder Ownership** section in [ARCHITECTURE.md §6](./ARCHITECTURE.md#6-team-folder-ownership--access-boundary-matrix) before contributing.

## License

The HIMATIF Nexus platform is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
