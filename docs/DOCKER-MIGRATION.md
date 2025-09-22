# Vapor Docker Runtime Migration Plan

## Problem Statement
- Project size: 404MB
- Vapor native runtime deployment size limits blocking deployments
- Need immediate solution to resume staging/production deployments

## Solution: Docker Runtime Migration
Migrate from Vapor's native PHP runtime to Docker-based runtime (10GB size limit)

## Implementation Status

### ✅ Phase 1: Emergency Fix (COMPLETED)
**Goal**: Unblock deployments immediately

**Changes Made**:
1. **Created `.Dockerfile`**:
   ```dockerfile
   FROM laravelphp/vapor:php84-arm
   COPY . /var/task
   ```

2. **Created `.dockerignore`**:
   - Excludes: `.git/`, `coverage/`, `node_modules/`, `tests/`, etc.
   - Estimated size reduction: 404MB → ~90MB

3. **Updated `vapor.yml` - Staging Environment**:
   ```yaml
   staging:
     runtime: docker-arm  # Changed from 'php-8.3:al2-arm'
   ```

### 🧪 Phase 2: Testing (IN PROGRESS)
- **Status**: Deploying to staging environment
- **Command**: `vapor deploy staging`
- **Expected**: First deployment takes longer (Docker image build)
- **Validation**: Ensure application functions identically

### ⏳ Phase 3: Production Migration (PENDING)
**Prerequisites**: ✅ Staging deployment successful & validated

**Changes Required**:
```yaml
production:
  runtime: docker-arm  # Change from 'php-8.3:al2-arm'
```

**⚠️ CRITICAL WARNING**:
> Docker runtime migration is **IRREVERSIBLE** per Vapor documentation.
> Cannot revert to native runtime after migration.

## Architecture Benefits
- **Size Limit**: 404MB → 10GB capacity (96% headroom)
- **ARM Architecture**: Maintained for cost efficiency
- **Custom Dependencies**: Can install additional PHP extensions/packages
- **Build Reproducibility**: Consistent deployment artifacts

## Future Optimizations (Phase 4)
- [ ] Unify Sail and Vapor Docker configurations (environment parity)
- [ ] Multi-stage Dockerfile for development/production
- [ ] Optimize image layers and build cache
- [ ] Custom PHP.ini configurations if needed

## Rollback Plan
⚠️ **No rollback possible** once Docker runtime deployed
- Keep native runtime environments as backup during testing
- Create new environments for testing if needed

## Monitoring
- Watch first Docker deployment for issues
- Validate all application functionality
- Monitor cold start performance vs native runtime
- Check memory usage patterns

---
**Migration Date**: 2025-09-19
**Architect**: Winston (Claude Code)
**Status**: Phase 2 - Testing Staging Deployment