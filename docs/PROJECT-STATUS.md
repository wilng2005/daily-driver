# Daily Driver - Project Status & Session Context

**Last Updated:** 2026-01-05
**Project:** Daily Driver (greater.than.today) - Coaching Platform
**Tech Stack:** Laravel 11, Nova 5, Serverless (AWS Lambda via Vapor)

---

## 🎯 Current Trajectory & Priorities

### **Top Priority: Mailing List Lead Magnet Feature**

**Status:** Requirements gathered, ready for epic creation

**What it is:** Top-of-funnel lead magnet system where users sign up for the mailing list to receive a free saboteur assessment invitation (assessment handled by external system).

**Why it matters:** Growth/conversion optimization - capture leads through valuable free offering.

**What's needed:**
1. Mailing list infrastructure (subscribers table, model, Nova resource)
2. Subscribe/unsubscribe flows (lead capture form, token-based unsubscribe)
3. Email templating & campaign system (welcome emails, broadcasts)

**Next Action:** Transform to PM agent and run `*create-brownfield-epic`

**Reference:** `docs/issues/mailing-list-lead-magnet.md`

---

### **Secondary Priority: Code Cleanup**

**Status:** Story created, ready for investigation phase

**What it is:** Remove unused Posts & Tags legacy code (superseded by Insights Module)

**Why it matters:** Reduce technical debt, cleaner codebase, avoid confusion

**Next Action:** Begin investigation phase - check production database for Posts data

**Reference:** `docs/issues/CLEANUP-remove-posts-tags-legacy-code.md`

---

## 📊 Recent Work Summary (2026-01-05 Session)

### **What We Accomplished:**

1. **Issue Triage & Cleanup:**
   - Archived 6 completed issues (API endpoints, tracking features)
   - Deprecated AI-Articles feature (superseded by Insights Module)
   - Reduced active issues from 13 → 6 (much cleaner!)

2. **Mailing List Feature Scoping:**
   - Gathered detailed requirements
   - Determined it's an epic (not single story)
   - Documented full scope and technical considerations

3. **Code Investigation:**
   - Investigated Posts/Tags usage (confirmed 100% dead code)
   - Created comprehensive cleanup story with safety checks
   - Identified Insights Module as the current content system

### **Key Decisions Made:**

- ✅ Insights Module is the future (Posts/Tags are legacy)
- ✅ Mailing list needs epic breakdown (3 stories estimated)
- ✅ Cleanup requires careful testing (multi-phase approach)

---

## 📁 Active Issues Overview

### **High Priority**

1. **mailing-list-lead-magnet.md** (NEW)
   - Epic creation pending
   - All requirements gathered
   - Clear path forward

2. **CLEANUP-remove-posts-tags-legacy-code.md** (NEW)
   - Investigation phase ready
   - Comprehensive safety plan
   - Medium risk, high value

### **Medium Priority - API Endpoints**

3. **ISSUE-SOFT-DELETE-CAPTURE.md**
   - DELETE /api/captures/{id} endpoint needed
   - Small scope, quick win

4. **ISSUE-UPDATE-CAPTURE-ENDPOINT.md**
   - PUT /api/captures/{id} endpoint needed
   - Small scope, quick win

### **In Progress - Features**

5. **FEATURE-INSIGHTS-MODULE.md**
   - Backend complete ✅
   - Needs frontend Blade components
   - Working well in production

6. **ISSUE-INSIGHTS-INDEX.md**
   - Scaffolded, on hold
   - Low priority polish

### **Concept/Discovery**

7. **feature-smart-delay-patterns.md** (in docs/)
   - Problem statement only
   - High ROI (save 60min/day)
   - Needs design work

---

## 🗺️ Project Landscape

### **Content Management Systems**

**Active System:**
- **Insights Module** - Rich multi-section content with markdown, images, ordering
  - Backend: ✅ Complete (models, Nova, tests)
  - Frontend: 🚧 Functional but could use polish
  - Status: In production, working well

**Static Content:**
- 4 hardcoded article Blade files in `resources/views/articles/`
- Accessed via `/article/{slug}` routes
- Working fine, no changes needed

**Legacy (To Remove):**
- Posts & Tags models - 100% unused
- See cleanup story for removal plan

### **Current Architecture Highlights**

- **Single-user system** - All APIs hardcode `user_id = 1` (critical constraint)
- **Serverless deployment** - AWS Lambda via Vapor (ARM runtime)
- **100% test coverage** - Enforced by CI/CD
- **Brownfield project** - Existing codebase with technical debt documented

**Key Files:**
- Architecture: `docs/brownfield-architecture.md`
- Database: `docs/DB_SCHEMA.md`
- API: `docs/API.md`

---

## 🚀 Recommended Next Session Actions

When starting next session, you have **three clear paths**:

### **Path 1: Build Mailing List Feature (Recommended)**

**Why:** Top priority, clear requirements, high business value

**How:**
1. Run `/BMad:agents:pm` to activate PM agent
2. Execute `*create-brownfield-epic`
3. Work through epic creation task
4. Break down into 3 stories (DB, flows, emails)

**Estimated Time:** 30-45 min to create epic, then implementation in sprints

---

### **Path 2: Code Cleanup (Safe, Valuable)**

**Why:** Reduce technical debt, practice safe production cleanup

**How:**
1. Start with Investigation Phase (check production DB)
2. Follow checklist in cleanup story
3. Multi-stage testing approach (local → staging → production)

**Estimated Time:** 2-3 hours for full cleanup (spread across days for staging soak)

---

### **Path 3: Quick Wins - API Endpoints (Fast)**

**Why:** Small, well-defined, easy to complete

**How:**
1. Pick ISSUE-SOFT-DELETE-CAPTURE or ISSUE-UPDATE-CAPTURE-ENDPOINT
2. Implement single API endpoint with tests
3. Deploy and close issue

**Estimated Time:** 30-60 min per endpoint

---

## 📚 Quick Reference

### **Key Documents**

- **This file:** Project status & trajectory
- **Architecture:** `docs/brownfield-architecture.md`
- **Issues folder:** `docs/issues/` (6 active, 18+ archived)
- **Mailing list spec:** `docs/issues/mailing-list-lead-magnet.md`
- **Cleanup story:** `docs/issues/CLEANUP-remove-posts-tags-legacy-code.md`

### **BMad Agent Commands**

- `*help` - Show available commands
- `*agent pm` - Transform to Product Manager (for epics/stories)
- `*agent dev` - Transform to Developer (for implementation)
- `*agent qa` - Transform to QA (for testing)
- `*status` - Show current context

### **Development Workflow**

```bash
# Local development (via Sail/Docker)
./vendor/bin/sail up -d
./vendor/bin/sail test --coverage-html=./coverage-report
./vendor/bin/sail dusk

# Deployment
git push origin staging  # Auto-deploys to staging
git push origin main     # Auto-deploys to production
```

### **Environment URLs**

- **Local:** http://localhost
- **Staging:** staging-a01.than.today
- **Production:** greater.than.today
- **Nova Admin:** /nova

---

## 🎭 Session Context for BMad Agents

### **When Starting a New Session:**

1. **Read this file first** - Understand current trajectory
2. **Ask user:** "Where did we leave off? I see three paths: mailing list feature, code cleanup, or API endpoints. Which would you like to focus on?"
3. **Load relevant context** - Read the specific issue/story file for chosen path
4. **Confirm understanding** - Summarize the task and get user confirmation before proceeding

### **User's Working Style:**

- Prefers **careful, tested approaches** (especially for production changes)
- Values **comprehensive documentation** for future sessions
- Likes **numbered options** for decision points
- Appreciates **clear next steps** and **progress tracking**
- Uses **BMad Method** with agent personas

### **Critical Constraints:**

- ✅ **100% test coverage** - Non-negotiable, enforced by CI/CD
- ✅ **Multi-stage testing** - Local → Staging (48hr soak) → Production
- ✅ **Single-user system** - Not multi-user ready (hardcoded user_id = 1)
- ✅ **Serverless limitations** - ARM Lambda, can't rely on local filesystem

---

## 🔄 How to Update This Document

**When to update:**
- End of each significant work session
- After completing a major task or feature
- When priorities shift
- After making key decisions

**What to update:**
- Last Updated date
- Current Trajectory & Priorities section
- Recent Work Summary (replace with latest session)
- Active Issues Overview (add/remove as needed)
- Recommended Next Session Actions

**Who should update:**
- Any BMad agent at end of session
- User can request updates anytime

---

## 💡 Tips for Future Sessions

### **For Efficient Resumption:**

1. Start by asking: "What do you want to work on today?"
2. Offer the 3 paths from "Recommended Next Session Actions"
3. Let user decide based on their current priorities
4. Load the specific issue/story and dive in

### **For Long Gaps Between Sessions:**

- Review recent git commits: `git log --oneline --graph -10`
- Check deployed changes in staging/production
- Review any new issues added to `docs/issues/`
- Ask user if priorities have shifted

### **For Context Switching:**

- Use `*status` command to see where you are
- Reference this document to reorient
- Don't assume - always confirm with user before major actions

---

**This document is your compass. Start here, orient yourself, then chart the course with the user.**

---

_Last session: Cleaned up issues, scoped mailing list feature, investigated code cleanup opportunity._
_Next session: User will choose between mailing list epic, code cleanup, or API endpoints._
