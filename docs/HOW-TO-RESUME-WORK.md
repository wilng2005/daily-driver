# How to Resume Work on Daily Driver

**For:** You (the human) when starting a new session
**Purpose:** Quick guide to get back into context efficiently

---

## 🎯 Quick Start (30 seconds)

When you're ready to work on Daily Driver:

1. **Start Claude Code / BMad Orchestrator**
   ```
   /BMad:agents:bmad-orchestrator
   ```

2. **Tell the agent to read PROJECT-STATUS.md**
   ```
   "Read docs/PROJECT-STATUS.md and tell me where we left off"
   ```

3. **Choose your path:**
   - The agent will offer you options based on current priorities
   - Pick what you want to work on today
   - The agent will load the relevant context and dive in

---

## 📋 What Agents Should Do (Automatically)

BMad agents are instructed to:

1. ✅ Read `.bmad-core/SESSION-STARTUP.md` on activation
2. ✅ Read `docs/PROJECT-STATUS.md` for current context
3. ✅ Greet you with a summary of where you left off
4. ✅ Offer you 3 prioritized paths forward
5. ✅ Let you choose what to focus on

**You don't need to explain context every time - it's all in PROJECT-STATUS.md**

---

## 📍 Key Files to Know About

### **For You (Human):**

- **`docs/PROJECT-STATUS.md`** - Your session memory
  - Read this if you want to know where you left off
  - Updated at the end of each session
  - Contains trajectory, priorities, recent work

- **`docs/HOW-TO-RESUME-WORK.md`** - This file
  - Quick reference for starting sessions

- **`docs/issues/`** - Active work items
  - 6 active issues currently
  - Each has detailed requirements and context

### **For Agents (They read these automatically):**

- **`.bmad-core/SESSION-STARTUP.md`** - Agent startup protocol
- **`docs/PROJECT-STATUS.md`** - Current project state
- **`docs/brownfield-architecture.md`** - System architecture
- **`.bmad-core/core-config.yaml`** - Project configuration

---

## 🔄 Typical Session Flow

```
You: Start BMad Orchestrator
Agent: Reads startup files automatically
Agent: "I see we last worked on X. Here are three paths forward..."
You: "Let's work on the mailing list feature"
Agent: Loads mailing list epic context
Agent: "I'll transform to PM agent and create the epic. Ready?"
You: "Yes"
[Work happens]
You: "I need to stop here"
Agent: Updates PROJECT-STATUS.md
Agent: "I've updated the project status. Next time, we can continue with..."
```

---

## 💡 Pro Tips

### **If You Forget Where You Were:**

Just ask: **"Where did I leave off?"**

The agent will read PROJECT-STATUS.md and tell you.

### **If Priorities Changed:**

Tell the agent: **"I want to work on [X] instead"**

The agent will pivot and load the new context.

### **If You Want to See All Options:**

Ask: **"What are all my active issues?"**

The agent will list everything from `docs/issues/`

### **If You're Not Sure What to Do:**

The agent will offer you the **"Recommended Next Session Actions"** from PROJECT-STATUS.md:
1. Build mailing list feature (top priority)
2. Code cleanup (safe, valuable)
3. Quick wins - API endpoints (fast)

---

## 🎭 Working with BMad Agents

### **Specialized Agents:**

- **PM (John)** - For creating epics, stories, PRDs
- **Dev (James)** - For implementing code
- **QA (Quinn)** - For testing and quality gates
- **Architect (Winston)** - For system design
- **Orchestrator** - For coordination and choosing which agent to use

### **How to Switch Agents:**

```
*agent pm        # Transform to Product Manager
*agent dev       # Transform to Developer
*agent qa        # Transform to QA
```

### **Get Help Anytime:**

```
*help           # Show available commands
*status         # Show current context
```

---

## 📊 Project Health at a Glance

**Status:** Active, production system (greater.than.today)

**Recent Progress:**
- ✅ Issue triage complete (6 active, 18+ archived)
- ✅ Insights Module working well in production
- ✅ Mailing list feature scoped and ready
- ✅ Cleanup story created with safety plan

**Next Major Milestone:** Mailing list lead magnet feature

**Technical Health:** Good
- 100% test coverage maintained
- CI/CD working smoothly
- Staging and production stable

---

## 🆘 If Something Seems Off

**Agent doesn't remember context?**
→ Ask: "Have you read docs/PROJECT-STATUS.md?"

**Can't find an issue?**
→ Check: `docs/issues/` folder (or `docs/issues/archived/`)

**Need architecture context?**
→ Point agent to: `docs/brownfield-architecture.md`

**Something broken in production?**
→ Check: CloudWatch logs, rollback plan in relevant issue

---

## 🔄 Maintaining the System

**PROJECT-STATUS.md should be updated:**
- At the end of each significant session
- When priorities change
- After completing major tasks
- When making key decisions

**You can request updates:**
- "Update PROJECT-STATUS.md with what we just did"
- "Add this to the project status"
- "Document this decision in project status"

**Agents are instructed to update it automatically at session end.**

---

## ✨ The System in Action

**Goal:** Never lose context between sessions

**How:**
1. All context lives in `docs/PROJECT-STATUS.md`
2. Agents read it on startup
3. You pick where to start
4. Work happens
5. Context gets updated
6. Repeat next session

**Result:** You always know exactly where you left off and what's next.

---

**You're all set! Start the Orchestrator and let it guide you from here.**

---

_Last updated: 2026-01-05_
_System working: ✅_
_Context preserved: ✅_
