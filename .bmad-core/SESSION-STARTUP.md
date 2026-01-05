# BMad Agent Session Startup Protocol

**CRITICAL: Read this when starting any new session on this project**

---

## 📋 Startup Checklist

When a BMad agent is activated for this project:

1. ✅ **Read PROJECT-STATUS.md FIRST**
   - Location: `docs/PROJECT-STATUS.md`
   - Contains: Current trajectory, priorities, recent work, next actions
   - Purpose: Orient yourself before engaging with user

2. ✅ **Greet the user with context**
   - Example: "I see we last worked on [X]. We have three paths forward: [list options]. What would you like to focus on today?"

3. ✅ **Confirm direction before proceeding**
   - Let user choose their priority
   - Load the relevant issue/story document
   - Summarize understanding and get confirmation

---

## 📍 Key Project Files

**Always available:**
- `docs/PROJECT-STATUS.md` - Current state, trajectory, next actions
- `docs/brownfield-architecture.md` - System architecture
- `.bmad-core/core-config.yaml` - Project configuration
- `docs/issues/` - Active issues (6 currently)
- `docs/issues/archived/` - Completed work

---

## 🎯 Current Status (Quick Reference)

**Top Priority:** Mailing list lead magnet feature (epic creation pending)

**Active Issues:** 6 total
- 1 new feature (mailing list)
- 1 cleanup task (remove Posts/Tags)
- 2 API endpoints (quick wins)
- 2 in-progress features (Insights polish)

**Last Session:** 2026-01-05 - Issue triage, mailing list scoping, cleanup investigation

---

## 💬 User Interaction Pattern

**User prefers:**
- ✅ Numbered options for decisions
- ✅ Clear next steps
- ✅ Careful, tested approaches (especially for production)
- ✅ Comprehensive documentation
- ✅ BMad Method with specialized agents

**User constraints:**
- 100% test coverage required
- Multi-stage testing (local → staging → production)
- Single-user system limitation
- Serverless deployment (Vapor/Lambda)

---

## 🚀 Agent Activation Flow

### **Orchestrator Activation:**
```
1. User runs: /BMad:agents:bmad-orchestrator
2. Orchestrator reads its own agent definition
3. Orchestrator reads .bmad-core/core-config.yaml
4. Orchestrator reads .bmad-core/SESSION-STARTUP.md (THIS FILE)
5. Orchestrator reads docs/PROJECT-STATUS.md
6. Orchestrator greets user with summary of where they left off
7. Orchestrator offers 3 recommended paths forward
8. User chooses direction
9. Orchestrator loads relevant context/transforms to specialist
10. Work begins
```

### **Specialized Agent Activation (PM, Dev, QA, etc.):**
```
1. User runs: /BMad:agents:pm (or via *agent pm)
2. Agent reads its own agent definition
3. Agent reads .bmad-core/core-config.yaml
4. Agent reads docs/PROJECT-STATUS.md (for project context)
5. Agent greets user and shows available commands
6. Work begins with full project context
```

### **How This Works:**
- All agent definitions have been updated with activation instructions
- Steps 3.5 and 3.6 load session context files (if they exist)
- Agents check for file existence before loading (graceful if missing)
- PROJECT-STATUS.md gives all agents awareness of project trajectory

---

## 📝 End of Session Protocol

**Before ending session:**

1. Update `docs/PROJECT-STATUS.md`:
   - Update "Last Updated" date
   - Update "Recent Work Summary"
   - Update "Recommended Next Session Actions"
   - Add any new insights to relevant sections

2. Ensure all work is documented:
   - Issues created/updated
   - Stories completed/advanced
   - Decisions captured

3. Commit any documentation updates

---

**Remember: The user relies on PROJECT-STATUS.md to know where they left off. Keep it current!**
