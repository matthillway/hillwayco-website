# Hillway SEO Roadmap
## Target: Rank #1 for Property Management Sheffield/Doncaster

---

## Current Position

**Good news:** You're already appearing in Doncaster searches (3rd result for "commercial property management doncaster")

**Competitors ahead of you:**
| Location | Top Competitors |
|----------|----------------|
| Sheffield | LSH, BNP Paribas, Knight Frank, Colliers, Eddisons, Renshaw, MJB |
| Doncaster | Barnsdales, PPH Commercial |

---

## Critical Issues (Fix This Week)

### 1. PENALTY RISK: Hidden SEO Content
**Location:** `index.html` lines 695-710
```html
<section class="seo-content" style="position: absolute; left: -9999px...">
```
**Problem:** Google can detect and penalize hidden keyword-stuffed content
**Action:** Remove entirely - this is black-hat SEO

### 2. H1 Tag Has No Keywords
**Current:** `<h1>HILLWAY</h1>`
**Recommended:** `<h1>Commercial Property Management Sheffield & Doncaster</h1>`

### 3. Title Tag Too Long (148 chars)
**Current:** "Hillway Commercial Property and Asset Management | Sheffield Doncaster Yorkshire Manchester | PropTech AI & Automation | Commercial Property Consultants"
**Recommended:** "Commercial Property Management Sheffield & Doncaster | Hillway" (58 chars)

### 4. Meta Description Too Long (302 chars)
**Recommended:** "Leading commercial property management in Sheffield & Doncaster. RICS-regulated service charge consultancy, asset management & PropTech solutions across Yorkshire." (158 chars)

---

## Location Pages (Critical Gap)

**Your competitors have dedicated location pages. You don't.**

### Create These Pages:

#### 1. `/property-management-sheffield.html`
- **Title:** "Property Management Sheffield | Commercial Property Consultants"
- **H1:** "Commercial Property Management in Sheffield"
- **Content:** 800+ words covering:
  - Sheffield office details (Cubo, 38 Carver Street)
  - Services specific to Sheffield market
  - Local client testimonials
  - Sheffield commercial property market insights
  - Areas covered: Sheffield, Rotherham, Barnsley, Chesterfield
- **Schema:** LocalBusiness for Sheffield office

#### 2. `/property-management-doncaster.html`
- **Title:** "Property Management Doncaster | Commercial Property Consultants"
- **H1:** "Commercial Property Management in Doncaster"
- **Content:** 800+ words covering:
  - Doncaster office details (David House, Bawtry)
  - Services in Doncaster area
  - Local testimonials
  - Areas covered: Doncaster, Bawtry, Retford, Worksop

#### 3. `/service-charge-consultancy.html`
- **Title:** "Service Charge Consultancy Sheffield & Doncaster | RICS Regulated"
- **H1:** "Service Charge Consultancy"
- **Content:** Detailed RICS-compliant service charge expertise

#### 4. `/asset-management-yorkshire.html`
- **Title:** "Asset Management Yorkshire | Commercial Property"
- **H1:** "Commercial Asset Management in Yorkshire"

---

## Content Gaps

### Missing Exact Phrase Matches
These exact phrases appear **0 times** on your site:
- "commercial property management sheffield"
- "commercial property management doncaster"
- "property management sheffield"
- "property management doncaster"

**Add naturally in content:**
> "As leading commercial property management consultants in Sheffield, we provide..."
> "Our property management services in Doncaster cover..."

### Underrepresented Keywords
| Keyword | Current Count | Target |
|---------|---------------|--------|
| service charge consultancy | 3 | 8-10 |
| commercial property consultants | 2 | 6-8 |
| chartered surveyor sheffield | 0 | 4-5 |

---

## Schema Markup Improvements

### Current: Good foundation
- ProfessionalService schema with both offices
- Area served defined

### Missing:
1. **LocalBusiness** for each office separately
2. **Service** schema for each service line
3. **Review/AggregateRating** schema (you have testimonials but no schema)
4. **FAQ** schema
5. **Breadcrumb** schema

### Add LocalBusiness Schema:
```json
{
  "@type": "LocalBusiness",
  "name": "Hillway Property Consultants Sheffield",
  "image": "https://www.hillwayco.uk/images/logo.png",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Cubo, 38 Carver Street",
    "addressLocality": "Sheffield",
    "addressRegion": "South Yorkshire",
    "postalCode": "S1 4FS",
    "addressCountry": "GB"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 53.3811,
    "longitude": -1.4701
  },
  "telephone": "+44-333-404-0861",
  "priceRange": "££",
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
    "opens": "09:00",
    "closes": "17:30"
  }
}
```

---

## Google Business Profile (Most Important)

**83% of property management companies have verified GBP - highest of any industry**
**42% of local searches click the Map Pack (top 3 with pins)**

### Actions:
1. Verify you have Google Business Profile for BOTH offices
2. Ensure NAP matches website exactly
3. Add all services as "Products"
4. Upload photos of offices, team, properties managed
5. Respond to all reviews
6. Post weekly updates (news, case studies)
7. Add Q&A section

### GBP Checklist:
- [ ] Sheffield office verified
- [ ] Doncaster office verified
- [ ] All services listed
- [ ] 10+ photos uploaded
- [ ] Business description includes keywords
- [ ] Posts published weekly
- [ ] Reviews requested from clients

---

## Backlink Strategy

**Backlinks are Google's strongest ranking factor**

### Current Authority Sources:
- RICS registration (link from RICS directory?)
- Client relationships

### Target Backlinks:
| Source | Action |
|--------|--------|
| RICS Directory | Ensure profile links to website |
| Sheffield Chamber | Join and get listed |
| Doncaster Chamber | Join and get listed |
| Yorkshire Post | Press releases / articles |
| Local business directories | Submit to Yell, Thomson Local, etc. |
| Property industry publications | Guest articles on PropTech |
| Client websites | Request testimonial links |
| University of Sheffield | PropTech partnership content |

### Quick Wins:
1. Submit to 20 UK business directories
2. Get listed on property industry associations
3. Request links from satisfied clients
4. Write guest posts for property blogs

---

## Technical SEO

### Fix:
- [ ] Standardize phone format: "+44 333 404 0861" everywhere
- [ ] Add breadcrumb schema to all pages
- [ ] Ensure all pages have unique meta descriptions
- [ ] Add internal links from homepage to new location pages

### Monitor:
- Set up Google Search Console
- Track rankings for target keywords
- Monitor crawl errors
- Submit sitemap after changes

---

## Content Calendar

### Month 1: Foundation
- Week 1: Fix critical issues (hidden content, H1, title, meta)
- Week 2: Create Sheffield location page
- Week 3: Create Doncaster location page
- Week 4: Create service charge consultancy page

### Month 2: Authority Building
- Week 1: Create asset management page
- Week 2: Add case studies (anonymized if needed)
- Week 3: Add FAQ section with schema
- Week 4: Optimize Google Business Profiles

### Month 3: Link Building
- Week 1: Submit to 20 directories
- Week 2: Reach out to clients for testimonial links
- Week 3: Write guest post for property publication
- Week 4: Press release on PropTech innovation

### Ongoing:
- Weekly Google Business Profile posts
- Monthly blog posts on property management topics
- Quarterly review and update of location pages

---

## Competitor Analysis Summary

### What Renshaw Does Well (ranks for "property management sheffield"):
- Dedicated location page: `/property-management-in-sheffield/`
- Clear H1: "Property Management In Sheffield"
- 800+ words of content
- LocalBusiness schema
- RICS trust signals

### What Barnsdales Does Well (ranks for Doncaster):
- Quantifiable stats: "over 2,000 tenancies in over 500 properties"
- ISO 9001 certification displayed
- Team member contacts with direct phone numbers
- Long-form comprehensive service pages

### Apply These Tactics:
1. Create dedicated location pages (like Renshaw)
2. Add quantifiable stats: "75+ clients", "4.75M sq ft managed"
3. Display RICS badge prominently
4. Add team member contact details
5. Create comprehensive 800+ word service pages

---

## Success Metrics

### Track These Rankings:
| Keyword | Current | Target (6 months) |
|---------|---------|-------------------|
| property management sheffield | Not in top 20 | Top 10 |
| property management doncaster | ~3 | Top 3 |
| commercial property management sheffield | Not ranking | Top 10 |
| commercial property management doncaster | ~3 | Top 3 |
| service charge consultancy yorkshire | Unknown | Top 5 |

### Timeline Expectations:
- Google Business Profile optimization: Results in 2-4 weeks
- On-page fixes (title, H1, meta): Results in 4-8 weeks
- New location pages: Results in 3-6 months
- Link building: Results in 6-12 months

---

## Priority Actions Summary

### This Week:
1. ⚠️ Remove hidden SEO content (penalty risk)
2. Rewrite H1, title tag, meta description
3. Verify Google Business Profiles for both offices

### This Month:
4. Create Sheffield location page
5. Create Doncaster location page
6. Add LocalBusiness schema for each office
7. Update sitemap with new pages

### This Quarter:
8. Create service pages
9. Build 20+ directory backlinks
10. Add case studies and FAQs
11. Implement review schema

---

Generated by Claude Code - Dec 2024
