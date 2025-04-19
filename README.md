
[![Staging Deployment](https://github.com/wilng2005/daily-driver/actions/workflows/staging-deploy.yml/badge.svg)](https://github.com/wilng2005/daily-driver/actions/workflows/staging-deploy.yml)
[![Production Deployment](https://github.com/wilng2005/daily-driver/actions/workflows/deploy.yml/badge.svg)](https://github.com/wilng2005/daily-driver/actions/workflows/deploy.yml)

# Daily Driver

A modern Laravel 11 application with Nova 5 admin panel, automated browser testing, and fully automated CI/CD deployments on AWS Lambda (Vapor) using PHP 8.3 and ARM for cost and performance efficiency.

- The Tech Leads page now displays a random, user-relatable career challenge message as the main heading, in double quotes and italics, on each page load. This feature is fully covered by automated and browser tests. See documentation below.

---

## 🚀 Features
- **Laravel 11** with first-class Sail (Docker) support
- **Nova 5** for powerful admin/resource management
- **Automated Dusk browser tests** for critical workflows
- **CI/CD via GitHub Actions** for both staging and production (no manual deploys)
- **Serverless deployment** on AWS Lambda using Laravel Vapor (PHP 8.3, ARM)
- **Composer-managed dependencies** (including PHP 8.3-only packages)

### 📖 Features & Usage
For a detailed breakdown of Nova admin features and workflows, see [docs/NOVA-FEATURES.md](docs/NOVA-FEATURES.md).

---

## 🛠️ Tech Stack
- PHP 8.3 (ARM on production/staging)
- Laravel 11
- Laravel Nova 5
- Laravel Sail (Docker)
- Laravel Vapor
- MySQL, Redis
- GitHub Actions (CI/CD)
- Dusk (browser testing)

---

## ⚡ Getting Started

### Prerequisites
- Docker & Docker Compose
- PHP 8.3+ (if running outside Sail)
- Composer v2
- Node.js & npm/yarn (for asset compilation)

### Setup
```sh
# 1. Clone the repo
 git clone https://github.com/wilng2005/daily-driver.git
 cd daily-driver

# 2. Copy env and install dependencies
 cp .env.example .env
 ./vendor/bin/sail up -d
 ./vendor/bin/sail composer install
 ./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev

# 3. Generate app key & run migrations
 ./vendor/bin/sail artisan key:generate
 ./vendor/bin/sail artisan migrate

# 4. (Optional) Seed the database
 ./vendor/bin/sail artisan db:seed
```

---

## 🧑‍💻 Development Workflow
- **Start the app:** `./vendor/bin/sail up -d`
- **Stop the app:** `./vendor/bin/sail down`
- **Run tests:** `./vendor/bin/sail test`
- **Run browser tests (Dusk):** `./vendor/bin/sail dusk`
- **Generate coverage report:** `./vendor/bin/sail test --coverage-html=./coverage-report`
- **Access Nova:** `/nova` (local dev, after running migrations)

---

## 🚢 Deployment
- **Staging:** Push to `staging` branch triggers CI/CD and deploys to Vapor (uses PHP 8.3 ARM runtime)
- **Production:** Push to `main` branch triggers CI/CD and deploys to Vapor (uses PHP 8.3 ARM runtime)
- **No manual `vapor deploy` required**—all deployments are automated via GitHub Actions
- **Vapor config:** See `vapor.yml` for environment/runtime details

---

## 🛑 Troubleshooting & Common Issues
- **Composer install fails?** Ensure you are using PHP 8.3+ (some dependencies require it)
- **GitHub Action not triggering?** Make sure workflow files exist in the target branch before merging. Fast-forward/no-op merges or missing workflow files can prevent triggers.
- **Dusk/Selenium errors?** Make sure Sail and all containers (including Selenium) are running before testing.
- **Manual deploy discouraged:** Always use CI/CD for both staging and production.

---

## 📝 Useful Commands
- `./vendor/bin/sail up -d` — Start Sail containers
- `./vendor/bin/sail down` — Stop Sail containers
- `./vendor/bin/sail test` — Run all tests
- `./vendor/bin/sail dusk` — Run browser (Dusk) tests
- `./vendor/bin/sail test --coverage-html=./coverage-report` — Generate coverage report
- `./vendor/bin/sail artisan migrate` — Run migrations
- `./vendor/bin/sail artisan db:seed` — Seed database

---

## 🤝 Contributing & Documentation
- See [docs/API.md](docs/API.md) for the full API reference (endpoints, authentication, schemas, and examples)
- See [docs/ARCHIVED-ISSUE-Nova-Fluent-FatalError.md](docs/ARCHIVED-ISSUE-Nova-Fluent-FatalError.md) for the full history of the Nova Fluent FatalError issue (archived, completed, and closed)
- See [ISSUE-UI-UPDATES.md](ISSUE-UI-UPDATES.md) for the latest UI asset update issue documentation
- See [ISSUE-RandomCareerMessage.md](ISSUE-RandomCareerMessage.md) for the randomized career message feature request, implementation, and testing documentation (Tech Leads page heading)
- All major changes and lessons learned are documented in the repo
- Please open issues or PRs for bugs, improvements, or questions

---

## 📚 References
- [Laravel](https://laravel.com/)
- [Laravel Nova](https://nova.laravel.com/)
- [Laravel Vapor](https://vapor.laravel.com/)
- [GitHub Actions](https://docs.github.com/en/actions)

---

## 🚀 Deployment Log
- **2025-04-19:** Deploying current changes to the `production` environment via CI/CD (GitHub Actions + Vapor).
- **2025-04-19:** Deploying current changes to the `staging` environment via CI/CD (GitHub Actions + Vapor).

_This README was last updated for the PHP 8.3/ARM, Nova 5, and CI/CD improvements (2025-04-17)._

