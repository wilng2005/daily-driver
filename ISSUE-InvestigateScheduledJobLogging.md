# Investigation: Scheduled Job Logging for Vapor/CloudWatch Visibility

## Issue Summary
Currently, there is no clear evidence in production logs (Laravel Vapor/AWS CloudWatch) that scheduled jobs are running as expected. This makes it difficult to:
- Confirm job execution
- Troubleshoot job failures
- Audit job activity over time

## Approach
1. **Explicit Logging in All Scheduled Jobs**
    - Added `Log::info()` at the start and on successful completion of each scheduled job.
    - Added `Log::error()` in exception catch blocks to capture and log failures.
    - For callable jobs (e.g., `PruneStaleAttachments`), wrapped the call in a closure with logging.
    - All logs are clearly prefixed with the job name for easy filtering in AWS CloudWatch and the Vapor dashboard.
2. **Branch Isolation**
    - All changes are implemented in the `chore/investigate-scheduled-job-logging` branch for focused review and testing.

## Jobs Updated
- `schedule:daily` (DoDailySchedule)
- `journal_entry:send` (SendJournalEntry)
- `reacquisition:send` (SendReacquisitionMessages)
- `PruneStaleAttachments` (callable in Kernel)

## Next Steps
1. **Monitor Staging Logs**
    - The branch has been merged and deployed to staging via CI/CD.
    - Monitor the Vapor dashboard and AWS CloudWatch for log entries related to scheduled jobs:
      - `[job_name] Job started`
      - `[job_name] Job completed successfully`
      - `[job_name] Job failed: ...`
2. **Verify Log Presence and Quality**
    - Confirm that all updated jobs (`DoDailySchedule`, `SendJournalEntry`, `SendReacquisitionMessages`, `PruneStaleAttachments`) are producing the expected log entries.
    - Ensure logs are clear, correctly prefixed, and provide sufficient information for auditing and troubleshooting.
3. **Review and Iterate**
    - If logs are missing or unclear, identify which job(s) need further adjustment.
    - Update logging statements or add additional context as needed.
    - (Optional) Add or update automated tests to verify logging, following TDD practices.
4. **Document Learnings**
    - Once satisfied, update project documentation to describe the new logging approach for scheduled jobs.
    - Summarize any key findings or improvements for future reference.

---

**Owner:** @wilng
**Branch:** `chore/investigate-scheduled-job-logging`
**Related PR:** _(to be created)_

If further improvements or monitoring needs are identified, please update this issue accordingly.
