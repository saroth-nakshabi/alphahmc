# AlphaHMC — Project Guide

> Map for future sessions. High-signal, not exhaustive. Verify `file:line` against current code before asserting — this drifts.

## Overview
Laravel 11 marketing + content site for **Alpha Health Group** (UAE healthcare). MySQL via local XAMPP; deployed to **cPanel (nexthealthos.com) with NO terminal** — all DB changes applied by hand in phpMyAdmin. Public front-end (Bootstrap 5 + custom CSS in Blade) plus an admin **dashboard** (ModernAdmin/AdminKit template, jQuery + Select2 + SweetAlert2 `Toast` + TinyMCE) for managing services, categories, brands, blogs, projects, an AI project planner, and more.

## ⚠️ Critical conventions (read before any change)
- **Always end a DB-touching task with a consolidated raw SQL block** (ALTER/UPDATE/CREATE, phpMyAdmin-ready, including data backfills). Production has no artisan/migrations/tinker. Skipping this → `SQLSTATE[42S22]` in prod. Applying to local XAMPP via tinker to verify is fine, but the SQL block is still mandatory.
- **Migrations sit "Pending" / host drift.** Columns are added to the DB directly via SQL, so `php artisan migrate` fails on already-existing columns (e.g. `main_categories.sort_order`). Make new migrations idempotent (`if (Schema::hasColumn(...)) return;`) and apply the column to local via `DB::statement` instead of a full migrate.
- **Host code has drifted from this repo.** The live host runs code not in git (e.g. a Strategies module — local `routes/web.php` has a stub `strategy.index` redirect). Before deploying *shared/global* files (`routes/web.php`, `dashboard/layout.blade.php`, `front/layout-2.blade.php`, composer files), get the live version and **merge — never overwrite blindly**.
- **Asset paths use `asset('public/...')`** — the public dir is nested at `/public/public/` on cPanel. Uploads live in `public/uploads/<type>/`.
- **Deploy zips:** don't auto-build. Build only when asked, using **PHP `ZipArchive` with forward-slash paths** (not PowerShell `Compress-Archive`); include all files changed since the last deployed zip. Always list changed file paths in task summaries.
- **TinyMCE/CKEditor sync:** call `tinymce.triggerSave()` (or sync `editor.getData()` → textarea) **before** `new FormData(form)`, or rich-text fields submit empty and `required` validation fails silently. Declare editor vars at module level.
- **Dead columns kept intentionally** (2026-06-13 audit): `service_groups.main_category_id`, `category_id`, `sliding_image`, `related_services`, `areaServed`, `serviceType` are unused — don't build against them; use the `category_service_group` pivot. **Trap:** `service_groups.content` is still ACTIVE even though `categories.content` was dropped from category forms — don't "clean up" by symmetry.

## Routing (`routes/web.php`)
- **Front-end (public):** lines ~67–165, no auth. Home `/` → `MainHomeController@index`. Most public pages are `MainHomeController` (services, categories, brands, about, blog/insights, projects, testimonials). Some legacy pages on `HomeController` (`/about`, `/contact`, `/blog`, `/how_alpha_work`). AI planner = `ProjectPlannerController` (`/plan-your-project*`). Search = `SearchController`. Sitemap = `SitemapController`.
- **Dashboard (auth):** large `middleware('auth')` group from ~line 169, mostly gated by Spatie `permission:*` middleware. Organized by feature with `// section` comments. Brands/Clients/eco use `Route::prefix('dashboard/...')` sub-groups.
- Many routes carry custom slugs (e.g. `/Alpha-Health-Group-Branches` = brands, `/about-alpha-health-group` = about) — grep the route **name** (`front.brands`), not the path.

## Feature map (dir / controller / model)
| Feature | Controller | Model(s) | Dashboard views | Front views |
|---|---|---|---|---|
| Services | `ServiceController` | `Service`, `ServiceImage`, `ServiceDocument` | `dashboard/services/` | `service.blade.php` |
| Sub-categories | `CategoryController` | `Category` | `dashboard/categories/` | `service_category.blade.php` |
| Main categories | `MainCategoryController` | `MainCategory` | (inline) | — |
| Service groups (packages) | `ServiceGroupController` | `ServiceGroup` | `dashboard/service_group/` | `service_group*.blade.php` |
| Brands | `BrandController` | `Brand`, `BrandHero` | `dashboard/brands/` | `brands.blade.php`, `brand_details.blade.php` |
| Blogs / Insights | `BlogController`, `ServiceMagazineController` | `Blog`, `Tag`, `BlogTag`, `ServiceMagazine` | `dashboard/blogs/` | `new-blog.blade.php`, `single_blog_page.blade.php`, `news-media.blade.php` |
| Projects | `ProjectController`, `ProjectCategoryController` | `Project`, `ProjectCategory`, `ProjectImage`, `ProjectVideo`, `ProjectDocument` | `dashboard/projects/` | `projects.blade.php`, `project_details.blade.php` |
| AI Project Planner | `ProjectPlannerController` (front), `AdminPlannerController`/`AdminPlannerBuilderController` (admin) | `ProjectPlannerSession`, `PlannerWorkflowStep` | `dashboard/planner/` | `plan-your-project.blade.php` |
| Project Process Manager | `AdminProjectProcessController` | `ProjectProcess` | `dashboard/project_process/` | (pushes into category/group `process_*`) |
| About page | `About_UsController`, `About_UsContentController`, `About_quoteController`, `eco_systemController` | `About_us`, `about_content`, `about_quote`, `about_eco` | `dashboard/About_us/` | `new-about.blade.php` |
| Clients / Testimonials | `clientsController`, `contactController` | `client`, `Testimonial`, `TestimonialSetting` | `dashboard/Clients/`, `dashboard/contact/` | `our-clients.blade.php`, `testimonials.blade.php` |
| Inquiries (CRM) | `AdminInquiryController` | `Inquiry` | `dashboard/inquiries/` | (inquiry modal on front) |
| Home sliders | `HomeSliderController` | `HomeSlider` | `dashboard/sliders/` | home |
| Announcements | `AnnouncementController` | `Announcement` | `dashboard/announcements/` | banners |
| Agents | `AgentController` | `Agent` | — | inquiry officer fields |
| Settings (AI/app) | `AdminSettingsController` | `AppSetting` | `dashboard/settings/` | — |
| Users/Roles/Perms | `UserController`, `RoleController`, `PermissionController`, `PermissionCategoryController` | `User`, `Admin`, Spatie `Permission`/`PermissionCategory` | `dashboard/user_management/`, `dashboard/roles_permissions/` | — |
| Tags (global) | `globalController`, `TagController` | `globaltag`, `Tag` | `dashboard/global_Tag/` | — |
| Google tags | `googleController` | `googletag` | `dashboard/Google_Tag/` | injected in layout |
| Test Q&A | `TestQuestionController`, `TestAnswerController` | `TestQuestion`, `TestAnswer` | `dashboard/test_questions/`, `test_answers/` | — |
| Uploads (editors) | `CkEditorUploadController`, `TinyMCEUploadController`, `ImageUploadController` | — | — | — |

## Key models & DB relationships
- **Service ⇆ Category: many-to-many** via pivot `service_categories` (`Service::categories()`, `Category::services()`). Manage from either side — service edit (`categories[]`), category edit ("Linked Services" `services[]`), or the **Map Services** modal on `categories/index`. All use `sync()`.
- **Category ⇆ MainCategory:** `category_main_category` pivot (`mainCategories()`), plus legacy single `main_category_id`. **Category ⇆ ServiceGroup:** `category_service_group` pivot.
- **Ordering:** `categories`, `main_categories`, `brands`, `blogs` have a `sort_order` column; lists query `orderBy('sort_order')->orderBy('id')`; dashboard reorder = jQuery-UI sortable → `*.reorder` route → `foreach update sort_order`.
- **Project Process:** `project_processes` master holds `process_intro/header/description/service_ids` (JSON arrays). `categories`/`service_groups` have nullable `project_process_id` FK that only *tracks* the link — on assign the controller **copies** master content into the record's own `process_*` columns (copy/push, not live reference). Public pages keep reading `process_*` — no change needed. Inline edits diverge from master until re-pushed.
- **Blog:** `Blog`↔`Tag` via `BlogTag`; blogs have `featured`/`news_focus`/manual dates/`sort_order`.
- **Spatie permissions** throughout (`permission:view categories`, etc.); `PermissionCategory` groups them in the UI.

## Assets & upload conventions
- Reference assets with `asset('public/...')`. Dashboard template under `public/dashboard/dist/` (libs: `select2`, `jquery-ui`, `tinymce`, `prismjs`).
- Uploads → `public/uploads/<type>/` where type ∈ `brands, blog_images, category_images, service_images, service_group_images, project_images, project_videos, project_documents, magazines, testimonials, ...`.
- **Naming:** `time() . '_' . Str::uuid() . '.ext'`, hero variants prefixed `_hero_`, sliding `_sliding_`, gallery `_gallery_`. Some legacy paths store the full `uploads/...` path in the column (e.g. category `hero_image`), others store only the filename (e.g. brand `logo`, joined with a base path at render). Check the column before assuming.

## AI / external services
- **Project Planner uses Google Gemini.** Active model in `AppSetting('gemini_model')`. **Free tier = 20 req/day per model** → on 429 `RESOURCE_EXHAUSTED`, `ProjectPlannerAI` silently falls back to generic deterministic rules (symptom: every plan looks the same). Fix by switching model (e.g. `gemini-2.5-flash-lite`) or enabling billing. Diagnose via `storage/logs/laravel.log` ("Planner Gemini error"); raw model JSON debug panel on `/plan-your-project` is gated to `APP_DEBUG=true`.

## Where to look — NOT yet explored this session
Start here instead of crawling everything:
- **AI planner internals:** `ProjectPlannerController`, `AdminPlannerBuilderController`, an `App\Services\ProjectPlannerAI`-style service (grep `Gemini`), `ProjectPlannerSession`/`PlannerWorkflowStep` models, `dashboard/planner/`.
- **Projects module:** `ProjectController`, `Project` + image/video/document models, `dashboard/projects/`, `projects.blade.php` / `project_details.blade.php`.
- **Blog/Insights ordering & news-focus:** `BlogController`, `ServiceMagazineController`, `dashboard/blogs/`, `new-blog.blade.php` (recent commits touched featured/news-focus).
- **Search:** `SearchController` (`/search`, `/search/live`).
- **Auth & RBAC:** `app/Http/Controllers/Auth/`, Spatie config, `roles_permissions/` views.
- **AI chat assistant / chat widget:** `ChatAssistantController` (live route `POST /ai-assistant/chat` = `ai.assistant.chat`), `ChatWidgetController` (`reply()`, currently commented out in routes). Both are rule-based/HTTP, not Gemini.
- **Shared layouts:** `resources/views/dashboard/layout.blade.php`, `resources/views/front/layout-2.blade.php` (host-drift sensitive — merge before deploy).
- **Front partials/components:** `resources/views/front/partials/`, `front/view/`.
- **Settings & app config:** `AdminSettingsController`, `AppSetting` model, `dashboard/settings/`.

## Commands
- Local DB tweaks: `php artisan tinker --execute="DB::statement('...')"`.
- `php artisan route:list` works. (Was previously broken because `ChatAssistantController`/`ChatWidgetController` were saved without a `.php` extension — fixed 2026-06-17.)
- `php artisan view:clear` after Blade edits if caching is on.
