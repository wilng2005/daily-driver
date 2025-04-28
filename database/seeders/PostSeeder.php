<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::truncate(); // Clear all posts

        Post::create([
            'title' => 'What is Burnout? Understanding and Overcoming It',
            'slug' => 'what-is-burnout',
            'content' => 'Burnout is a state of emotional, physical, and mental exhaustion caused by excessive and prolonged stress. Learn how to recognize, prevent, and recover from burnout in both work and life.',
            'status' => 'published',
            'source' => 'manual',
            'ai_prompt' => null,
            'published_at' => now()->subDays(10),
        ]);
        Post::create([
            'title' => 'Productivity Hacks for Busy Professionals',
            'slug' => 'productivity-hacks',
            'content' => 'Discover practical productivity tips and strategies to help you manage your time, focus your energy, and achieve more with less stress.',
            'status' => 'published',
            'source' => 'ai',
            'ai_prompt' => 'Write an article about productivity hacks for busy professionals.',
            'published_at' => now()->subDays(7),
        ]);
        Post::create([
            'title' => 'How to Recover from a Personal Crisis',
            'slug' => 'recover-from-crisis',
            'content' => 'Personal crises can be overwhelming, but with the right mindset and support, you can rebuild and thrive. Here are steps and resources to help you recover.',
            'status' => 'published',
            'source' => 'manual',
            'ai_prompt' => null,
            'published_at' => now()->subDays(3),
        ]);
        Post::create([
            'title' => 'The Science of Stress Management',
            'slug' => 'science-of-stress-management',
            'content' => 'Explore evidence-based strategies for managing stress and building resilience in your daily life.',
            'status' => 'draft',
            'source' => 'ai',
            'ai_prompt' => 'Write an article about the science of stress management.',
            'published_at' => null,
        ]);

        Post::create([
            'title' => "Why It’s So Hard to Find a Good Career or Performance Coach – and How to Choose the Right One",
            'slug' => 'why-its-so-hard-to-find-a-good-career-or-performance-coach',
            'content' => <<<EOT
# Why It’s So Hard to Find a Good Career or Performance Coach – and How to Choose the Right One

## Introduction

In the business world, even luminaries like Google’s Eric Schmidt and Microsoft’s Bill Gates have extolled the value of having a coach. But what exactly is a *career* or *performance* coach, and how do these roles differ from mentors, therapists, or consultants?

According to the International Coaching Federation (ICF), coaching is defined as:

> *“Partnering with clients in a thought-provoking and creative process that inspires them to maximize their personal and professional potential.”* [ICF, 2023]

In practice, a career or performance coach works with individuals to help them define and achieve specific goals—e.g., making a career transition, improving leadership skills, or enhancing work performance.

- **Career coaching** centers on career trajectory, job search strategy, and professional growth.
- **Performance coaching** (often called executive or leadership coaching) focuses on effectiveness, decision-making, and peak productivity.

Unlike a mentor (advice-giver), a consultant (problem-solver), or a therapist (healer of past trauma), a coach empowers clients to unlock their own solutions through structured guidance and reflective conversation.

Despite coaching’s benefits, many entrepreneurs and tech leaders report that it’s hard to find a coach that truly fits. Here’s why—and what to do about it.

## Why It’s Hard to Find a Good Coach

### 1. **Market Saturation & Lack of Regulation**

- The global coaching industry now exceeds **$4.5 billion USD** in annual revenue.
- Over **100,000 coaches** are in practice, yet **less than half** hold accredited certifications. [ICF, 2023]
- Anyone can call themselves a coach. There is no universal license or regulatory body.

> ⚠️ Result: Massive variance in quality, with limited ways to verify credentials at a glance.

### 2. **Mismatch in Coaching Style and Client Needs**

- Coaching is highly relational—success depends on the **“working alliance.”**
- Research shows **coach-client fit** (in values, communication style, personality) predicts success. [Grant, 2014]
- A great coach for one founder may be a poor fit for another, depending on whether you want direct feedback, introspection, structured action plans, etc.

### 3. **Marketing vs. Substance**

- Coaches often invest more in **online polish** than ongoing training.
- Flashy websites and curated testimonials can mask weak fundamentals.
- Many talented coaches operate by referral and may not have strong online visibility.

> 🧩 Information asymmetry means clients struggle to distinguish genuine expertise from sales pitch.

### 4. **Cost and Accessibility**

- Rates range from **$200 to $800+ per hour** for executive coaching. [Next Level Coaching, 2024]
- Coaching has historically catered to executives at large firms—not early-stage founders or indie professionals.
- Some platforms offer affordable group or digital coaching, but quality and personalization vary.

### 5. **Client Readiness and Clarity**

- Coaching is most effective when you’re:
  - Clear on your goals
  - Willing to do the work
  - Open to reflection and feedback
- Many seekers have only vague needs (“I want change”) but aren’t sure what for.

> 🧠 Coaching isn’t therapy or advice—it’s a partnership. You get more when you bring more.

## Evidence-Based Strategies for Finding the Right Coach

### 1. **Clarify Your Goals and Readiness**

- Define your primary coaching goal: career clarity, founder support, leadership growth?
- Ask yourself:
  - Am I ready to reflect and be challenged?
  - What does success look like in 6 months?

> 🔍 Self-awareness leads to better coach alignment.

### 2. **Verify Credentials and Experience**

Look for:
- Certifications from **ICF**, **EMCC**, or university coaching programs
- Relevant experience (e.g., coaching startup founders, product leaders, etc.)
- Methodologies grounded in research (e.g., GROW model, 360° feedback tools)

Use the [ICF Credentialed Coach Finder](https://coachingfederation.org/find-a-coach).

### 3. **Do Discovery Sessions With Multiple Coaches**

- Treat this like hiring a key team member.
- Sample 2–3 coaches before committing.
- Evaluate:
  - Rapport and “chemistry”
  - Clarity of their process
  - How they listen and respond

> ✅ A good coach will listen more than pitch.

### 4. **Ask Smart Questions**

Suggested Qs:
- *“What’s your coaching process?”*
- *“What types of clients do you work best with?”*
- *“How do you measure success or track progress?”*
- *“What’s your fee structure and commitment model?”*

> 🛠️ Great coaches will explain their framework, not sell a dream.

### 5. **Use Vetted Platforms and Trusted Referrals**

- Platforms: BetterUp, CoachHub (B2B); Noomii, ICF directory (B2C)
- Ask founder friends, VCs, or mentors for personal recommendations.
- Tap into communities (Slack groups, mastermind circles, alumni networks).

> 💬 “A referral is a transfer of trust.” – Arete Coach

## Conclusion

Coaching, when done well, can be a powerful **strategic asset** for entrepreneurs and tech leaders.

But the search isn’t easy. Why?

- It’s unregulated.
- Quality is inconsistent.
- Fit is personal.
- Marketing can mislead.
- And you might not be clear on what you want.

But if you **do the prep**, ask the right questions, and interview multiple candidates, you’ll raise your odds of finding someone who’s not just good—but right for *you*.

> 💡 “Everyone needs a coach.” – Eric Schmidt, former Google CEO

Make your coaching journey a **deliberate investment**, not a hopeful guess.

## References

- International Coaching Federation (ICF). (2023). *Professional Coaching Global Study*.
- Khelifi, Y., & Cozma, I. (2023). *The Right Time to Get a Career Coach*. HBR.
- Grant, A. M. (2014). *The Efficacy of Executive Coaching in Times of Organizational Change*. Journal of Change Management.
- Next Level Coaching. (2024). *How Much Does Executive Coaching Cost?*
- Sorensen, S. (2024). *Referrals: Coaching’s Secret to Growth*. Arete Coach.
- Lashinsky, A. (2009). *Eric Schmidt: Hire a Coach*. Fortune.
- MetrixGlobal. (2001). *Executive Coaching ROI Study*.
EOT,
            'status' => 'published',
            'source' => 'manual',
            'ai_prompt' => null,
            'published_at' => now(),
        ]);
    }
}
