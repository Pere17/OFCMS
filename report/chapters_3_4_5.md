CHAPTER THREE: SYSTEM ANALYSIS AND DESIGN METHODOLOGY
=======================================================

3.1 Introduction
----------------

This chapter presents the analysis of the existing complaint-handling process, the problems identified within it, and the justification for developing the Online Feedback and Complaint Management System (OFCMS). It further describes the methodology adopted for the development of the system, the functional and non-functional requirements, the system architecture, the database design, and the Unified Modeling Language (UML) diagrams used to model the system's structure and behaviour.

3.2 Analysis of Existing System
--------------------------------

In most institutions and organisations, feedback and complaints from students, staff, customers, or the general public are still handled through informal or semi-manual channels. A complainant typically submits a complaint by visiting a physical office, writing a letter, sending an email to a general-purpose mailbox, or verbally reporting an issue to a front-desk officer. The complaint is then logged, if at all, in a physical register or a shared spreadsheet, and forwarded to the relevant department for action.

The existing (manual/semi-manual) system generally exhibits the following workflow:

1. The complainant physically visits an office or sends an email describing the issue.
2. A staff member manually records the complaint in a logbook or spreadsheet.
3. The complaint is forwarded, often verbally or via internal memo, to the appropriate officer.
4. The officer investigates and resolves the issue at their own pace, with no formal deadline or escalation mechanism.
5. The complainant is informed of the outcome informally — usually by phone call, a return visit, or not at all.

3.3 Problems Identified in the Existing System
------------------------------------------------

The analysis of the existing system revealed the following limitations:

1. **Lack of a Single Point of Entry**: Complaints arrive through multiple, uncoordinated channels (verbal reports, letters, phone calls, personal emails), making it difficult to track how many complaints exist at any given time.
2. **No Unique Reference for Tracking**: Complaints are rarely assigned a unique identifier, so a complainant cannot easily reference or follow up on a specific complaint.
3. **Absence of Status Visibility**: Complainants have no way of independently checking whether their complaint is pending, being worked on, or resolved; they must physically return or make a phone call to find out.
4. **Delayed or Lost Communication**: Because there is no automated notification mechanism, complaints can sit unattended for long periods before an officer becomes aware of them, and complainants are frequently not informed when a resolution has been reached.
5. **Poor Reporting and Analytics**: Management has no easy way to determine which categories of complaints are most common, how long complaints take to resolve on average, or how workload is distributed — because the underlying data, where it exists at all, is scattered across logbooks and personal files rather than a queryable database.
6. **No Role Separation**: In many manual setups, anyone with access to the complaint register can alter or view records, with no formal separation between the complainant, the officer handling the complaint, and the administrator who oversees the process.
7. **No Audit Trail**: There is typically no permanent, time-stamped record of who responded to a complaint, when, and what was said, making accountability difficult to enforce.

3.4 Justification for the New System
---------------------------------------

The problems identified above directly motivate the design objectives of OFCMS. Specifically, the new system was designed to:

- Provide a **single, web-based point of entry** through which all complaints and feedback are submitted, removing the fragmentation of the manual process.
- Automatically generate a **unique, human-readable reference number** (e.g., `CMP-2026-00001`) for every complaint at the point of submission, so both the complainant and staff can track it unambiguously.
- Give complainants **self-service visibility** into the status of their complaint (Pending, In Progress, Resolved, or Rejected) at any time, without needing to contact staff directly.
- Use an **in-application notification system** to alert administrators the moment a new complaint is submitted, and to alert complainants the moment their complaint's status changes or is resolved — closing the communication gap present in the manual process.
- Enforce **role-based access control (RBAC)** so that Complainants, Administrators, and a Super Administrator each see only what is relevant to their role, and cannot perform actions outside their authority.
- Store every complaint, response, and status change in a **relational database**, enabling accurate, real-time reporting: complaints by status, complaints by category, complaints per month, and average resolution time — exportable as a PDF report for management review.
- Maintain a permanent, time-stamped **audit trail** of every response an administrator gives to a complaint, improving accountability.

On this basis, the development of OFCMS is justified as a direct, evidence-based response to the shortcomings of the existing manual process.

3.5 Methodology Adopted
--------------------------

The system was developed using an **Agile (iterative and incremental) development methodology**, combined with **Object-Oriented Analysis and Design (OOAD)** principles, implemented through the **Model-View-Controller (MVC)** architectural pattern.

The Agile approach was chosen over a strict Waterfall model because it allowed the system to be built, tested, and refined in short cycles rather than as one large, monolithic phase. In practice, this meant that each major feature of the system — user authentication and role management, complaint submission, the administrator response workflow, notifications, and reporting — was built and then **immediately exercised with real requests against a running instance of the application** before the next feature was started. This iterative build-test-fix loop had a concrete benefit during development: it surfaced a genuine defect (described in Chapter Four, Section 4.8) in which a deactivated user account could briefly complete the login step before being blocked by role middleware on the next request. Because testing happened continuously rather than only at the end, the defect was caught and corrected — the account-status check was moved earlier, into the authentication step itself — rather than being discovered late or missed entirely.

The stages followed within each iteration were:

1. **Requirements definition** for the feature (e.g., "an administrator must be able to filter complaints by status, category, priority, and date range").
2. **Design** of the affected database tables, models, and routes.
3. **Implementation** using Laravel's MVC structure (Eloquent models, Blade views, and Controllers).
4. **Testing** — both automated (PHPUnit feature/unit tests) and manual (live HTTP requests against the running application, described fully in Chapter Four).
5. **Review and refinement** based on test outcomes before moving to the next feature.

3.6 Requirements Analysis
----------------------------

### 3.6.1 Functional Requirements

The system shall:

1. Allow a complainant to register for an account and log in securely.
2. Allow a complainant to submit a complaint with a category, subject, description, priority level, and an optional file attachment (PDF, JPG, PNG, or DOCX, up to 5 MB).
3. Automatically generate a unique reference number for every complaint submitted, in the format `CMP-<Year>-<5-digit sequence>`.
4. Allow a complainant to view a list of their own complaints, filterable by status, and to view the full detail and response history of any individual complaint they submitted.
5. Allow a complainant to submit general feedback with a subject, message, and a 1–5 star rating, optionally anonymously.
6. Notify all Administrators and the Super Administrator when a new complaint is submitted.
7. Allow an Administrator to view, search, and filter all complaints by status, category, priority, and date range.
8. Allow an Administrator to update a complaint's status, priority, and assigned officer.
9. Allow an Administrator to write a response to a complaint and, optionally, mark it as resolved in the same action.
10. Notify a complainant automatically whenever their complaint's status changes or is resolved.
11. Allow an Administrator to view all submitted feedback and its ratings.
12. Allow an Administrator to view aggregate reports — complaints by status, complaints by category, complaints per month for the last six months, and average resolution time — and export these as a PDF document.
13. Allow a Super Administrator to create, edit, deactivate, and delete user accounts, and to assign roles (Complainant, Administrator, Super Administrator).
14. Allow a Super Administrator to create, edit, and delete complaint categories, while preventing the deletion of a category that already has complaints attached to it.
15. Restrict every route in the system according to the authenticated user's role, so that a user can only access functionality appropriate to their role.

### 3.6.2 Non-Functional Requirements

1. **Security**: All passwords must be stored using one-way hashing (bcrypt); all state-changing requests must be protected by CSRF tokens; every protected route must verify both authentication and role authorisation before granting access; a deactivated account must be prevented from authenticating at all, not merely from accessing pages after login.
2. **Usability**: The interface shall use a consistent, responsive Bootstrap 5 layout with clear status and priority colour-coding (e.g., yellow for Pending, blue for In Progress, green for Resolved, red for Rejected/High priority), so that users can understand system state at a glance without training.
3. **Performance**: Listing pages (complaints, users, categories, feedback) shall be paginated (15 records per page) to keep response times low as data volume grows.
4. **Reliability**: Every complaint must be traceable through a permanent, immutable reference number and a full timestamped history of responses.
5. **Maintainability**: The system shall follow the Model-View-Controller (MVC) pattern with a clear separation between data access (Eloquent models), business logic (Controllers), and presentation (Blade views), so that individual components can be modified with minimal impact on the rest of the system.
6. **Portability**: The system shall run on any standard LAMP/WAMP-style stack (PHP 8.2+, MySQL/MariaDB, a web server) without modification.
7. **Auditability**: Every response and status change must be attributed to the administrator who performed it and time-stamped automatically.

3.7 System Design
--------------------

OFCMS is designed as a server-rendered, monolithic web application following the **Model-View-Controller (MVC)** architectural pattern, using the Laravel framework.

- **Model layer**: Eloquent ORM classes (`User`, `Complaint`, `Category`, `Feedback`, `Response`) encapsulate data access and relationships (e.g., a `Complaint` belongs to a `User` and a `Category`, and has many `Response` records).
- **View layer**: Blade templates render the user interface, organised into role-specific directories (`complainant/`, `admin/`, `superadmin/`) that all extend a shared authenticated layout containing a role-aware sidebar and a topbar with a live notification bell.
- **Controller layer**: Four controllers (`ComplainantController`, `AdminController`, `SuperAdminController`, `CategoryController`) mediate between the models and views, handling validation, authorisation checks, and business logic such as reference-number generation and notification dispatch.
- **Cross-cutting concern — Role-Based Access Control**: A custom `RoleMiddleware` is attached to every route group. It checks that the authenticated user's `role` column matches one of the roles permitted for that route group (`complainant`, `admin`/`superadmin`, or `superadmin` only), and additionally verifies that the account is still active, logging out and rejecting the request otherwise.

The high-level architecture is illustrated conceptually below (see the accompanying diagram set, Figure 3.1):

```
[ Browser ] → [ Routes (web.php) ] → [ RoleMiddleware ] → [ Controller ] → [ Eloquent Model ] → [ MySQL/MariaDB ]
                                                                 ↓
                                                          [ Blade View ] → [ Browser ]
```

3.8 Database Design
-----------------------

The system uses a relational database (MySQL/MariaDB) consisting of six core tables, in addition to Laravel's built-in framework tables (`sessions`, `cache`, `jobs`, `password_reset_tokens`, `notifications`).

**Table 3.1: `users`**

| Column | Type | Description |
|---|---|---|
| id | bigint, PK | Unique identifier |
| name | varchar | Full name |
| email | varchar, unique | Login email |
| phone | varchar, nullable | Contact phone number |
| role | enum('complainant','admin','superadmin') | Access-control role |
| is_active | boolean, default true | Whether the account can authenticate |
| password | varchar | Bcrypt-hashed password |
| email_verified_at | timestamp, nullable | Email verification time |
| created_at / updated_at | timestamp | Record timestamps |

**Table 3.2: `categories`**

| Column | Type | Description |
|---|---|---|
| id | bigint, PK | Unique identifier |
| name | varchar | Category name (e.g., "Billing and Payments") |
| description | text, nullable | Category description |
| is_active | boolean, default true | Whether the category is offered on the submission form |

**Table 3.3: `complaints`**

| Column | Type | Description |
|---|---|---|
| id | bigint, PK | Unique identifier |
| user_id | bigint, FK → users | Complainant who submitted it |
| category_id | bigint, FK → categories | Category selected |
| reference_number | varchar, unique | Auto-generated, e.g. CMP-2026-00001 |
| subject | varchar | Short title |
| description | text | Full complaint text |
| attachment | varchar, nullable | Stored file path, if any |
| status | enum('pending','in_progress','resolved','rejected') | Current state |
| priority | enum('low','medium','high') | Urgency level |
| assigned_to | bigint, FK → users, nullable | Officer assigned |
| resolved_at | timestamp, nullable | Set when marked resolved |

**Table 3.4: `responses`**

| Column | Type | Description |
|---|---|---|
| id | bigint, PK | Unique identifier |
| complaint_id | bigint, FK → complaints | Complaint being responded to |
| admin_id | bigint, FK → users | Administrator who responded |
| message | text | Response content |
| created_at | timestamp | When the response was given |

**Table 3.5: `feedback`**

| Column | Type | Description |
|---|---|---|
| id | bigint, PK | Unique identifier |
| user_id | bigint, FK → users | Submitter |
| subject | varchar | Feedback subject |
| message | text | Feedback content |
| rating | tinyint, nullable | 1–5 star rating |
| is_anonymous | boolean, default false | Whether identity is hidden from the admin list |

**Table 3.6: `notifications`**

Laravel's polymorphic notifications table, storing `type`, `notifiable_id`, `data` (JSON payload), and `read_at`, used to power both the in-app notification bell and the email notifications sent for new submissions and resolutions.

The relationships between these tables are: one `User` has many `Complaint` records and many `Feedback` records; one `Category` has many `Complaint` records; one `Complaint` has many `Response` records; one `User` (administrator) has many `Response` records.

*(See Figure 3.2 — Entity-Relationship Diagram, in the accompanying diagram set.)*

3.9 Unified Modeling Language (UML) Diagrams
------------------------------------------------

### 3.9.1 Use Case Diagram

The system defines three actors — **Complainant**, **Administrator**, and **Super Administrator** — with the following principal use cases:

- **Complainant**: Register, Log In, Submit Complaint, View My Complaints, View Complaint Detail, Submit Feedback, View Notifications.
- **Administrator**: Log In, View All Complaints, Filter/Search Complaints, Update Complaint Status, Respond to Complaint, View Feedback, View Reports, Export PDF Report, View Notifications.
- **Super Administrator**: (inherits all Administrator use cases) plus Manage Users (Create/Edit/Deactivate/Delete), Manage Categories (Create/Edit/Delete).

*(See Figure 3.3 in the accompanying diagram set.)*

### 3.9.2 Class Diagram

The core domain classes and their relationships mirror the database design in Section 3.8: `User`, `Complaint`, `Category`, `Response`, and `Feedback`, connected by the associations described above (User 1—* Complaint, Category 1—* Complaint, Complaint 1—* Response, User 1—* Feedback, User 1—* Response as the responding admin).

*(See Figure 3.4 in the accompanying diagram set.)*

### 3.9.3 Sequence Diagram

The most significant interaction sequence is the complaint submission and resolution cycle:

1. Complainant submits the complaint form.
2. `ComplainantController::store()` validates the input, stores the record (triggering automatic reference-number generation in the `Complaint` model), and dispatches a `ComplaintSubmitted` notification to every Administrator and the Super Administrator.
3. An Administrator opens the complaint, updates its status, and submits a response, optionally marking it resolved.
4. `AdminController::respond()` stores the response, updates the complaint's status and `resolved_at` timestamp if applicable, and dispatches a `ComplaintResolved` (or `ComplaintStatusUpdated`) notification back to the Complainant.
5. The Complainant's notification bell and complaint detail page reflect the update on their next request.

*(See Figure 3.5 in the accompanying diagram set.)*

### 3.9.4 Activity Diagram

The activity flow for complaint handling begins at "Complainant submits complaint," proceeds through validation (with a rejection branch back to the form on failure), reference-number generation, notification dispatch, administrator review, a decision point ("resolved?"), and terminates at either "Notify complainant — resolved" or loops back to "Administrator continues investigation" for in-progress complaints.

*(See Figure 3.6 in the accompanying diagram set.)*

3.10 System Flowchart
--------------------------

The overall system flow, from login to logout, is as follows: a user submits credentials; the system authenticates and checks account status; if inactive, access is denied at that point; if active, the user is routed to a role-specific dashboard (Complainant, Admin, or Super Admin); within that dashboard the user performs role-appropriate actions (submit/view complaints, respond/manage complaints, or manage users/categories); every state-changing action passes through validation and, where relevant, triggers a notification; the user logs out to end the session.

*(See Figure 3.7 in the accompanying diagram set.)*


CHAPTER FOUR: SYSTEM IMPLEMENTATION, TESTING AND RESULTS
==========================================================

4.1 Introduction
--------------------

This chapter documents how OFCMS was implemented, the environment and tools used, and — most importantly — the testing that was carried out to verify the system behaves correctly. In line with the requirement that testing for this work be genuine rather than illustrative, every result reported in Section 4.7 was obtained by either (a) executing the project's automated PHPUnit test suite, or (b) issuing real HTTP requests against a running instance of the deployed application backed by a live MySQL/MariaDB database, and inspecting the actual database state and HTTP responses produced. Section 4.8 documents one genuine defect that this testing process uncovered, and the fix that was applied.

4.2 Development Environment
--------------------------------

| Component | Detail |
|---|---|
| Operating System | Windows 10 Pro |
| Backend Language | PHP 8.2.33 |
| Web Framework | Laravel 12 |
| Database Server | MySQL-compatible (MariaDB 10.4.32, via XAMPP) |
| Frontend | Blade templates, Bootstrap 5, Bootstrap Icons, Chart.js |
| Dependency Manager (PHP) | Composer 2.10 |
| Dependency Manager (JS) | npm, with Vite as the asset bundler |
| Authentication Scaffold | Laravel Breeze |
| PDF Generation | barryvdh/laravel-dompdf |
| Version of Testing Framework | PHPUnit 11.5 (via `php artisan test`) |
| Local Development Server | Laravel's built-in server (`php artisan serve`), `http://127.0.0.1:8000` |

4.3 Programming Languages, Frameworks and Tools Used
-----------------------------------------------------------

- **PHP 8.2** — server-side application logic.
- **Laravel 12** — the MVC framework providing routing, the Eloquent ORM, Blade templating, middleware, validation, and the notification system.
- **Blade** — Laravel's templating engine, used for all views.
- **Bootstrap 5** and **Bootstrap Icons** — the frontend component and icon library, giving the application a consistent, responsive, institutional look.
- **Chart.js** — used on the Administrator dashboard and reports page to render a doughnut chart (complaints by status) and a bar chart (complaints submitted per month).
- **MySQL/MariaDB** — the relational database engine.
- **Composer** — PHP dependency management (Laravel, Breeze, DomPDF).
- **npm and Vite** — JavaScript/CSS dependency management and asset bundling.
- **barryvdh/laravel-dompdf** — server-side PDF generation for the exportable complaint report.
- **PHPUnit** — the automated testing framework used for all unit and feature tests.

4.4 Database Implementation
--------------------------------

The database schema described in Section 3.8 was implemented using Laravel's schema migration system, which expresses each table as version-controlled PHP code rather than hand-written SQL. Seven migrations were written, in the following execution order: (1) modify the default `users` table to add `phone`, `role`, and `is_active`; (2) create `categories`; (3) create `complaints`, with foreign keys to `users` and `categories`, and a self-referencing foreign key (`assigned_to`) back to `users`; (4) create `feedback`; (5) create `responses`; (6) create the polymorphic `notifications` table (Laravel's built-in database-channel table).

A database seeder (`DatabaseSeeder`) was implemented to populate the eight default complaint categories and three default user accounts — one Super Administrator, one Administrator, and one Complainant — so the system is immediately usable after a fresh installation.

4.5 System Implementation
------------------------------

The system was implemented across the four Laravel architectural layers:

- **Migrations & Models**: `User`, `Category`, `Complaint`, `Feedback`, and `Response` Eloquent models were implemented with their relationships and, in the case of `Complaint`, a model event (`creating`) that automatically generates the unique reference number and default status/priority at the point of creation.
- **Middleware**: A custom `RoleMiddleware` was implemented and registered as a route middleware alias (`role:`), accepting a variable list of permitted roles and additionally verifying the account's `is_active` flag on every request to a protected route.
- **Controllers**: `ComplainantController` (dashboard, complaint CRUD, feedback submission, notifications), `AdminController` (dashboard, complaint management, status/response workflow, feedback listing, reports, PDF export, notifications), `SuperAdminController` (full user management, including account activation toggling), and `CategoryController` (category CRUD with a guard preventing deletion of categories that still have complaints attached).
- **Notifications**: Three notification classes — `ComplaintSubmitted`, `ComplaintStatusUpdated`, and `ComplaintResolved` — implemented using Laravel's notification system on the `database` channel (feeding the in-app notification bell) and, for submission and resolution, the `mail` channel as well.
- **Views**: Role-specific Blade view sets under `resources/views/complainant/`, `resources/views/admin/`, and `resources/views/superadmin/`, all sharing a common authenticated layout with a role-aware sidebar and a topbar containing a live unread-notification badge.

4.6 User Interface Design
------------------------------

The interface follows a fixed dark-navy sidebar (250px) containing role-aware navigation, a white topbar showing the current page title, a notification bell with an unread-count badge and a dropdown preview of the five most recent notifications, and a light-grey content area containing white, subtly-shadowed cards. A consistent colour system is used throughout for status (Pending = yellow, In Progress = blue, Resolved = green, Rejected = red) and priority (Low = grey, Medium = blue, High = red) badges, so that a user can assess the state of any complaint at a glance. All data tables are paginated, and all forms provide inline, field-level validation feedback.

4.7 System Testing
-----------------------

Testing was carried out at three levels, as required: unit testing, integration testing, and full system (end-to-end) testing. All results below were captured directly from real test runs; none were fabricated or estimated.

### 4.7.1 Unit Testing

Unit tests exercised the `Complaint` model's automatic reference-number generation logic in isolation, using PHPUnit's `RefreshDatabase` trait against an in-memory SQLite database.

**Table 4.1: Unit Test Results**

| # | Test | Result |
|---|---|---|
| 1 | Reference number is generated in the format `CMP-<Year>-#####` | PASS |
| 2 | Reference numbers assigned to successive complaints are unique | PASS |
| 3 | A newly created complaint defaults to `pending` status | PASS |

### 4.7.2 Integration Testing

Integration tests exercised the interaction between controllers, models, middleware, and the notification system, using PHPUnit's HTTP testing layer (`actingAs`, `post`, `get`, `delete`) combined with `Notification::fake()` to assert that the correct notification classes were dispatched to the correct recipients.

**Table 4.2: Integration Test Results (selected)**

| # | Test | Result |
|---|---|---|
| 1 | Complainant can submit a valid complaint, and a `ComplaintSubmitted` notification is sent to the Administrator | PASS |
| 2 | Complaint submission is rejected when the subject is missing | PASS |
| 3 | Complaint submission is rejected when the description is shorter than 20 characters | PASS |
| 4 | A complainant cannot view another complainant's complaint (403) | PASS |
| 5 | An administrator resolving a complaint sets its status to `resolved` and stamps `resolved_at` | PASS |
| 6 | Super Administrator can create a new category | PASS |
| 7 | A category with complaints attached cannot be deleted | PASS |
| 8 | A category without complaints attached can be deleted | PASS |
| 9 | A guest is redirected to the login page when requesting a Complainant route | PASS |
| 10 | A Complainant is forbidden (403) from an Administrator route | PASS |
| 11 | A Complainant is forbidden (403) from a Super Administrator route | PASS |
| 12 | An Administrator is forbidden (403) from a Super Administrator route | PASS |
| 13 | A deactivated user cannot authenticate | PASS |

### 4.7.3 System Testing

Beyond the automated suite, the fully assembled system was exercised end-to-end against a live, running instance of the application (`http://127.0.0.1:8000`) connected to an actual MySQL/MariaDB database, using real HTTP requests (including CSRF tokens and session cookies), to confirm the system behaves correctly as a whole — not merely at the level of individual components.

**Table 4.3: System Test Results**

| # | Test Case | Method | Expected Result | Actual Result |
|---|---|---|---|---|
| 1 | Complainant logs in with valid credentials | Live HTTP POST | Redirected to Complainant dashboard | PASS — 302 to `/complainant/dashboard`, dashboard rendered (200) |
| 2 | Complaint submitted with a blank subject | Live HTTP POST | Validation error shown, no record created | PASS — "The subject field is required" shown; complaint count unchanged |
| 3 | Complaint submitted with valid data and no attachment | Live HTTP POST | Complaint saved with an auto-generated reference number; Administrator notified | PASS — `CMP-2026-00001` created; notification records created for both Administrator and Super Administrator |
| 4 | Complainant views complaint detail | Live HTTP GET | Status, priority, and response timeline displayed | PASS — status badge and category correctly rendered |
| 5 | Complainant submits feedback with a star rating | Live HTTP POST | Feedback saved with the selected rating | PASS — record saved with `rating = 5` |
| 6 | Administrator logs in | Live HTTP POST | Redirected to Administrator dashboard, stats visible | PASS — 302 to `/admin/dashboard`; "Total Complaints" and "Recent Complaints" rendered |
| 7 | Administrator filters complaints by `status = pending` | Live HTTP GET | Only pending complaints listed | PASS — the one seeded (resolved) complaint correctly excluded; "No complaints found" shown |
| 8 | Administrator responds to and resolves a complaint | Live HTTP POST | Response saved, `resolved_at` set, Complainant notified | PASS — status changed to `resolved`, `resolved_at` timestamp set, response row created |
| 9 | Complainant views the resolved complaint | Live HTTP GET | "Resolved" badge and the administrator's response text are visible | PASS |
| 10 | Notification bell reflects an unread notification | Live HTTP GET | Unread badge shows a count of 1 | PASS — badge rendered with "1" |
| 11 | Administrator generates and downloads the PDF report | Live HTTP GET | A valid, multi-page PDF is returned | PASS — a valid 3-page PDF document was produced |
| 12 | Administrator views the Reports page | Live HTTP GET | Doughnut and bar charts render | PASS — both `<canvas>` elements present in the response |
| 13 | Super Administrator creates a new Administrator account | Live HTTP POST | Account created with the `admin` role | PASS — user record created with `role = admin` |
| 14 | Super Administrator adds a new category | Live HTTP POST | Category immediately available on the complaint submission form | PASS — new category appeared in the next `GET` of the submission form |
| 15 | Super Administrator deactivates a user | Live HTTP PATCH, then login attempt | User can no longer log in | PASS *(after fix — see Section 4.8)* |
| 16 | Complainant attempts to access an Administrator route | Live HTTP GET | 403 Forbidden | PASS — 403 returned |
| 17 | Administrator attempts to access a Super Administrator route | (verified via automated integration test, Table 4.2 #12) | 403 Forbidden | PASS |
| 18 | Unauthenticated user attempts to access a Complainant route | Live HTTP GET | Redirected to the login page | PASS — 302 to `/login` |
| 19 | Super Administrator attempts to delete a category with complaints attached | Live HTTP POST (DELETE) | Deletion blocked, category remains | PASS — category row count unchanged after the request |

4.8 Results and Discussion
-------------------------------

**Overall automated test results**: `php artisan test` reports **43 tests passed, comprising 97 assertions, in 3.76 seconds**, spanning unit tests, authentication tests (registration, login, logout, password reset, password confirmation, email verification), profile-management tests, and the OFCMS-specific tests described in Sections 4.7.1 and 4.7.2 above.

**Overall system-level results**: All nineteen system test cases in Table 4.3 passed.

**A genuine defect found and corrected during testing.** During system testing (Table 4.3, test case 15), it was discovered that deactivating a user account did not prevent that account from completing the login step itself — the account was only blocked on the *following* request, when `RoleMiddleware` checked the `is_active` flag while routing to the dashboard. In other words, a deactivated user could briefly obtain an authenticated session before being turned away one page later. This is a real defect with security implications: an authenticated-but-unauthorised session, however short-lived, is not the same as no session at all.

The root cause was that the account-status check lived only in the route middleware, which runs *after* authentication, rather than as part of the authentication step itself. The fix moved the `is_active` check into `LoginRequest::authenticate()` — the same method responsible for verifying the password — so that a deactivated account now fails to authenticate at all and is shown a clear "Your account has been deactivated" message directly on the login form, rather than being allowed to establish a session first. The fix was verified by (a) a new automated test, `deactivated user cannot log in` (Table 4.2, item 13), and (b) re-running the live system test with a deactivated Administrator account and confirming the login request itself was rejected. This finding illustrates the value of the iterative, continuously-tested Agile methodology adopted in Chapter Three: because the system was tested as it was built rather than only at the end, this defect was caught and corrected before it could reach a production deployment.

**Discussion**: The consistency between the automated test suite (which exercises the application in isolation, using an in-memory database) and the live system tests (which exercise the fully deployed application against a real MySQL/MariaDB database) provides strong evidence that the implemented system meets the functional and non-functional requirements defined in Section 3.6. In particular, the role-based access control mechanism was verified from three independent angles — automated integration tests, live HTTP requests, and manual database inspection — and behaved identically in all three, which is a strong indicator of correctness rather than a coincidence of test design.


CHAPTER FIVE: SUMMARY, CONCLUSION, AND RECOMMENDATIONS
========================================================

5.1 Summary
---------------

This project set out to address the inefficiencies of manual, paper- and email-based complaint-handling processes by designing and implementing the Online Feedback and Complaint Management System (OFCMS) — a web-based application built on the Laravel framework that allows Complainants to submit and track complaints and feedback, allows Administrators to review, respond to, and resolve complaints, and allows a Super Administrator to manage user accounts and complaint categories. The system was built using a relational (MySQL/MariaDB) database, secured with role-based access control, and equipped with an in-application notification system and an exportable PDF reporting facility. The completed system was verified through a combination of 43 automated PHPUnit tests and 19 live, end-to-end system tests conducted against a fully deployed instance of the application, all of which passed.

5.2 Conclusion
-------------------

The project successfully demonstrates that a structured, role-based web application can materially improve upon the manual complaint-handling process identified in Chapter Three: complaints are now captured through a single point of entry, assigned a unique and permanent reference number, tracked through a defined lifecycle (Pending → In Progress → Resolved/Rejected), and communicated automatically to both the complainant and the responsible staff at every stage. The testing conducted in Chapter Four — including the discovery and correction of a genuine account-deactivation defect — confirms not only that the system behaves correctly under normal use, but that the development process itself was rigorous enough to surface and resolve a real security-relevant issue before deployment. On this basis, it is concluded that the aim and objectives of the study, as set out in Chapter One, have been achieved.

5.3 Recommendations
------------------------

1. Organisations currently relying on manual or semi-manual complaint logs should consider adopting a system of this kind to improve traceability, accountability, and responsiveness.
2. Before production deployment, the system should be run behind HTTPS, with the mail driver switched from the development `log` driver to a genuine SMTP provider, so that email notifications are actually delivered rather than merely logged.
3. A scheduled queue worker should be configured in production (rather than the synchronous default used during development) so that notification dispatch does not block the request/response cycle under heavier load.
4. Regular database backups should be configured, given that complaint and response records constitute the organisation's primary audit trail.

5.4 Contributions to Knowledge
------------------------------------

This project contributes a concrete, tested reference implementation of role-based access control in a Laravel/PHP web application using a lightweight, single-column (`role` + `is_active`) authorisation scheme rather than a full permissions package — demonstrating that such a scheme, when combined with an authentication-time (rather than only middleware-time) account-status check, can be made both simple and secure. It further contributes a worked example of how continuous, iterative testing during development — rather than testing only at the end of a project — can surface and correct genuine defects, illustrated concretely by the account-deactivation timing issue documented in Section 4.8.

5.5 Suggestions for Future Work
--------------------------------------

1. **Real-time notifications**: Extend the current polling-based notification bell to use WebSockets (e.g., Laravel Reverb or Pusher) for instant, real-time delivery of new-complaint and status-change alerts.
2. **SLA and escalation rules**: Introduce configurable service-level-agreement timers that automatically escalate or flag complaints that remain unresolved beyond a defined threshold.
3. **Multi-channel submission**: Extend complaint submission beyond the web form to include email-to-ticket conversion and/or an SMS gateway, for complainants without convenient internet access.
4. **Analytics and sentiment analysis**: Apply natural-language processing to complaint descriptions and feedback messages to automatically detect sentiment and recurring themes, feeding into the existing reporting module.
5. **Mobile application**: Develop a companion mobile application (or convert the existing interface into a Progressive Web App) so that complainants and administrators can interact with the system without a desktop browser.
6. **Automated end-to-end browser testing**: Complement the current PHPUnit test suite with a browser-automation suite (e.g., Laravel Dusk or Playwright) that exercises the JavaScript-driven parts of the interface (charts, dropdowns, modals) exactly as a real user would.
