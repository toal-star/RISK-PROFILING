# BenefitGuard NYC

AI-powered SNAP retailer fraud risk profiling for New York City.

## Live URL
http://13.220.32.186/retailers

Judge credentials: judge@benefitguard.nyc / JudgeAccess2026

## What it does
Scores all 7,171 authorized NYC SNAP retailers using 5 public-data signals grounded in 30 years of USDA research. Click any store for an AI-generated risk explanation powered by Claude.

## Risk Signals
1. Store Type (22pts)
2. Address Churn (28pts)
3. Confirmed Disqualifications in ZIP (18pts)
4. ZIP Poverty Rate (17pts)
5. Median Household Income (15pts)

## Results
- 113 High-risk stores (1.6%) out of 7,171 scored
- 104 confirmed disqualified stores used for validation
- Max score 91/100

## Stack
Laravel 13, SQLite, Tailwind CSS, Anthropic API, AWS EC2
