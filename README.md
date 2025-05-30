
[![Staging Deployment](https://github.com/wilng2005/daily-driver/actions/workflows/staging-deploy.yml/badge.svg)](https://github.com/wilng2005/daily-driver/actions/workflows/staging-deploy.yml)
[![Production Deployment](https://github.com/wilng2005/daily-driver/actions/workflows/deploy.yml/badge.svg)](https://github.com/wilng2005/daily-driver/actions/workflows/deploy.yml)

# Daily Driver

A modern Laravel 11 application with Nova 5 admin panel, automated browser testing, and fully automated CI/CD deployments on AWS Lambda (Vapor) using PHP 8.3 and ARM for cost and performance efficiency.

> **This application powers the public-facing coaching website [greater.than.today](https://greater.than.today), serving as the main platform for coaching services, resources, and client engagement.**

---

## 🚀 Features
- **Laravel 11** with first-class Sail (Docker) support
- **Nova 5** for powerful admin/resource management
- **Insights Module**: Create, edit, and publish rich multi-section insights via Nova admin, with support for markdown, images, and layout customization
- **Automated Dusk browser tests** for critical workflows
- **Next Actions API endpoint** for retrieving prioritized next-action items (see API section)
- **CI/CD via GitHub Actions** for both staging and production (no manual deploys)
- **Serverless deployment** on AWS Lambda using Laravel Vapor (PHP 8.3, ARM)
- **Composer-managed dependencies** (including PHP 8.3-only packages)

### 📚 Features & Usage
For a detailed breakdown of Nova admin features and workflows, see [docs/NOVA-FEATURES.md](docs/NOVA-FEATURES.md).

#### Insights Module (Nova)
- Manage insights and their sections from the Nova admin panel
- Fields: title, description, keywords (JSON), published_at, sections (hasMany)
- Section fields: header, markdown, image path, background color, order
- Full CRUD, ordering, markdown editing, and publication control
- 100% test coverage and TDD enforced for all CRUD and publication logic

#### API Endpoints
- **GET /api/todos** — Search todos by query string (requires API token)
- **GET /api/next-actions** — Returns all captures where `next_action=true`, sorted with captures having `priority_no=null` first, then ascending by `priority_no`. Secured by the same API token middleware as `/api/todos`. See OpenAPI schema at `/api/open-ai/schema` for details.
- **POST /api/captures** — Create a new capture (todo) item. Requires `name` and `content` in the JSON body. Optional fields: `priority_no`, `inbox`, `next_action`. Requires API token in the `X-API-Token` header. See OpenAPI schema at `/api/open-ai/schema` for full request/response details and validation rules.
- **PUT /api/captures/{id}** — Update a capture by ID. Only `name`, `content`, `priority_no`, `inbox`, and `next_action` are updatable. Requires API token. See OpenAPI schema for request/response structure, validation, and security details.
- **DELETE /api/captures/{id}** — Soft delete a capture by ID. Marks the capture as deleted (using Laravel SoftDeletes) without removing it from the database. Requires API token. Returns 204 No Content on success. See OpenAPI schema at `/api/open-ai/schema` for details.
- **GET /api/open-ai/random-number** — Generate a random integer in a given range (public endpoint). By default, returns a number between 1 and 12 (simulates a 12-sided die). Accepts optional `min` and `max` query parameters. See OpenAPI schema at `/api/open-ai/random-number/schema` for full details.

For instructions on using this endpoint inside ChatGPT or as a plugin/tool, see:
[docs/USING-RANDOM-NUMBER-API-IN-CHATGPT.md](docs/USING-RANDOM-NUMBER-API-IN-CHATGPT.md)

### 📰 AI-Generated Articles
See the [AI-Generated Articles Feature Plan](docs/issues/FEATURE-AI-ARTICLES.md) for the roadmap and technical details of the automated articles section, which provides regularly updated coaching content powered by AI and reviewed via the Nova admin panel.

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

---

## 🧑‍💻 Development Workflow

- Insights and their sections are managed via Nova admin resources
- All CRUD and publication logic for insights is covered by automated feature tests (see `InsightTest`)
- TDD and 100% code coverage are enforced for this feature

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


### Database Reset & Seeding

To reset your database and seed it with sample data for local development/testing:

```sh
./vendor/bin/sail artisan migrate:fresh --seed
```

This will:
- Drop all tables and re-run all migrations
- Truncate the posts table and insert example data (manual & AI, published & draft)

Use this workflow to demo, test, or develop with real data.

---

### Updating main.css (Custom Styles)

All custom styles for the application are managed in `public/sass/main.css`.

To update or add styles:
1. Edit `public/sass/main.css` and save your changes.
2. Rebuild frontend assets using Sail and Vite:
   ```sh
   ./vendor/bin/sail npm run build
   ```
3. Refresh your browser to see the changes. (You may need to clear your browser cache for CSS changes to appear.)

**Note:**
- Always commit both your updated CSS and any relevant Blade/template changes.
- If you encounter build errors, try removing `node_modules` and `package-lock.json`, then run `./vendor/bin/sail npm install` before rebuilding.

### Commands
- **Start the app:** `./vendor/bin/sail up -d`
- **Stop the app:** `./vendor/bin/sail down`
- **Run tests:** `./vendor/bin/sail test`
- **Run browser tests (Dusk):** `./vendor/bin/sail dusk`
- **Generate coverage report:** `./vendor/bin/sail test --coverage-html=./coverage-report`
- **Access Nova:** `/nova` (local dev, after running migrations)
- **Before deploying or opening a PR:** Always run the code coverage report locally and ensure 100% coverage before pushing. Do **not** wait for CI/CD to catch coverage failures. Use `./vendor/bin/sail test --coverage-html=./coverage-report` and open `coverage-report/index.html` to verify.

---

## ✅ Code Coverage Policy
- The project enforces 100% code coverage for all API endpoints and business logic. Any new or updated endpoint (such as `PUT /api/captures/{id}`) must be accompanied by comprehensive feature tests.
- For time-dependent logic, use Carbon freezing (see `DoDailyScheduleTest.php`) to ensure deterministic coverage.
- Test-driven development (TDD) is the preferred workflow for all bug fixes and features.

- This project enforces 100% code coverage as part of the CI/CD pipeline.
- All new endpoints (including `/api/next-actions`) must be fully covered by automated feature tests before merging.
- Test for `/api/next-actions` ensures correct filtering and sorting logic for next-action captures, as described in the issue documentation.
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

## 📋 Project Issues Overview

### Outstanding/Open Issues

#### 1. AI-Generated Articles Feature
- **File:** [FEATURE-AI-ARTICLES.md](docs/issues/FEATURE-AI-ARTICLES.md)
- **Summary:** Implements an Articles section for coaching topics (burnout, productivity, stress, etc.) with AI-generated and manually authored content. Includes Nova admin review, TDD-first development, and CI/CD integration. **Status:** 🚧 Open & In Progress (see feature plan for roadmap)

#### 2. Insights Module (Manual + Sectioned)
- **File:** [FEATURE-INSIGHTS-MODULE.md](docs/issues/FEATURE-INSIGHTS-MODULE.md)
- **Summary:** Adds a flexible, multi-section Insights module supporting manual insight creation with structured sections, markdown content, image selection, and layout customization. Images are now automatically aligned left/right by section order (no image position field). Designed for editorial control and rich content presentation. **Status:** 🚧 Open & In Progress (see feature doc for requirements and implementation plan)

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

## ⚠️ Technical Debt / Multi-User Limitation

> **Single-user Limitation:**
> The current API hardcodes `user_id = 1` for all newly created captures (see CaptureController@store). This is a temporary workaround because only one user exists in production. The system is **not** ready for multi-user environments. A future redesign will be required to support multiple users, including proper authentication, user context, and per-user data isolation.

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

- **2025-05-17:** Added `/api/next-actions` endpoint for prioritized next actions, updated OpenAPI schema, and added full test coverage for filtering and sorting. See [ISSUE-NEXT-ACTIONS-ENDPOINT.md](docs/ISSUE-NEXT-ACTIONS-ENDPOINT.md).

- **2025-05-21:** Added `GET /api/open-ai/random-number` endpoint (public) for generating random numbers in a customizable range, with OpenAPI schema and full feature test coverage. See [ISSUE-OPENAI-RANDOM-NUMBER-ENDPOINT.md](docs/issues/ISSUE-OPENAI-RANDOM-NUMBER-ENDPOINT.md).
- **2025-05-19:** Added `POST /api/captures` endpoint for creating captures. Updated OpenAPI schema, feature tests, and documentation for endpoint, validation, and authentication. See [ISSUE-CREATE-CAPTURE-ENDPOINT.md](docs/issues/ISSUE-CREATE-CAPTURE-ENDPOINT.md).
- **2025-05-18:** Added `PUT /api/captures/{id}` endpoint for updating captures. Updated OpenAPI schema, added feature tests and validation, and ensured 100% code coverage. See [ISSUE-UPDATE-CAPTURE-ENDPOINT.md](docs/issues/ISSUE-UPDATE-CAPTURE-ENDPOINT.md).

_This README was last updated for the OpenAI random number endpoint, OpenAPI schema, and test coverage improvements (2025-05-21)._


