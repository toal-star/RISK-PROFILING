# ROADMAP — Benefits Trafficking SNAP

**Project:** AI-powered SNAP retailer fraud risk profiling tool
**Pilot geography:** New York (convenience stores + small/medium grocery)
**Deadline:** June 13
**Stack:** Laravel + MySQL on AWS EC2, Anthropic API for explanations

---

## Guiding principle

Cut features, never cut transparency. A small tool that works and honestly explains
its scoring beats a big tool that half-works. Every store's risk score must come with
a plain-English reason a non-technical user can understand.

---

## The riskiest thing, tackled first

The single biggest risk is **the data pipeline**: getting the New York retailer CSV
cleaned, loaded into MySQL, and joined to a list of confirmed disqualified stores for
validation. If this doesn't work, nothing downstream works — no scoring, no AI
explanations, no dashboard. Everything else is cosmetic by comparison.

So Milestone 1 is the data pipeline, and it gets attacked Day 1. If it's going to fail,
it needs to fail early while there's still time to adjust scope.

---

## Week 1 (May 31 – June 6): Make it work

### Milestone 1 — Data pipeline (Days 1–2) — RISKIEST, DO FIRST
**User-facing result:** Nothing visible yet, but the database is loaded and queryable.

- Download current NY authorized retailer CSV from the USDA ArcGIS hub
- Filter to convenience stores + small/medium grocery (drop supermarkets, farmers
  markets, superstores — near-zero trafficking risk)
- Clean the data: dedupe repeated authorization periods, standardize store types,
  parse zip codes
- Build a list of confirmed disqualified NY stores (Brooklyn + Buffalo press releases,
  SAM.gov, Final Agency Decisions) — this is the validation set
- Load both into MySQL tables

**GitHub issues to open:**
- `Download and store raw NY SNAP retailer CSV`
- `Write data cleaning script (dedupe, standardize store types, parse zips)`
- `Build confirmed-disqualified-store validation table`
- `Create MySQL schema for retailers + violations`

### Milestone 2 — Risk scoring engine (Days 3–4)
**User-facing result:** Every store in the database gets a numeric risk score 0–100.

- Score stores using available public signals: store type (heaviest weight),
  zip-code violation density, store-name independence (chain vs. independent),
  authorization churn (how often the store changed hands)
- Tune weights so known disqualified stores tend to score high (this is the
  validation step that answers "how do you know your flags are right?")

**GitHub issues to open:**
- `Implement store-type risk weighting`
- `Add zip-code violation-density scoring`
- `Validate scores against confirmed disqualified stores`

### Milestone 3 — Basic dashboard (Days 5–6)
**User-facing result:** User logs in and sees a ranked, searchable list of NY stores
sorted by risk score.

- Login screen (Laravel's built-in auth)
- Ranked table of stores with score, store type, location
- Filter by zip / borough; search by name

**GitHub issues to open:**
- `Add login/authentication`
- `Build ranked store dashboard`
- `Add filter and search`

---

## Week 2 (June 7 – June 13): Make it convincing

### Milestone 4 — AI explanations (Days 7–9) — THE "WOW" FEATURE
**User-facing result:** Clicking a store shows a plain-English paragraph explaining
*why* it scored the way it did.

- Wire Laravel to the Anthropic API
- For a selected store, send its data + score factors to the model and get back a
  clear, non-technical explanation
- Handle errors gracefully (API down, etc.)

**GitHub issues to open:**
- `Integrate Anthropic API into Laravel`
- `Generate per-store plain-English risk explanation`
- `Add error handling for API failures`

### Milestone 5 — Map + polish (Days 10–11)
**User-facing result:** A map view of flagged stores, cleaner styling.

- Plot stores on a map using their lat/long, colored by risk
- Visual polish on the dashboard

**GitHub issues to open:**
- `Add map view of stores by risk`
- `Style and polish dashboard`

### Milestone 6 — Presentation + deploy final (Days 12–13)
**User-facing result:** Live, deployed app + a presentation ready for judges.

- Final deploy to EC2, test end-to-end on the public URL
- Build the pitch: the problem, the stats (15% of redemptions = 95%+ of trafficking;
  NY = 6% of stores but 25% of violations), the live demo, the validation story,
  the "this is a pilot, here's how it scales" close
- Write README explaining the tool honestly

**GitHub issues to open:**
- `Final EC2 deploy and end-to-end test`
- `Write project README`
- `Prepare judge presentation`

---

## What might honestly NOT get finished

Being straight about this now so there's no panic in week two:

- **The socioeconomic overlay (census income, food-desert data) is a stretch goal.**
  It would sharpen scoring, but joining census data by tract is fiddly. If Milestones
  1–4 run long, this gets cut and mentioned to judges as a "next step," not built.
- **The map (Milestone 5) is the first thing to cut** if AI explanations take longer
  than planned. A ranked list with explanations is the core product; the map is a
  nice-to-have.
- **Multi-state scaling will not happen.** NY is the pilot, full stop. Trying to load
  all 250,000 national retailers in two weeks would sink the project.
- **The scoring model is a heuristic, not machine learning.** With no transaction-level
  data (it's legally confidential) and a two-week window, a transparent weighted-factor
  score is more honest and more defensible to judges than a black-box model anyway.

---

## Honest framing for judges

This tool does not claim to prove fraud. It generates *data-driven reasonable suspicion*
from public data — a ranked starting point for nonprofits, journalists, and state
agencies who lack access to closed federal systems. Federal tools exist but are internal
and inaccessible; this democratizes a first-pass screening layer. That's the pitch, and
it's true.
