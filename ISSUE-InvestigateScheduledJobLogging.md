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
2. **Scheduler Trigger Diagnostic Log**
    - Added `Log::info('[Scheduler] schedule() method triggered');` as the first line of the `schedule()` method in `app/Console/Kernel.php`.
    - This log confirms whether the Laravel scheduler itself is being invoked by Vapor (via `php artisan schedule:run`).
    - If this log appears in CloudWatch, it means the scheduler is being triggered as expected. If not, investigate Vapor schedule configuration and deployment.
3. **Branch Isolation**
    - All changes are implemented in the `chore/investigate-scheduled-job-logging` branch for focused review and testing.

## Jobs Updated
- `schedule:daily` (DoDailySchedule)
- `journal_entry:send` (SendJournalEntry)
- `reacquisition:send` (SendReacquisitionMessages)
- `PruneStaleAttachments` (callable in Kernel)

## Next Steps
1. **Deploy to Staging/Production**
    - Merge and deploy the branch to ensure new logs (including the scheduler trigger log) appear in AWS CloudWatch via Vapor.
2. **Verify Scheduler Invocation**
    - In CloudWatch (`/aws/lambda/vapor-daily-driver-staging`), search for the log entry:
      - `[Scheduler] schedule() method triggered`
    - If present, this confirms the scheduler is running. If absent, further investigation of Vapor's schedule configuration is required.
3. **Monitor and Verify Job Logging**
    - Monitor the Vapor dashboard and CloudWatch for log entries related to scheduled jobs:
      - `[job_name] Job started`
      - `[job_name] Job completed successfully`
      - `[job_name] Job failed: ...`
    - Confirm that all updated jobs (`DoDailySchedule`, `SendJournalEntry`, `SendReacquisitionMessages`, `PruneStaleAttachments`) are producing the expected log entries.
    - Ensure logs are clear, correctly prefixed, and provide sufficient information for auditing and troubleshooting.
4. **Review and Iterate**
    - If logs are missing or unclear, identify which job(s) need further adjustment.
    - Update logging statements or add additional context as needed.
    - (Optional) Add or update automated tests to verify logging, following TDD practices.
5. **Document Learnings**
    - Once satisfied, update project documentation to describe the new logging approach for scheduled jobs.
    - Summarize any key findings or improvements for future reference.

---

## Manually Triggering the Laravel Scheduler in Vapor

If you need to verify that the scheduler and its logs are working in your Vapor environment, you can manually trigger the scheduler using either the Vapor CLI or the Vapor dashboard:

### Using the Vapor CLI
1. Ensure you have the Vapor CLI installed and are authenticated (`vapor login`).
2. Run the following command to execute the scheduler in your staging environment:
   ```
   vapor ssh staging -- php artisan schedule:run
   ```
   - This will invoke the scheduler as if it were triggered by AWS EventBridge.
3. Check AWS CloudWatch (`/aws/lambda/vapor-daily-driver-staging`) for the log entry:
   - `[Scheduler] schedule() method triggered`

### Using the Vapor Dashboard
1. Go to your project in the [Vapor Dashboard](https://vapor.laravel.com/).
2. Select your environment (e.g., staging).
3. Use the "Run Command" feature to execute:
   ```
   php artisan schedule:run
   ```
4. Check AWS CloudWatch for the log entry as above.

### Notes
- If you see the log entry, the scheduler is functioning and your jobs should be processed as scheduled.
- If you do not see the log, review your Vapor schedule configuration and deployment status.
>>>>>>> chore/investigate-scheduled-job-logging

---

**Owner:** @wilng
**Branch:** `chore/investigate-scheduled-job-logging`
**Related PR:** _(to be created)_

If further improvements or monitoring needs are identified, please update this issue accordingly.
