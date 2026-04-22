# Admin Panel Seed Runbook

## Quick Start (recommended)

Run from `backend/`:

```bash
php artisan migrate:fresh --seed
```

This drops all tables, re-applies every migration, then runs `DatabaseSeeder` → `AdminPanelSeeder` which calls all child seeders in order.

---

## Seed Only (without touching migrations)

If the database is already migrated and you only want to (re)populate the data:

```bash
php artisan db:seed --class=AdminPanelSeeder
```

All child seeders are idempotent — they skip rows that already exist, so running this multiple times is safe.

---

## Run a Single Child Seeder

| Seeder | Command |
|--------|---------|
| Roles | `php artisan db:seed --class=RoleSeeder` |
| Admin Users | `php artisan db:seed --class=AdminUsersSeeder` |
| Housing (buildings, entrances, apartments) | `php artisan db:seed --class=HousingSeeder` |
| Profiles & Activities | `php artisan db:seed --class=ProfilesAndActivitiesSeeder` |
| Requests | `php artisan db:seed --class=RequestsSeeder` |
| Announcements | `php artisan db:seed --class=AnnouncementSeeder` |
| Messages & Broadcasts | `php artisan db:seed --class=MessagesAndBroadcastsSeeder` |
| Billing (meters, readings, invoices) | `php artisan db:seed --class=BillingSeeder` |
| Management Settings | `php artisan db:seed --class=ManagementSettingsSeeder` |

---

## Seeded Accounts

All passwords are `password`.

| Email | Role | Purpose |
|-------|------|---------|
| `chairman@example.com` | OSBBRepresentative | Primary admin / chairman |
| `manager@example.com` | OSBBRepresentative | Building manager |
| `coordinator@example.com` | OSBBRepresentative | Maintenance coordinator |
| `owner1@example.com` | ApartmentOwner | Owner — apt data |
| `owner2@example.com` | ApartmentOwner | Owner — apt data |
| `owner3@example.com` | ApartmentOwner | Owner — apt data |
| `owner4@example.com` | ApartmentOwner | Owner — apt data |
| `owner5@example.com` | ApartmentOwner | Owner — apt data |
| `tenant1@example.com` | Tenant | Tenant — messages, requests |
| `tenant2@example.com` | Tenant | Tenant — messages, requests |
| `tenant3@example.com` | Tenant | Tenant — messages, requests |
| `tenant4@example.com` | Tenant | Tenant — messages, requests |

---

## What Each Seeder Creates

### AdminUsersSeeder
12 users: 3 OSBBRepresentative (staff), 5 ApartmentOwner, 4 Tenant. All active.

### HousingSeeder
3 buildings × 4 entrances × 27 apartments = **324 apartments**.  
Each entrance is assigned a responsible staff member. Owners and tenants are linked to apartments.

### ProfilesAndActivitiesSeeder
- `user_profiles` with phone numbers for every user.
- `workload_stats` for staff users (max capacity 25).
- 4 activity records per user (staff activities or resident activities).

### RequestsSeeder
Up to 3 requests per apartment (first 40 apartments → ~120 requests).  
Statuses: `new`, `in_progress`, `resolved`, `closed`. Priorities vary.  
Staff members are assigned as `assignee_id`. Resolved requests have a `resolved_at` timestamp for avg response time calculation.

### AnnouncementSeeder
9 announcements covering types: `general`, `maintenance`, `events`, `financial`, `safety`, `updates`.  
Statuses: `published`, `scheduled`, `draft`.

### MessagesAndBroadcastsSeeder
5 direct conversations between the chairman and residents (noise complaint, maintenance, parking, billing, lighting).  
1 group conversation between staff members.  
1 broadcast to Building A residents.

### BillingSeeder
Per apartment: 3 utility meters (water, electricity, gas) with 6 months of readings + 6 months of invoices (paid/unpaid/overdue).

### ManagementSettingsSeeder
11 settings across groups: `general`, `billing`, `notifications`, `announcements`.

---

## Admin Panel API Endpoints

All management endpoints require a Bearer token (`Authorization: Bearer <token>` from `POST /api/auth/login`).

| Page | Frontend Route | Backend Endpoint |
|------|---------------|------------------|
| News & Announcements | `/management/news` | `GET/POST/PUT/DELETE /api/management/announcements` |
| Requests/Messages | `/management/requests` | `GET /api/management/messages`, `/api/management/conversations` |
| Task Load Overview | `/management/tasks` | `GET /api/management/tasks/statistics?period=Week` |
| Buildings & Apartments | `/management/buildings` | `GET /api/management/buildings`, `/api/management/buildings/:id/entrances`, `/api/management/entrances/:id/apartments` |
| User Profile | `/management/profile` | `GET/PUT /api/management/profile` |
| Analytics | `/management/analytics` | `GET /api/management/analytics` |
| Members Directory | `/management/members` | `GET /api/management/members` |
| Settings | `/management/settings` | `GET /api/management/settings`, `PUT /api/management/settings` |

> **Note:** Management routes use the `/api/management/...` prefix and require a Bearer token (see table above).

### Legacy seeders (not used)

`ActivitySeeder` and `ConversationSeeder` in `database/seeders/` are **not** called from `AdminPanelSeeder`. Use **ProfilesAndActivitiesSeeder** (activities) and **MessagesAndBroadcastsSeeder** (conversations/messages) for panel testing. The legacy files are kept for reference only; running them manually may duplicate or conflict with seeded data.

---

## Verification Steps

After running `php artisan migrate:fresh --seed`:

1. **Login** as `chairman@example.com` / `password`
2. **News page** (`/management/news`) — should show 9 announcements with mixed statuses.
3. **Requests page** (`/management/requests`) — should show 5+ direct threads in the inbox.
4. **Task Load page** (`/management/tasks`) — should show real request counts per period and 3 staff workload rows.
5. **Buildings page** (`/management/buildings`) — should show 3 buildings, 4 entrances each, 27 apartments per entrance.
6. **Profile page** (`/management/profile`) — should show chairman's name, contact info, and activity history.
7. **Analytics page** (`/management/analytics`) — summary cards should show DB totals instead of zeros.
8. **Members page** (`/management/members`) — should list all 12 seeded users with role tags.
9. **Settings page** (`/management/settings`) — should show 11 settings grouped by category with editable inputs.
