# Daily Driver Brownfield Architecture Document

## Introduction

This document captures the CURRENT STATE of the Daily Driver codebase, including technical debt, workarounds, and real-world patterns. It serves as a reference for AI agents working on enhancements to this coaching platform.

### Document Scope

Comprehensive documentation of entire system - a modern Laravel 11 application with Nova 5 admin panel powering the coaching website greater.than.today.

### Change Log

| Date       | Version | Description                 | Author    |
| ---------- | ------- | --------------------------- | --------- |
| 2025-09-16 | 1.0     | Initial brownfield analysis | Winston   |

## Quick Reference - Key Files and Entry Points

### Critical Files for Understanding the System

- **Main Entry**: `public/index.php` (Laravel standard entry point)
- **Configuration**: `config/app.php`, `.env.example` (environment template)
- **Core Business Logic**: `app/Models/`, `app/Http/Controllers/`, `app/Nova/`
- **API Definitions**: `routes/api.php` (13KB+ comprehensive API routes)
- **Database Models**: `app/Models/` (Capture, Insight, TelegramChat, User, etc.)
- **Key Algorithms**: `app/Models/TelegramChat.php:33KB` (complex Telegram integration logic)
- **Nova Resources**: `app/Nova/` (Admin panel resource definitions)
- **Test Coverage**: `tests/` (enforces 100% code coverage policy)

### Nova Admin Resources (Critical for Content Management)

- **Captures**: `app/Nova/Capture.php` - Task/todo management
- **Insights**: `app/Nova/Insight.php` - Multi-section content publishing
- **Users**: `app/Nova/User.php` - User and resource access management
- **Posts**: `app/Nova/Post.php` - Blog/content management
- **Telegram**: `app/Nova/TelegramChat.php` - Chat management integration

## High Level Architecture

### Technical Summary

This is a **modern serverless Laravel 11 application** deployed on AWS Lambda via Vapor, serving as the backend for a coaching platform. It features a comprehensive Nova 5 admin panel, extensive API endpoints for external integrations (including OpenAI/ChatGPT), Telegram bot integration, and a sophisticated capture/task management system.

### Actual Tech Stack (from composer.json/package.json)

| Category        | Technology          | Version | Notes                                    |
| --------------- | ------------------- | ------- | ---------------------------------------- |
| Runtime         | PHP                 | ^8.3    | Required (ARM on production/staging)     |
| Framework       | Laravel             | ^11.0   | Latest version with first-class features |
| Admin Panel     | Laravel Nova        | ^5.0.0  | Commercial admin interface               |
| Database        | MySQL               | -       | Via Vapor managed database               |
| Serverless      | Laravel Vapor       | ^2.27   | AWS Lambda deployment                    |
| Authentication  | Laravel Sanctum     | ^4.0    | API token authentication                 |
| Search          | Laravel Scout       | ^10.10  | Model search functionality               |
| Frontend Build  | Vite                | ^6.3.6  | Modern asset bundling                    |
| Styling         | Sass                | ^1.90.0 | CSS preprocessing                        |
| Testing         | PHPUnit             | ^10.0   | 100% coverage enforcement               |
| Browser Testing | Laravel Dusk        | ^8.0    | Selenium-based E2E tests                |
| Integrations    | Telegram Bot SDK    | ^3.11   | Bot integration                         |
| AI Integration  | OpenAI PHP Laravel  | ^0.8.1  | OpenAI API integration                  |
| HTTP Client     | Guzzle              | ^7.4.5  | External API communication              |
| Storage         | AWS S3 via Flysystem| ^3.0    | File storage on Lambda                  |

### Repository Structure Reality Check

- **Type**: Single Laravel application (monolithic)
- **Package Manager**: Composer (PHP) + pnpm (Node.js) - NOTE: uses pnpm, not npm
- **Notable**: Sophisticated CI/CD with GitHub Actions for staging/production auto-deployment
- **Dependencies**: Nova commercial license managed via separate composer repository

## Source Tree and Module Organization

### Project Structure (Actual)

```text
daily-driver/
├── app/
│   ├── Console/            # Artisan commands (4 items)
│   ├── Http/Controllers/   # HTTP request handlers (3 controllers)
│   ├── Jobs/               # Queue jobs (5 items)
│   ├── Models/             # Eloquent models (11 models)
│   ├── Nova/               # Admin panel resources (17 resources)
│   ├── Policies/           # Authorization policies (6 items)
│   ├── Providers/          # Service providers (9 items)
│   ├── Services/           # Business logic services (4 items)
│   └── Telegram/           # Telegram bot integration (3 items)
├── bootstrap/              # Laravel bootstrap files
├── config/                 # Configuration files (22 configs)
├── database/
│   ├── factories/          # Model factories
│   ├── migrations/         # Database migrations (25+ migrations)
│   └── seeders/            # Database seeders
├── docs/                   # Project documentation (8 files)
│   └── issues/             # Issue tracking docs (14 items)
│       └── archived/       # Completed issues
├── public/                 # Web root with assets
│   ├── images/             # Static images with dimension config
│   └── sass/               # Custom CSS (main.css - CRITICAL: manual builds required)
├── resources/
│   ├── css/                # Vite-compiled CSS
│   ├── js/                 # Frontend JavaScript
│   └── views/              # Blade templates
├── routes/                 # Route definitions
│   ├── api.php             # API routes (13KB+ comprehensive)
│   ├── web.php             # Web routes (3KB)
│   ├── channels.php        # Broadcasting channels
│   └── console.php         # Console routes
├── storage/                # Laravel storage
├── tests/                  # Test suites (100% coverage enforced)
│   ├── Browser/            # Dusk browser tests (13 tests)
│   ├── Feature/            # Feature tests (16 tests)
│   └── Unit/               # Unit tests (6 tests)
├── vendor/                 # Composer dependencies (58 packages)
├── .bmad-core/            # BMAD agent framework files (15 files)
├── docker/                # Docker configurations (4 configs)
├── scripts/               # Utility scripts (image processing)
├── vapor.yml              # Vapor deployment config
├── vite.config.js         # Vite build configuration
├── docker-compose.yml     # Local development via Sail
└── pnpm-lock.yaml         # pnpm dependency lock file
```

### Key Modules and Their Purpose

- **Capture System**: `app/Models/Capture.php` - Core task/todo management with soft deletes, search, Nova actions
- **Insights Module**: `app/Models/Insight.php` + `app/Models/InsightSection.php` - Multi-section content publishing system
- **Telegram Integration**: `app/Models/TelegramChat.php` (33KB!) - Complex bot integration with webhook handling
- **API Controllers**: `app/Http/Controllers/` - CaptureController, OpenAiController (10KB+ comprehensive)
- **Nova Resources**: `app/Nova/` - Complete admin interface with 17 resource definitions
- **Authentication**: Via Sanctum + custom API token middleware (`X-API-Token` header)

## Data Models and APIs

### Data Models

**Core Models** (see actual model files):
- **Capture Model**: `app/Models/Capture.php` - Tasks with soft deletes, priorities, inbox/next-action flags
- **Insight Model**: `app/Models/Insight.php` - Content pieces with slug auto-generation, publication control
- **InsightSection Model**: `app/Models/InsightSection.php` - Sectioned content with markdown, images, ordering
- **User Model**: `app/Models/User.php` - Basic user with resource access control
- **TelegramChat Model**: `app/Models/TelegramChat.php` (33KB) - Complex chat state management
- **Post Model**: `app/Models/Post.php` - Blog/content posts
- **Related Types**: Full Eloquent relationships defined in models

### API Specifications

**OpenAPI Documentation**: Available at `/api/open-ai/schema` (comprehensive OpenAPI 3.1.0 spec)

**Key Endpoints** (see `routes/api.php` - 13KB+ of routes):
- **GET /api/todos** - Search captures by query string (requires API token)
- **POST/PUT/DELETE /api/captures** - Full CRUD for captures (requires API token)
- **GET /api/next-actions** - Prioritized next-action items (requires API token)
- **GET /api/open-ai/random-number** - Public random number generator for ChatGPT
- **GET /api/open-ai/timestamp** - Public timezone-aware timestamp for ChatGPT
- **POST /webhooks/telegram** - Telegram bot webhook handler
- **OpenAPI Schemas**: Individual schema endpoints for each public API

**Authentication Patterns**:
- Sanctum for web users (`auth:sanctum` middleware)
- API Token for external integrations (`X-API-Token` header)
- Public endpoints for OpenAI/ChatGPT integration (no auth)

## Technical Debt and Known Issues

### Critical Technical Debt

1. **Single-User System**: **CRITICAL LIMITATION** - All API endpoints hardcode `user_id = 1` (see CaptureController@store). System is NOT multi-user ready. Future redesign required for proper authentication and per-user data isolation.

2. **Manual CSS Build Process**: Custom styles in `public/sass/main.css` require manual `npm run build` after changes. No hot reload for CSS changes.

3. **Image Dimension Config**: Manual maintenance required - `config/image_dimensions.php` must be kept in sync with actual files in `public/images/` using `php scripts/dump_image_sizes.php`.

4. **Telegram Bot Complexity**: 33KB `TelegramChat.php` model contains complex state management that's difficult to modify safely.

### Workarounds and Gotchas

- **Package Manager**: Uses `pnpm` not `npm` - must use correct commands in documentation
- **Vapor Runtime**: ARM-based PHP 8.3 on production/staging - deployment constraints
- **Nova License**: Commercial license managed via private composer repository
- **Test Coverage**: 100% coverage ENFORCED in CI/CD - all changes must include comprehensive tests
- **API Token**: Single shared token for all external integrations (not per-user)
- **Staging Database**: Staging and production share same database (see vapor.yml:26)

## Integration Points and External Dependencies

### External Services

| Service       | Purpose           | Integration Type | Key Files                             |
| ------------- | ----------------- | ---------------- | ------------------------------------- |
| AWS Lambda    | Application Host  | Vapor            | `vapor.yml`                           |
| AWS S3        | File Storage      | Flysystem        | Laravel filesystem config             |
| MySQL         | Database          | Vapor Managed    | Database config, 25+ migrations      |
| Telegram      | Bot Integration   | Webhook          | `app/Telegram/`, `app/Models/TelegramChat.php` |
| OpenAI        | AI Integration    | REST API         | `openai-php/laravel` package          |
| GitHub        | CI/CD             | Actions          | `.github/workflows/`                  |
| CloudFront    | CDN               | Vapor            | `vapor.yml` cloudfront config         |

### Internal Integration Points

- **Nova Admin**: Access via `/nova` - comprehensive admin interface with 17 resources
- **API Authentication**: Two patterns - Sanctum for web, API token for external
- **Queue Jobs**: 5 job classes in `app/Jobs/` - processed on Lambda
- **Telescope**: Laravel debugging (if enabled)
- **Artisan Commands**: 4 console commands for maintenance tasks

## Development and Deployment

### Local Development Setup

**CRITICAL**: Must use Laravel Sail (Docker) for consistency with production ARM environment.

```bash
# 1. Clone and setup
git clone https://github.com/wilng2005/daily-driver.git
cd daily-driver
cp .env.example .env

# 2. Start Sail (Docker required)
./vendor/bin/sail up -d
./vendor/bin/sail composer install
./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev

# 3. Setup application
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed  # Optional sample data
```

**Known Setup Issues**:
- Uses `pnpm` in production but `npm` works locally via Sail
- Requires Docker for Sail - no local PHP development recommended
- Nova license must be configured in `auth.json`

### Build and Deployment Process

**Automated CI/CD** (NO manual deploys):
- **Staging**: Push to `staging` branch → GitHub Actions → Vapor deploy
- **Production**: Push to `main` branch → GitHub Actions → Vapor deploy

**Build Process** (see `vapor.yml`):
1. `composer install` with mirror paths
2. `php artisan event:cache`
3. `npm ci && npm run build && rm -rf node_modules`
4. Deploy with migrations (`php artisan migrate --force`)
5. `php artisan telegram:setwebhook`

**Environments**:
- **Local**: Docker Sail
- **Staging**: `staging-a01.than.today` (ARM Lambda)
- **Production**: `greater.than.today` (ARM Lambda)

## Testing Reality

### Current Test Coverage

- **Policy**: **100% code coverage ENFORCED** in CI/CD pipeline
- **Unit Tests**: 6 tests in `tests/Unit/`
- **Feature Tests**: 16 comprehensive tests in `tests/Feature/`
- **Browser Tests**: 13 Dusk tests in `tests/Browser/` (Selenium-based)
- **TDD Workflow**: Test-driven development required for all features

### Running Tests

```bash
# Local testing via Sail
./vendor/bin/sail test                                    # All tests
./vendor/bin/sail dusk                                   # Browser tests
./vendor/bin/sail test --coverage-html=./coverage-report # Coverage report

# CRITICAL: Always check coverage locally before pushing
# Open coverage-report/index.html to verify 100% coverage
```

**Test Infrastructure**:
- PHPUnit 10.0 for unit/feature tests
- Laravel Dusk 8.0 for browser automation
- Selenium containers via Sail
- Carbon freezing for time-dependent tests (see `DoDailyScheduleTest.php`)

## Nova Admin Panel Architecture

### Nova Resources Overview

The Nova admin panel is the **primary content management interface** with 17 resource definitions:

**Core Resources**:
- `Nova/Capture.php` (4.6KB) - Task management with actions, filters, metrics
- `Nova/Insight.php` + `Nova/InsightSection.php` - Content publishing system
- `Nova/User.php` (3.8KB) - User management with resource access controls
- `Nova/Post.php` (3.1KB) - Blog content management
- `Nova/TelegramChat.php` (3.4KB) - Chat management interface

**Nova Features Used**:
- **Actions**: 15 custom actions in `Nova/Actions/`
- **Filters**: 4 custom filters in `Nova/Filters/`
- **Lenses**: 4 custom lenses in `Nova/Lenses/`
- **Metrics**: 6 dashboard metrics in `Nova/Metrics/`
- **Dashboards**: Custom dashboard in `Nova/Dashboards/`

## API Architecture Details

### OpenAI/ChatGPT Integration APIs

**Public APIs** designed for ChatGPT plugin/tool usage:
- `GET /api/open-ai/random-number` - Dice rolling simulation
- `GET /api/open-ai/timestamp` - Timezone-aware timestamps
- Each has dedicated OpenAPI schema endpoints

**Design Pattern**: Public endpoints return OpenAPI schemas for discoverability.

### Authentication Architecture

**Two-Tier System**:
1. **Sanctum** (`auth:sanctum`) - Web users, full Laravel auth
2. **API Token** (`X-API-Token` header) - External integrations, single shared token

**Security Limitation**: Single API token shared across all external integrations (not per-user or per-application).

## Deployment Architecture (Vapor/Lambda)

### Serverless Constraints

**ARM Runtime**: `php-8.3:al2-arm` for cost/performance optimization
**Memory**: 1024MB application, 512MB CLI
**Timeout**: 600 seconds maximum
**Storage**: Vapor managed storage, not ephemeral Lambda storage

### CloudFront Configuration

**API Caching**: API routes (`/api/*`) explicitly configured with TTL=0 (no caching)
**Static Assets**: Standard CloudFront caching for public assets

## Known Constraints and "Gotchas"

### Development Constraints
1. **Sail Required**: Local development must use Docker Sail for ARM compatibility
2. **100% Test Coverage**: All code changes must achieve 100% test coverage
3. **CSS Build Process**: Manual `npm run build` required after CSS changes in `public/sass/main.css`
4. **Image Config Sync**: Adding images requires updating `config/image_dimensions.php`

### Production Constraints
1. **Single User System**: All APIs assume `user_id = 1` - not multi-user ready
2. **Shared Database**: Staging and production share same database instance
3. **ARM Runtime**: Deployment locked to ARM Lambda architecture
4. **Nova License**: Commercial license dependency via private composer repository

### Integration Constraints
1. **Telegram Bot**: Complex 33KB model with intricate state management
2. **API Token**: Single shared token for all external integrations
3. **OpenAI Integration**: Public endpoints designed for ChatGPT tool usage

## Appendix - Useful Commands and Scripts

### Frequently Used Commands

```bash
# Local Development (via Sail)
./vendor/bin/sail up -d           # Start development environment
./vendor/bin/sail down            # Stop development environment
./vendor/bin/sail composer install # Install PHP dependencies
./vendor/bin/sail npm install     # Install Node dependencies
./vendor/bin/sail npm run build   # Build production assets

# Database Operations
./vendor/bin/sail artisan migrate:fresh --seed  # Reset with sample data
./vendor/bin/sail artisan db:seed --class=InsightSeeder  # Specific seeder

# Testing (CRITICAL - run before every push)
./vendor/bin/sail test --coverage-html=./coverage-report
./vendor/bin/sail dusk            # Browser tests

# Utility Scripts
./vendor/bin/sail php scripts/dump_image_sizes.php  # Update image config
```

### Debugging and Troubleshooting

- **Logs**: CloudWatch logs on staging/production (via Vapor)
- **Local Logs**: `storage/logs/laravel.log`
- **Test Failures**: Check `coverage-report/index.html` for coverage gaps
- **Nova Issues**: Check `/nova` access and license in `auth.json`
- **API Testing**: Use documented endpoints at `/api/open-ai/schema`

### Critical Maintenance Tasks

1. **Before Every Deploy**: Run full test suite with coverage report
2. **Image Updates**: Update `config/image_dimensions.php` when adding images
3. **CSS Changes**: Manual build required after editing `public/sass/main.css`
4. **Database Changes**: Always include migrations in feature branches
5. **Nova Updates**: Ensure commercial license compatibility

## Success Metrics

This brownfield documentation enables AI agents to:
- ✅ Understand the actual system architecture (serverless Laravel on ARM)
- ✅ Navigate the complex codebase structure and key files
- ✅ Respect critical constraints (100% test coverage, single-user limitation)
- ✅ Follow established patterns (Nova resources, API authentication)
- ✅ Avoid known gotchas (manual CSS builds, image config sync)
- ✅ Work within production constraints (Vapor deployment, ARM runtime)
- ✅ Maintain code quality standards (TDD workflow, comprehensive testing)

## Notes

- This document reflects the ACTUAL state including technical debt and workarounds
- References actual files rather than duplicating content when possible
- Documents constraints and "gotchas" honestly for AI agent safety
- Emphasizes the sophisticated CI/CD and testing infrastructure
- Highlights the single-user limitation as a critical constraint
- The goal is PRACTICAL documentation for AI agents doing real work on this mature codebase