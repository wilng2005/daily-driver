# Mailing List & Lead Magnet Feature

**Status:** Pending Epic Creation
**Date Created:** 2026-01-05
**Agent Session:** BMad Orchestrator → PM (John)
**Next Step:** Run `*create-brownfield-epic` with PM agent

---

## Feature Summary

Top-of-funnel lead magnet system: Users can sign up for the mailing list to receive a free saboteur assessment invitation. The assessment itself is handled by an external system, but we need the full mailing list infrastructure.

---

## Requirements Clarified

### 1. Mailing List Infrastructure
- **No existing integration** - Need to collect emails in the database
- **Email collection** - Store subscribers with status tracking
- **Templated emails** - Ability to send templated emails to the mailing list
- **Unsubscribe functionality** - Users must be able to unsubscribe

### 2. Saboteur Assessment
- **External system** - Assessment is triggered/handled on a separate system
- **No results handling** - We don't need to send or manage assessment results
- **Our role** - Just collect email signups and send initial invitation

### 3. Current System Context
- **No existing lead capture forms** in the system
- **No existing mailing list table** in database schema
- **Existing email capability** - Laravel on AWS Lambda (likely using SES)
- **Queue infrastructure** - Already has Laravel queues for background jobs

---

## Scope Assessment

This is **NOT a single story** - this is an **EPIC** requiring 2-3 coordinated stories.

### Why Epic vs Story?
- New database table and model required
- Multiple integration points (DB, email, queues, Nova)
- New frontend components (lead capture form, unsubscribe page)
- Email templating system needed
- Nova admin interface for management
- Significant infrastructure work beyond existing patterns

---

## Proposed Epic Breakdown

### Story 1: Core Mailing List Infrastructure
- Create `subscribers` database table (email, status, tokens, timestamps)
- Create `Subscriber` Eloquent model with validation
- Create Nova resource for subscriber management
- Basic CRUD operations in Nova admin
- Tests for model and basic operations

### Story 2: Subscribe/Unsubscribe Flows
- Lead capture form/landing page (frontend component)
- Subscribe API endpoint with validation
- Unsubscribe token generation and validation
- Unsubscribe page with token-based confirmation
- Email confirmation on signup (optional)
- Tests for flows

### Story 3: Email Templating & Campaign System
- Email template management (likely Nova resource or config)
- "Send to list" functionality in Nova
- Queue job for bulk email sending
- Welcome email template (with saboteur assessment invite)
- Broadcast campaign functionality
- Tests for email sending

---

## Technical Considerations

### Database Schema (Proposed)
```
subscribers table:
- id (bigint, primary key)
- email (string, unique, indexed)
- status (enum: 'active', 'unsubscribed', 'bounced')
- unsubscribe_token (string, unique, indexed)
- source (string: 'saboteur_assessment', etc.)
- subscribed_at (timestamp)
- unsubscribed_at (timestamp, nullable)
- created_at (timestamp)
- updated_at (timestamp)
- deleted_at (timestamp, soft deletes)
```

### Integration Points
- **Email Service:** AWS SES (via Vapor)
- **Queue Jobs:** Laravel queues on Lambda
- **Admin Interface:** Nova resource with actions
- **Frontend:** New landing page route/blade template
- **API:** Public subscribe endpoint, token-based unsubscribe endpoint

### Constraints
- **Single-user system** - Admin-only campaign sending (no multi-user concerns)
- **100% test coverage** - All new code must have comprehensive tests
- **Serverless Lambda** - Email sending must use queues for bulk operations
- **ARM runtime** - No special considerations needed

---

## Next Steps

1. **Run `*create-brownfield-epic`** with PM agent (John)
2. **Create epic** with the 3 stories outlined above
3. **Validate with user** before implementation
4. **Assign to Dev agent** for implementation after approval

---

## Session Context

The user was working with the BMad Orchestrator, transformed to PM agent (John), and started the `*create-brownfield-story` task. During scoping questions, we determined this was too large for a single story and needs an epic instead.

**To resume:** Run `/BMad:agents:pm` then execute `*create-brownfield-epic`
