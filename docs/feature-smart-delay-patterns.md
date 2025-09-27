# Feature: Smart Delay Pattern Recognition

**Problem Statement:**
Daily inbox processing consumes 80+ minutes due to decision fatigue on delay timeframes. Each item requires contextual judgment that AI can't replicate, but most delays follow personal patterns.

## Core Feature
When delaying an item, system shows: **"Delay for [X days] (same as last time)" with clear modify option**

## User Experience
1. User encounters item to delay
2. System displays: `⏰ Delay for 14 days (same as last) [Accept] [Change]`
3. One-click accept OR quick modify without starting from scratch
4. System learns and refines patterns over time

## Key Benefits
- Reduces decision time from ~1 minute to ~5 seconds per item
- Eliminates "how long?" cognitive load
- Still allows contextual overrides when needed
- Builds personal delay intelligence over time

## Implementation Requirements
- Track last delay duration used
- Prominent display of suggested timeframe
- One-click accept mechanism
- Quick modify path (dropdown/input)
- Clear visual distinction between "suggested" and "custom"

## Potential Enhancements
- Track patterns by item type/category
- Show most common delay periods (1 week, 2 weeks, 1 month)
- "Quick delay" buttons for your top 3 patterns

## Success Metric
Reduce daily inbox processing time from 80 minutes to <20 minutes.

## Discovery Notes
- Most inbox items are "somewhat valuable" but not immediately actionable
- Current delay mechanism creates daily decision bottleneck
- Pattern-based shortcuts could eliminate most cognitive overhead while preserving flexibility
- User already naturally filters out truly worthless items and identifies clearly important items