# ISSUE: Ensure Google Analytics Tracks All Cal.com Booking Link Clicks

## Background
Currently, outbound booking links to cal.com (for coaching slots) are not reliably tracked as events in Google Analytics, despite using Google Tag Manager and standard anchor tags. This results in incomplete analytics regarding user engagement and conversion funnel activity.

## Problem
- Google Analytics does not consistently log outbound link clicks to cal.com, likely due to the browser navigating away before the event can be sent.
- No custom event tracking is currently implemented for these booking links.
- Reliable tracking is required for all outbound booking attempts, with future plans to also track successful bookings.

## Solution Plan
Implement an intermediate redirect tracking page to ensure all outbound cal.com booking clicks are logged in GA before forwarding the user:

1. **Create a new Laravel route** (`/redirect-to-cal`) that accepts a `target` query parameter.
2. **Build a minimal Blade view** (`redirect-to-cal.blade.php`) that:
    - Fires a Google Analytics event via Google Tag Manager (`dataLayer.push({event: 'calcom_booking_click', ...})`).
    - Shows a brief “Redirecting...” message.
    - Redirects the user to the cal.com booking URL after a short delay (e.g., 1 second).
3. **Update all booking links** to point to `/redirect-to-cal?target=...` instead of directly to cal.com.
4. **Validate the `target` parameter** on the backend to prevent open redirect vulnerabilities (only allow cal.com URLs).
5. **Test event tracking** in Google Analytics Realtime and GTM Preview mode to confirm reliability.

## Acceptance Criteria
- [ ] All booking link clicks to cal.com are tracked as events in Google Analytics.
- [ ] The redirect page is visually minimal and user-friendly.
- [ ] Users are redirected to their intended cal.com booking page within 1 second.
- [ ] The solution prevents open redirect exploits.
- [ ] Documentation and README are updated to describe the new tracking flow and coverage policy.

## References
- [Google Tag Manager Outbound Link Tracking](https://support.google.com/tagmanager/answer/6106960?hl=en)
- [Cal.com Documentation](https://docs.cal.com/)
