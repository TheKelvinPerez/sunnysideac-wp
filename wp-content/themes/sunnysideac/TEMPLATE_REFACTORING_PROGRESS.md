# Template Refactoring Progress Tracker

## Project Overview
**Goal:** Restructure theme according to modern WordPress best practices
**Strategy:** Clean root, organized subdirectories, proper template hierarchy
**Current Status:** 🔄 REVISED PLAN - Modern WordPress Standards
**Branch:** feature/template-refactoring-phase-1

---

## 🎯 New Strategy - Modern WordPress Standards

### Final Target Structure:
```
theme/
├── 404.php
├── archive.php
├── front-page.php
├── functions.php
├── home.php
├── index.php
├── page.php
├── search.php
├── single.php
├── style.css
│
├── page-templates/
│   ├── about.php (Template Name: About Page)
│   ├── contact.php (Template Name: Contact Page)
│   └── etc...
│
├── templates/
│   └── cpt/
│       ├── single-brand.php
│       ├── archive-brand.php
│       └── etc...
│
├── template-parts/
│   ├── header/
│   ├── footer/
│   ├── hero/
│   ├── components/
│   └── cards/
│
├── inc/ (already well organized)
├── assets/ (already well organized)
└── Other build files (already well organized)
```

---

## Phase 1: Create Modern Directory Structure
**Status:** 🔄 In Progress
**Risk Level:** Low
**Estimated Time:** 15 minutes

### Actions:
- [ ] Create `templates/cpt/` directory
- [ ] Create subdirectories in `template-parts/`
- [ ] Set up template loader filter for CPT templates

### Directories to Create:
- [ ] `templates/cpt/`
- [ ] `template-parts/header/`
- [ ] `template-parts/footer/`
- [ ] `template-parts/hero/`
- [ ] `template-parts/components/`
- [ ] `template-parts/cards/`

**Completion Date:** _______________

---

## Phase 2: Move Custom Post Type Templates
**Status:** ✅ COMPLETED
**Risk Level:** Medium
**Completed Time:** 15 minutes

### Templates to Move:
- [x] `single-brand.php` → `templates/cpt/`
- [x] `single-city.php` → `templates/cpt/`
- [x] `single-review.php` → `templates/cpt/`
- [x] `single-service.php` → `templates/cpt/`
- [x] `single-service-city.php` → `templates/cpt/`
- [x] `archive-brand.php` → `templates/cpt/`
- [x] `archive-cities.php` → `templates/cpt/`
- [x] `archive-review.php` → `templates/cpt/`
- [x] `archive-service.php` → `templates/cpt/`

### Actions:
- [x] Create template loader filter (`inc/core/cpt-template-loader.php`)
- [x] Move all CPT templates (9 files moved)
- [x] Test CPT functionality

### Result:
- Root directory reduced from 32 → 23 PHP files
- All CPT templates now in organized `templates/cpt/` directory
- Template loader automatically handles routing

**Completion Date:** 2025-12-04

---

## Phase 3: Convert Page Templates
**Status:** ⏳ Not Started
**Risk Level:** Medium
**Estimated Time:** 60 minutes

### Page Templates to Convert:
- [ ] `page-about.php` → `page-templates/about.php` (add Template Name header)
- [ ] `page-contact.php` → `page-templates/contact.php`
- [ ] `page-careers.php` → `page-templates/careers.php`
- [ ] `page-financing.php` → `page-templates/financing.php`
- [ ] `page-projects.php` → `page-templates/projects.php`
- [ ] `page-areas.php` → `page-templates/areas.php`
- [ ] `page-customer-portal.php` → `page-templates/customer-portal.php`
- [ ] `page-blog.php` → `page-templates/blog.php`
- [ ] `page-daikin-product.php` → `page-templates/daikin-product.php`
- [ ] `page-maintenance-plan.php` → `page-templates/maintenance-plan.php`
- [ ] `page-privacy-policy.php` → `page-templates/privacy-policy.php`
- [ ] `page-terms-conditions.php` → `page-templates/terms-conditions.php`
- [ ] `page-warranty.php` → `page-templates/warranty.php`

### Actions:
- [ ] Add Template Name headers to each file
- [ ] Move to `page-templates/`
- [ ] Assign templates in WordPress admin
- [ ] Test all pages

**Completion Date:** _______________

---

## Phase 4: Organize Template Parts
**Status:** ⏳ Not Started
**Risk Level:** Low
**Estimated Time:** 45 minutes

### Template Parts to Organize:
- [ ] Move header parts to `template-parts/header/`
- [ ] Move footer parts to `template-parts/footer/`
- [ ] Move hero sections to `template-parts/hero/`
- [ ] Move cards to `template-parts/cards/`
- [ ] Move general components to `template-parts/components/`
- [ ] Update all `get_template_part()` calls

### Testing:
- [ ] All includes work correctly
- [ ] No broken template parts
- [ ] Update function calls

**Completion Date:** _______________

---

## Phase 5: Final Cleanup & Testing
**Status:** ⏳ Not Started
**Risk Level:** Low
**Estimated Time:** 30 minutes

### Actions:
- [ ] Verify clean root directory
- [ ] Test complete site functionality
- [ ] Update documentation
- [ ] Commit final changes

### Root Directory Should Contain:
- 404.php, archive.php, front-page.php, functions.php, home.php, index.php, page.php, search.php, single.php, style.css

**Completion Date:** _______________

---

## Phase 3: Move Core Page Templates
**Status:** ⏳ Not Started
**Risk Level:** Medium
**Estimated Time:** 60 minutes

### Templates to Move:
- [ ] `page-about.php`
- [ ] `page-contact.php`
- [ ] `page-careers.php`
- [ ] `page-financing.php`
- [ ] `page-projects.php`
- [ ] `page-areas.php`

### Testing Checklist:
- [ ] Contact form works
- [ ] No broken layouts
- [ ] All images load properly
- [ ] Navigation works correctly

**Completion Date:** _______________

---

## Phase 4: Move Complex Pages
**Status:** ⏳ Not Started
**Risk Level:** Medium-High
**Estimated Time:** 90 minutes

### Templates to Move:
- [ ] `page-customer-portal.php`
- [ ] `page-blog.php`
- [ ] `page-daikin-product.php`

### Testing Checklist:
- [ ] Customer portal functionality works
- [ ] Blog pagination works
- [ ] Product features display correctly
- [ ] All interactive elements work

**Completion Date:** _______________

---

## Phase 5: Move Custom Post Type Templates
**Status:** ⏳ Not Started
**Risk Level:** High
**Estimated Time:** 120 minutes

### Single Templates to Move:
- [ ] `single-city.php`
- [ ] `single-service.php`
- [ ] `single-brand.php`
- [ ] `single-review.php`
- [ ] `single-service-city.php`

### Archive Templates to Move:
- [ ] `archive-city.php`
- [ ] `archive-service.php`
- [ ] `archive-brand.php`
- [ ] `archive-review.php`
- [ ] `archive-cities.php`

### Testing Checklist:
- [ ] All city pages work
- [ ] All service pages work
- [ ] All brand pages work
- [ ] Review system works
- [ ] All archive pages work
- [ ] CPT relationships intact

**Completion Date:** _______________

---

## Phase 6: Move Core WordPress Templates
**Status:** ⏳ Not Started
**Risk Level:** Medium
**Estimated Time:** 60 minutes

### Templates to Move:
- [ ] `front-page.php`
- [ ] `home.php`
- [ ] `single.php`
- [ ] `page.php`
- [ ] `archive.php`

### Destination: `templates/core/`

### Testing Checklist:
- [ ] Homepage displays correctly
- [ ] Blog listing works
- [ ] Search results show
- [ ] 404 page works
- [ ] WordPress hierarchy preserved

**Completion Date:** _______________

---

## Phase 7: Final Cleanup
**Status:** ⏳ Not Started
**Risk Level:** Low
**Estimated Time:** 90 minutes

### Template Parts Organization:
- [ ] Create `template-parts/components/`
- [ ] Create `template-parts/cards/`
- [ ] Create `template-parts/sections/`
- [ ] Move files to appropriate subdirectories
- [ ] Update all `get_template_part()` calls

### Final Testing:
- [ ] Complete site audit
- [ ] All URLs work
- [ ] No PHP errors
- [ ] Performance maintained
- [ ] Client approval

**Completion Date:** _______________

---

## Final Structure Goal:
```
Theme Root (4-6 files only):
├── index.php
├── functions.php
├── style.css
├── header.php (optional)
├── footer.php (optional)
└── templates/
    ├── single/
    ├── archive/
    ├── page/
    └── core/
```

---

## Issues Encountered:
*Document any problems, solutions, or deviations from plan*

### Phase 1 Issues:
-

### Phase 2 Issues:
-

### Phase 3 Issues:
-

---

## Notes:
*Additional notes, decisions, or observations*

---

## Rollback History:
*Document any rollbacks and reasons*

---

## Final Sign-off:
**Developer:** ________________________
**Date:** ____________________________
**QA Approved:** _____________________
**Final Cleanup Date:** ______________