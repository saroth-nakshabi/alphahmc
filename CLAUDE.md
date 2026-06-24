# AlphaHMC — Project Guide

> High-signal map for future sessions. Verify `file:line` against current code before trusting — this drifts.

## Stack
Laravel 11 marketing/content site for **Alpha Health Group** (UAE healthcare). MySQL via local XAMPP; production on **cPanel (nexthealthos.com) with NO terminal** — all DB changes applied by hand in phpMyAdmin. Public front-end (Bootstrap 5 + custom CSS in Blade) plus an admin **dashboard** (ModernAdmin/AdminKit: jQuery, Select2, SweetAlert2 `Toast`, TinyMCE) managing services, categories, brands, blogs, projects, an AI project planner, and more.

## ⚠️ Critical conventions (read before any change)
- **End every DB-touching task with one consolidated raw SQL block** (ALTER/UPDATE/CREATE + backfills, phpMyAdmin-ready). Prod has no artisan/migrations/tinker; skipping this → `SQLSTATE[42S22]` in prod. Verifying on local XAMPP via tinker is fine, but the SQL block is still mandatory.
- **Migrations drift / sit "Pending".** Columns are added directly via SQL, so `php artisan migrate` fails on already-existing columns (e.g. `main_categories.sort_order`). Make new migrations idempotent (`if (Schema::hasColumn(...)) return;`) and apply columns to local via `DB::statement`, not a full migrate. (`php artisan migrate` is denied in settings — apply SQL by hand.)
- **Host code has drifted from this repo** — the live host runs code not in git (e.g. a Strategies module; local `routes/web.php` only has a `strategy.index` stub). Before deploying *shared/global* files (`routes/web.php`, `dashboard/layout.blade.php`, `front/layout-2.blade.php`, composer files), fetch the live version and **merge — never overwrite blindly**.
- **Asset paths use `asset('public/...')`** — the public dir is nested at `/public/public/` on cPanel. Uploads live in `public/uploads/<type>/`.
- **Deploy zips:** build only when asked, using **PHP `ZipArchive` with forward-slash paths** (not PowerShell `Compress-Archive`); include all files changed since the last deployed zip, and list changed paths in the task summary.
- **TinyMCE/CKEditor sync:** call `tinymce.triggerSave()` (or sync `editor.getData()` → textarea) **before** `new FormData(form)`, or rich-text fields submit empty and `required` validation fails silently. Declare editor vars at module level.
- **Dead columns kept intentionally** (2026-06-13 audit): `service_groups.main_category_id`, `category_id`, `sliding_image`, `related_services`, `areaServed`, `serviceType` — don't build against them; use the `category_service_group` pivot. **Trap:** `service_groups.content` is still ACTIVE even though `categories.content` was dropped from category forms — don't "clean up" by symmetry.

## Routing (`routes/web.php`)
- **Front-end (public, no auth):** ~67–165. Home `/` → `MainHomeController@index`. Most public pages = `MainHomeController` (services, categories, brands, about, blog/insights, projects, testimonials). Legacy pages on `HomeController` (`/about`, `/contact`, `/blog`, `/how_alpha_work`). AI planner = `ProjectPlannerController` (`/plan-your-project*`). Search = `SearchController`. Sitemap = `SitemapController`.
- **Dashboard (auth):** `middleware('auth')` group from ~169, mostly gated by Spatie `permission:*`. Organized by `// section` comments; brands/clients/eco use `Route::prefix('dashboard/...')` sub-groups.
- Many routes use custom slugs (`/Alpha-Health-Group-Branches` = brands, `/about-alpha-health-group` = about) — grep the route **name** (`front.brands`), not the path.

## Feature map (feature → controller → model(s) → key views)
| Feature | Controller | Model(s) | Views |
|---|---|---|---|
| Services | `ServiceController` | `Service`, `ServiceImage`, `ServiceDocument` | `dashboard/services/`, `service.blade.php` |
| Sub-categories | `CategoryController` | `Category` | `dashboard/categories/`, `service_category.blade.php` |
| Main categories | `MainCategoryController` | `MainCategory` | (inline) |
| Service groups (packages) | `ServiceGroupController` | `ServiceGroup` | `dashboard/service_group/`, `service_group*.blade.php` |
| Brands | `BrandController` | `Brand`, `BrandHero` | `dashboard/brands/`, `brands.blade.php`, `brand_details.blade.php` |
| Blogs / Insights | `BlogController`, `ServiceMagazineController` | `Blog`, `Tag`, `BlogTag`, `ServiceMagazine` | `dashboard/blogs/`, `new-blog.blade.php`, `single_blog_page.blade.php`, `news-media.blade.php` |
| Projects | `ProjectController`, `ProjectCategoryController` | `Project`, `ProjectCategory`, `ProjectImage/Video/Document` | `dashboard/projects/`, `projects.blade.php`, `project_details.blade.php` |
| AI Project Planner | `ProjectPlannerController` (front), `AdminPlannerController`/`AdminPlannerBuilderController` (admin) | `ProjectPlannerSession`, `PlannerWorkflowStep` | `dashboard/planner/`, `plan-your-project.blade.php` |
| Project Process Manager | `AdminProjectProcessController` | `ProjectProcess` | `dashboard/project_process/` (pushes into category/group `process_*`) |
| About | `About_UsController`, `About_UsContentController`, `About_quoteController`, `eco_systemController` | `About_us`, `about_content`, `about_quote`, `about_eco` | `dashboard/About_us/`, `new-about.blade.php` |
| Clients / Testimonials | `clientsController`, `contactController` | `client`, `Testimonial`, `TestimonialSetting` | `dashboard/Clients/`, `our-clients.blade.php`, `testimonials.blade.php` |
| Inquiries (CRM) | `AdminInquiryController` | `Inquiry` | `dashboard/inquiries/` (front modal) |
| Settings (AI/app) | `AdminSettingsController` | `AppSetting` | `dashboard/settings/` |
| Users/Roles/Perms | `UserController`, `RoleController`, `PermissionController`, `PermissionCategoryController` | `User`, `Admin`, Spatie `Permission`/`PermissionCategory` | `dashboard/user_management/`, `dashboard/roles_permissions/` |

**Minor modules:** Home sliders (`HomeSliderController`/`HomeSlider`), Announcements (`AnnouncementController`/`Announcement`), Agents (`AgentController`/`Agent`), global + blog Tags (`globalController`/`TagController`, `globaltag`/`Tag`), Google tags (`googleController`/`googletag`, injected in layout), Test Q&A (`TestQuestionController`/`TestAnswerController`), editor uploads (`CkEditorUploadController`, `TinyMCEUploadController`, `ImageUploadController`).

## Key models & DB relationships
- **Service ⇆ Category: many-to-many** via pivot `service_categories` (`Service::categories()`, `Category::services()`). Manage from either side or the **Map Services** modal on `categories/index`; all use `sync()`.
- **Category ⇆ MainCategory:** `category_main_category` pivot (`mainCategories()`) + legacy single `main_category_id`. **Category ⇆ ServiceGroup:** `category_service_group` pivot.
- **Ordering:** `categories`, `main_categories`, `brands`, `blogs` have `sort_order`; lists `orderBy('sort_order')->orderBy('id')`; dashboard reorder = jQuery-UI sortable → `*.reorder` route → `foreach update sort_order`.
- **Project Process:** `project_processes` master holds `process_intro/header/description/service_ids` (JSON). `categories`/`service_groups` have nullable `project_process_id` FK that only *tracks* the link — on assign the controller **copies** master content into the record's own `process_*` columns (copy/push, not live reference). Public pages read `process_*` directly. Inline edits diverge from master until re-pushed.
- **Blog:** `Blog`↔`Tag` via `BlogTag`; blogs have `featured`/`news_focus`/manual dates/`sort_order`.
- **Spatie permissions** throughout (`permission:view categories`, etc.); `PermissionCategory` groups them in the UI.

## Assets & uploads
- Reference assets with `asset('public/...')`. Dashboard template under `public/dashboard/dist/` (select2, jquery-ui, tinymce, prismjs).
- Uploads → `public/uploads/<type>/`; type ∈ `brands, blog_images, category_images, service_images, service_group_images, project_images, project_videos, project_documents, magazines, testimonials, ...`.
- **Naming:** `time() . '_' . Str::uuid() . '.ext'`; hero variants `_hero_`, sliding `_sliding_`, gallery `_gallery_`. Some legacy columns store the full `uploads/...` path (e.g. category `hero_image`), others only the filename (e.g. brand `logo`, joined with a base path at render) — check the column before assuming.

## AI / external services
- **Project Planner uses Google Gemini.** Active model in `AppSetting('gemini_model')`. **Free tier = 20 req/day per model** → on 429 `RESOURCE_EXHAUSTED`, `ProjectPlannerAI` silently falls back to generic deterministic rules (symptom: every plan looks the same). Fix by switching model (e.g. `gemini-2.5-flash-lite`) or enabling billing. Diagnose via `storage/logs/laravel.log` ("Planner Gemini error"); raw model JSON debug panel on `/plan-your-project` is gated to `APP_DEBUG=true`.
- **AI chat assistant:** `ChatAssistantController` (`POST /ai-assistant/chat` = `ai.assistant.chat`), `ChatWidgetController` (`reply()`, currently commented out in routes). Both rule-based/HTTP, not Gemini.

## Commands
- Local DB tweaks: `php artisan tinker --execute="DB::statement('...')"`.
- `php artisan route:list` works. (Was broken when `ChatAssistantController`/`ChatWidgetController` were saved without a `.php` extension — fixed 2026-06-17.)
- `php artisan view:clear` after Blade edits if caching is on.
- To find a route, grep the route **name**, not the path.
