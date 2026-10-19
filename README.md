# HIMATIF Nexus

> The unified management platform for **Himpunan Mahasiswa Teknik Informatika (HIMATIF)**.

**HIMATIF Nexus** is a monolith-SPA that consolidates the organization's four core operations — content publishing, event management, merchandise, and asset management — into a single application.

## Architecture

| Layer | Technology |
|---|---|
| **Backend** | Laravel 13 (PHP 8.3+) |
| **Frontend** | Vue 3 + Inertia.js v2 |
| **Styling** | Tailwind CSS |
| **Build** | Vite + Laravel Vite Plugin |
| **Database** | PostgreSQL (target) / SQLite (development) |
| **Auth** | Laravel Breeze + Sanctum |
| **Page Builder** | Puck (planned) — drag-and-drop content editor for Infokom |
| **RBAC** | `spatie/laravel-permission` (planned) |

## Modules

The application is divided into four functional modules, each owned by a dedicated team:

| Module | Team | Description |
|---|---|---|
| **BPH** | Admin | Organization management — user accounts, roles, permissions |
| **Infokom** | Content | Blog articles with a visual page builder (Puck) and activity/event listings |
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

# Database (ensure DB_CONNECTION is set correctly in .env)
php artisan migrate --seed

# Build assets
npm run dev
```

For the full setup script (installs dependencies, generates key, runs migrations, builds assets):

```bash
composer run setup
```

## Database

The project targets **PostgreSQL** as its production database. The default `.env` uses SQLite for local development.

### Switching to PostgreSQL

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=himatif_nexus
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Switching to MySQL (XAMPP / Laragon)

For developers using XAMPP or Laragon, MySQL is also supported:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=himatif_nexus
DB_USERNAME=root
DB_PASSWORD=
```

> **Note:** Several migration blockers exist. See [ARCHITECTURE.md](./ARCHITECTURE.md) §3.3 for details.

## Documentation

- [**ARCHITECTURE.md**](./ARCHITECTURE.md) — Full system architecture, technical stack, database schema, Puck integration, team folder ownership, and directory structure (also available in [Bahasa Indonesia](./ARCHITECTURE.id-ID.md)).

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
