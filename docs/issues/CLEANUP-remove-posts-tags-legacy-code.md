# Cleanup: Remove Posts & Tags Legacy Code

**Status:** Draft - Needs Validation
**Created:** 2026-01-05
**Risk Level:** Medium (Production cleanup requires careful testing)

---

## Summary

Remove unused Posts and Tags models and related code that have been superseded by the Insights Module. The Posts/Tags system is completely unused in the current application - all content is now managed via Insights or static article Blade files.

---

## Background

The AI-Articles feature (FEATURE-AI-ARTICLES.md) was deprecated on 2026-01-05 in favor of the more sophisticated Insights Module. Investigation revealed that Posts and Tags models are not used anywhere in the current application:

- **Insights Module** loads via View Composer in `AppServiceProvider` and displays in "Insights and Stories" section
- **Static articles** are 4 hardcoded Blade files in `resources/views/articles/`
- **Posts query** exists in homepage route but `$posts` variable is never used in the template
- **Tags** are not referenced anywhere

---

## Investigation & Validation Phase

### ✅ Step 1: Verify Posts Are Not Used

**Database Check:**
- [ ] Connect to **staging** database and check if `posts` table has any data
- [ ] Connect to **production** database and check if `posts` table has any data
- [ ] Document any existing posts (if any) - screenshot and backup
- [ ] Check `tags` and `post_tag` tables for data

**Code Usage Check:**
- [ ] Search entire codebase for `Post::` references (excluding migrations/seeders)
- [ ] Search entire codebase for `Tag::` references (excluding migrations/seeders)
- [ ] Verify `$posts` variable is not used in any Blade templates
- [ ] Check all Nova dashboards/resources to ensure Posts aren't displayed
- [ ] Review any scheduled jobs/commands for Post references

**Testing Check:**
- [ ] Review all existing tests - ensure none depend on Posts/Tags functionality
- [ ] Check Dusk/browser tests for Post-related assertions

---

### ✅ Step 2: Document Migration Path (If Data Exists)

**If production has Posts data:**
- [ ] Export all posts to CSV/JSON backup
- [ ] Create migration script to convert Posts → Insights (if needed)
- [ ] Get user approval on whether to migrate or archive data

**If no production data:**
- [ ] Document that tables are empty and safe to remove
- [ ] Proceed with cleanup

---

## Cleanup Implementation

### Phase 1: Code Removal

**Routes (routes/web.php):**
- [ ] Remove `use App\Models\Post;` (line 3)
- [ ] Remove `use App\Models\Tag;` (line 4)
- [ ] Remove Posts query from homepage route (lines 29-32)
- [ ] Remove `'posts' => $posts,` from view data (line 47)

**Models:**
- [ ] Delete `app/Models/Post.php`
- [ ] Delete `app/Models/Tag.php` (if exists)

**Nova Resources:**
- [ ] Delete `app/Nova/Post.php`
- [ ] Delete `app/Nova/Filters/PostSource.php`
- [ ] Delete `app/Nova/Filters/PostStatus.php`

**Views:**
- [ ] Delete `resources/views/post.blade.php`
- [ ] Delete `resources/views/articles.blade.php` (NOT the /articles/ directory)
- [ ] Verify `resources/views/articles/` directory is preserved (contains 4 static articles)

**Tests:**
- [ ] Delete `tests/Feature/PostSeederTest.php`
- [ ] Review and update any other tests that reference Posts

**Seeders:**
- [ ] Delete `database/seeders/PostSeeder.php`
- [ ] Update `database/seeders/DatabaseSeeder.php` if it references PostSeeder

**Database Migrations:**
- [ ] Create new migration to drop tables (see Phase 2 below)
- [ ] Do NOT delete old migrations (keep for historical reference)

---

### Phase 2: Database Migration

**Create Migration:**
```bash
php artisan make:migration drop_posts_and_tags_tables
```

**Migration Content:**
```php
public function up(): void
{
    Schema::dropIfExists('post_tag');
    Schema::dropIfExists('tags');
    Schema::dropIfExists('posts');
}

public function down(): void
{
    // Restore from backup if needed
    // Reference original migrations for table structure
}
```

**IMPORTANT:**
- [ ] Run migration on **local** first
- [ ] Run migration on **staging** second
- [ ] Verify staging for 24-48 hours before production
- [ ] Run migration on **production** only after staging verification

---

## Testing Strategy

### ✅ Local Testing (Required)

**Unit/Feature Tests:**
- [ ] Run full test suite: `./vendor/bin/sail test`
- [ ] Verify 100% code coverage maintained
- [ ] Fix any failing tests

**Browser Tests:**
- [ ] Run Dusk tests: `./vendor/bin/sail dusk`
- [ ] Verify "Insights and Stories" section displays correctly
- [ ] Verify static articles load at `/article/{slug}`
- [ ] Verify homepage loads without errors

**Manual Verification:**
- [ ] Homepage (`/`) loads correctly
- [ ] "Insights and Stories" section shows Insights (not Posts)
- [ ] All 4 static article routes work: `/article/five-science-backed-strategies`, etc.
- [ ] `/insights` index page works
- [ ] `/insights/{slug}` detail pages work
- [ ] Nova admin loads without Posts resource
- [ ] Check browser console for JavaScript errors
- [ ] Check Laravel logs for PHP errors

---

### ✅ Staging Testing (Required)

**Deploy to Staging:**
- [ ] Push cleanup branch to `staging`
- [ ] Wait for CI/CD to deploy
- [ ] Run smoke tests on staging environment

**Smoke Tests:**
- [ ] Visit staging homepage: `staging-a01.than.today`
- [ ] Verify "Insights and Stories" section works
- [ ] Test all 4 static article URLs
- [ ] Test `/insights` and `/insights/{slug}` routes
- [ ] Check Nova admin - verify no errors
- [ ] Monitor CloudWatch logs for errors
- [ ] Let staging run for 24-48 hours minimum

---

### ✅ Production Deployment (Final Step)

**Pre-Deployment:**
- [ ] Staging has been verified for 24-48 hours with no issues
- [ ] All tests passing in CI/CD
- [ ] User approval obtained
- [ ] Backup strategy confirmed

**Deployment:**
- [ ] Merge to `main` branch
- [ ] Monitor deployment in GitHub Actions
- [ ] Watch CloudWatch logs for errors
- [ ] Test production immediately after deploy

**Post-Deployment Verification:**
- [ ] Visit `greater.than.today` homepage
- [ ] Verify "Insights and Stories" section
- [ ] Test sample article and insight URLs
- [ ] Monitor for 1 hour for any issues

---

## Rollback Plan

**If Issues Occur:**

1. **Immediate Actions:**
   - [ ] Revert Git commit: `git revert <commit-hash>`
   - [ ] Push to trigger re-deploy: `git push origin main`
   - [ ] Monitor deployment

2. **Database Rollback (if migration ran):**
   - [ ] Run migration rollback: `php artisan migrate:rollback --step=1`
   - [ ] Verify tables restored
   - [ ] Restore data from backup (if needed)

3. **Code Rollback:**
   - [ ] All deleted files are in Git history
   - [ ] Can restore via `git revert` or `git checkout`

---

## Risk Assessment

**Medium Risk Factors:**
- Production database changes (dropping tables)
- Code removal without comprehensive testing could break something unexpected

**Mitigation:**
- Thorough investigation phase before any changes
- Multi-stage testing (local → staging → production)
- 24-48 hour staging verification
- Clear rollback plan
- All changes tracked in Git

**Critical Safeguards:**
- ✅ 100% test coverage requirement enforced by CI/CD
- ✅ Database migration tested on local and staging first
- ✅ Staging environment mirrors production
- ✅ Can revert via Git at any time

---

## Acceptance Criteria

**Investigation Complete:**
- [ ] Production database checked for Posts/Tags data
- [ ] All code references to Post/Tag documented
- [ ] Migration path decided (delete vs migrate data)

**Cleanup Complete:**
- [ ] All legacy Post/Tag code removed from codebase
- [ ] Database migration created and tested
- [ ] All tests passing (100% coverage maintained)
- [ ] Staging verified for 24-48 hours
- [ ] Production deployment successful

**Verification:**
- [ ] Homepage works correctly
- [ ] "Insights and Stories" section displays Insights
- [ ] Static articles accessible at `/article/*` routes
- [ ] Insights accessible at `/insights/*` routes
- [ ] Nova admin loads without errors
- [ ] No errors in CloudWatch logs
- [ ] No JavaScript console errors

---

## Notes

- **DO NOT** rush this cleanup - take time to verify at each stage
- **DO NOT** skip staging verification period
- **DO NOT** delete migrations (keep for historical reference)
- Keep old migration files even after tables are dropped
- The 4 static article Blade files in `resources/views/articles/` must be preserved
- Insights Module is the future - ensure it's working perfectly before removing Posts

---

## Checklist Summary

**Before Starting:**
- [ ] Read this entire document
- [ ] Understand each phase
- [ ] Have rollback plan ready

**Phase 1 - Investigation:**
- [ ] Check production database
- [ ] Verify code usage
- [ ] Review all tests

**Phase 2 - Local Development:**
- [ ] Remove code
- [ ] Create migration
- [ ] Run tests
- [ ] Manual verification

**Phase 3 - Staging:**
- [ ] Deploy to staging
- [ ] Run smoke tests
- [ ] Wait 24-48 hours
- [ ] Monitor logs

**Phase 4 - Production:**
- [ ] Get final approval
- [ ] Deploy to production
- [ ] Monitor closely
- [ ] Verify all functionality

---

**Created:** 2026-01-05
**Status:** Draft - Ready for Investigation Phase
**Next Step:** Begin Phase 1 Investigation on staging/production databases
