
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

## ✅ Code Coverage Policy
- This project enforces 100% code coverage as part of the CI/CD pipeline.
- For generic error handling (e.g., logging and rethrowing in catch blocks), we use `@codeCoverageIgnoreStart`/`@codeCoverageIgnoreEnd` to pragmatically exclude these lines from coverage, as they do not contain business logic.
- All other logic is fully covered by automated tests, following TDD principles.

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
- See [docs/DB_SCHEMA.md](docs/DB_SCHEMA.md) for the full database schema (tables, columns, relationships, and migration notes)
- See [docs/issues/archived/ARCHIVED-ISSUE-Nova-Fluent-FatalError.md](docs/issues/archived/ARCHIVED-ISSUE-Nova-Fluent-FatalError.md) for the full history of the Nova Fluent FatalError issue (archived, completed, and closed)
- See [docs/issues/ISSUE-UI-UPDATES.md](docs/issues/ISSUE-UI-UPDATES.md) for the latest UI asset update issue documentation
- See [docs/issues/ISSUE-RandomCareerMessage.md](docs/issues/ISSUE-RandomCareerMessage.md) for the randomized career message feature request, implementation, and testing documentation (Tech Leads page heading)
- See [docs/issues/ISSUE-InvestigateScheduledJobLogging.md](docs/issues/ISSUE-InvestigateScheduledJobLogging.md) for the scheduled job logging investigation and implementation notes
- _Archived/closed issues are grouped in [docs/issues/archived/](docs/issues/archived/) to keep the documentation organized._
- All major changes and lessons learned are documented in the repo
- Please open issues or PRs for bugs, improvements, or questions

---

## 📋 Project Issues Overview

### Archived/Closed Issues

#### 1. Refactor Job Commands for Testability and TDD
- **File:** [job-command-di-tdd-refactor.md](docs/issues/archived/job-command-di-tdd-refactor.md)
- **Summary:** Refactored scheduled job commands to use dependency injection, improving testability and enabling TDD. Feature tests and code coverage are now robust. **Status:** ✅ Closed & Completed (2025-04-23)

#### 2. Scheduled Job Logging for Vapor/CloudWatch
- **File:** [ISSUE-InvestigateScheduledJobLogging.md](docs/issues/archived/ISSUE-InvestigateScheduledJobLogging.md)
- **Summary:** Implemented explicit logging for all scheduled jobs to improve AWS CloudWatch visibility on Vapor. Approach is documented and confirmed. **Status:** ✅ Closed & Completed (2025-04-23)

#### 3. UI Asset Reference Updates
- **File:** [ISSUE-UI-UPDATES.md](docs/issues/archived/ISSUE-UI-UPDATES.md)
- **Summary:** Documented all UI asset reference changes and legal updates for the coaching program. **Status:** ✅ Closed & Completed (2025-04-23)

#### 4. Randomized Career Message Feature
- **File:** [ISSUE-RandomCareerMessage.md](docs/issues/archived/ISSUE-RandomCareerMessage.md)
- **Summary:** Implemented a feature to display a random motivational message on the Tech Leads page, with server-side logic and automated tests. **Status:** ✅ Closed & Completed (2025-04-23)

#### 5. Nova Fluent FatalError (Closed)
- **File:** [ARCHIVED-ISSUE-Nova-Fluent-FatalError.md](docs/issues/archived/ARCHIVED-ISSUE-Nova-Fluent-FatalError.md)
- **Summary:** Historical record of debugging and resolving a fatal error after upgrading to Nova 5 and PHP 8.3. Includes all troubleshooting steps and lessons learned. **Status:** Completed & Archived

#### 6. Nova Fluent FatalError (Full History, Large)
- **File:** [ISSUE-Nova-Fluent-FatalError.md](docs/issues/archived/ISSUE-Nova-Fluent-FatalError.md)
- **Summary:** Full, detailed debugging log and technical notes for the Nova Fluent FatalError, including Dusk/browser testing, CI/CD, and deployment notes. **Status:** Archived

---

## 📝 Issue Documentation Policy

To ensure clarity, maintainability, and ease of onboarding, follow these policies for documenting and maintaining project issues:

1. **New Issues:**
   - All new issues must be documented as individual Markdown files in the `docs/issues/` folder.
   - Each issue file should have a clear title, summary, context, implementation plan, and status.

2. **Closed/Archived Issues:**
   - When an issue is closed or completed, move its Markdown file to the `docs/issues/archived/` subfolder.
   - Add a closure note and the date of completion at the top of the file.

3. **README Updates:**
   - Update the "Project Issues Overview" section in `README.md` whenever an issue is created, closed, or archived.
   - Ensure each issue is listed with a summary, status, and a direct link to its documentation.

4. **Summaries & Status:**
   - Keep summaries and statuses up to date for all issues, so new contributors can quickly understand the project context.
   - Use clear closure notes and completion dates for all archived issues.

5. **Consistency:**
   - Ensure all links in the README and documentation remain accurate after moving or archiving issues.
   - Regularly review the documentation for accuracy and clarity.

By following this policy, the project will remain well-documented, easy to navigate, and welcoming to all contributors.

---

## 📚 References
- [Laravel](https://laravel.com/)
- [Laravel Nova](https://nova.laravel.com/)
- [Laravel Vapor](https://vapor.laravel.com/)
- [GitHub Actions](https://docs.github.com/en/actions)

---

## 🚀 Deployment Log
- **2025-04-21:** Deploying scheduled job logging investigation to the `staging` environment via CI/CD (GitHub Actions + Vapor). Includes explicit logging for all scheduled jobs to improve CloudWatch/Vapor visibility.
- **2025-04-19:** Deploying current changes to the `production` environment via CI/CD (GitHub Actions + Vapor).
- **2025-04-19:** Deploying current changes to the `staging` environment via CI/CD (GitHub Actions + Vapor).

_This README was last updated for the PHP 8.3/ARM, Nova 5, and CI/CD improvements (2025-04-17)._

