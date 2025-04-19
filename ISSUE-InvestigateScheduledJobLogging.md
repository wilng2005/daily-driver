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
1. **Deploy to Staging/Production**
    - Merge and deploy the branch to ensure new logs appear in AWS CloudWatch via Vapor.
2. **Verify Logging**
    - Monitor the Vapor dashboard and CloudWatch for log entries:
      - `[job_name] Job started`
      - `[job_name] Job completed successfully`
      - `[job_name] Job failed: ...`
3. **Review and Iterate**
    - Adjust log verbosity or add additional context as needed.
    - Consider adding automated tests to verify logging if desired.
4. **Document Learnings**
    - Update project documentation to reflect the new logging approach for scheduled jobs.

---

**Owner:** @wilng
**Branch:** `chore/investigate-scheduled-job-logging`
**Related PR:** _(to be created)_

If further improvements or monitoring needs are identified, please update this issue accordingly.
