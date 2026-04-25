# IRent Database Schema

**Default connection:** `sqlite` (config: `config/database.php`, env: `DB_CONNECTION`)

**Connections available:** `sqlite`, `mysql`, `mariadb`, `pgsql`, `sqlsrv`

---

## Tables, Attributes & Relationships

### 1. **users**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| first_name | string | |
| last_name | string | |
| email | string | unique |
| email_verified_at | timestamp | nullable |
| password | string | |
| verification_token | string | nullable |
| is_active | boolean | default false |
| remember_token | string | nullable |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

**Model:** `User`. **Relations:** roles (BelongsToMany role_user), profile (HasOne user_profiles), activities, announcements, responsibleEntrances (BelongsToMany entrance_user), assignedRequests (Request assignee_id), ownedApartments (Apartment owner_id), rentedApartments (Apartment tenant_id), createdRequests (Request creator_id), sentMessages, conversations (BelongsToMany conversation_participants).

---

### 2. **password_reset_tokens**
| Column | Type | Attributes |
|--------|------|-------------|
| email | string | PK |
| token | string | |
| created_at | timestamp | nullable |

---

### 3. **sessions**
| Column | Type | Attributes |
|--------|------|-------------|
| id | string | PK |
| user_id | bigint | nullable, FK → users.id |
| ip_address | string(45) | nullable |
| user_agent | text | nullable |
| payload | longText | |
| last_activity | integer | index |

---

### 4. **roles**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| name | string | unique |
| label | string | |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

**Model:** `Role`. **Relations:** users (BelongsToMany via role_user).

---

### 5. **role_user** (pivot)
| Column | Type | Attributes |
|--------|------|-------------|
| user_id | bigint | FK → users.id, CASCADE |
| role_id | bigint | FK → roles.id, CASCADE |
| (primary) | | PK (user_id, role_id) |

---

### 6. **personal_access_tokens**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| tokenable_type | string | morphs |
| tokenable_id | bigint | morphs |
| name | text | |
| token | string(64) | unique |
| abilities | text | nullable |
| last_used_at | timestamp | nullable |
| expires_at | timestamp | nullable, index |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

---

### 7. **cache**
| Column | Type | Attributes |
|--------|------|-------------|
| key | string | PK |
| value | mediumText | |
| expiration | integer | |

---

### 8. **cache_locks**
| Column | Type | Attributes |
|--------|------|-------------|
| key | string | PK |
| owner | string | |
| expiration | integer | |

---

### 9. **jobs**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| queue | string | index |
| payload | longText | |
| attempts | unsignedTinyInteger | |
| reserved_at | unsignedInteger | nullable |
| available_at | unsignedInteger | |
| created_at | unsignedInteger | |

---

### 10. **job_batches**
| Column | Type | Attributes |
|--------|------|-------------|
| id | string | PK |
| name | string | |
| total_jobs | integer | |
| pending_jobs | integer | |
| failed_jobs | integer | |
| failed_job_ids | longText | |
| options | mediumText | nullable |
| cancelled_at | integer | nullable |
| created_at | integer | |
| finished_at | integer | nullable |

---

### 11. **failed_jobs**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| uuid | string | unique |
| connection | text | |
| queue | text | |
| payload | longText | |
| exception | longText | |
| failed_at | timestamp | |

---

### 12. **buildings**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| address | string | |
| city | string | default 'Kyiv' |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

**Relations:** entrances (HasMany). *Note: No `Building` model in app/Models; Entrance belongs to Building.*

---

### 13. **entrances**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| building_id | bigint | FK → buildings.id, CASCADE |
| name | string | |
| code | string | nullable |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

**Model:** `Entrance`. **Relations:** building (BelongsTo Building), apartments (HasMany), responsibleStaff (BelongsToMany User via entrance_user).

---

### 14. **apartments**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| entrance_id | bigint | FK → entrances.id, CASCADE |
| number | string | |
| floor | integer | nullable |
| area | float | nullable |
| owner_id | bigint | nullable, FK → users.id, NULL ON DELETE |
| tenant_id | bigint | nullable, FK → users.id, NULL ON DELETE |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

**Model:** `Apartment`. **Relations:** entrance (BelongsTo Entrance), owner (BelongsTo User), tenant (BelongsTo User), requests (HasMany Request), meters (HasMany UtilityMeter), invoices (HasMany Invoice), currentInvoice (HasOne Invoice unpaid).

---

### 15. **entrance_user** (pivot)
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| entrance_id | bigint | FK → entrances.id, CASCADE |
| user_id | bigint | FK → users.id, CASCADE |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

---

### 16. **user_profiles**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| user_id | bigint | FK → users.id, CASCADE |
| phone | string | nullable |
| avatar_url | string | nullable |
| bio | text | nullable |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

**Model:** `UserProfile`. **Relations:** user (BelongsTo User), workloadStats (HasOne WorkloadStats).

---

### 17. **workload_stats**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| user_profile_id | bigint | FK → user_profiles.id, CASCADE |
| active_requests_count | integer | default 0 |
| resolved_requests_count | integer | default 0 |
| avg_response_time | float | default 0.0 |
| max_capacity | integer | default 10 |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

**Model:** `WorkloadStats`. **Relations:** userProfile (BelongsTo UserProfile).

---

### 18. **activities**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| user_id | bigint | FK → users.id, CASCADE |
| action | string | |
| subject | string | nullable |
| meta_data | json | nullable |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

**Relations:** user (BelongsTo User). *Note: Referenced in User model as Activity::class; ensure Activity model exists.*

---

### 19. **announcements**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| user_id | bigint | FK → users.id, CASCADE |
| title | string | |
| content | text | |
| type | enum | 'general','maintenance','events','financial','safety','updates' — default 'general' |
| status | enum | 'draft','published','scheduled' — default 'draft' |
| scheduled_for | timestamp | nullable |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

**Model:** `Announcement`. **Relations:** user (BelongsTo User).

---

### 20. **requests**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| title | string | |
| description | text | |
| status | enum | 'new','in_progress','resolved','closed' — default 'new' |
| priority | enum | 'low','medium','high','critical' — default 'medium' |
| apartment_id | bigint | FK → apartments.id, CASCADE |
| creator_id | bigint | FK → users.id |
| assignee_id | bigint | nullable, FK → users.id |
| resolved_at | timestamp | nullable |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

**Model:** `Request`. **Relations:** apartment (BelongsTo Apartment), creator (BelongsTo User), assignee (BelongsTo User).

---

### 21. **conversations**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| type | enum | 'direct','group','broadcast' — default 'direct' |
| category | enum | 'general','suggestions_complaints','legal','maintenance','financial' — default 'general' |
| subject | string | nullable |
| last_message_at | timestamp | |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

**Model:** `Conversation`. **Relations:** participants (BelongsToMany User via conversation_participants), messages (HasMany Message).

---

### 22. **conversation_participants** (pivot)
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| conversation_id | bigint | FK → conversations.id, CASCADE |
| user_id | bigint | FK → users.id, CASCADE |
| last_read_at | timestamp | nullable |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |
| (unique) | | (conversation_id, user_id) |

---

### 23. **messages**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| conversation_id | bigint | FK → conversations.id, CASCADE |
| user_id | bigint | FK → users.id, CASCADE |
| body | text | |
| attachment_url | string | nullable |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

**Model:** `Message`. **Relations:** conversation (BelongsTo Conversation), user (BelongsTo User).

---

### 24. **utility_meters**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| apartment_id | bigint | FK → apartments.id, CASCADE |
| type | enum | 'water','electricity','gas','heating' |
| serial_number | string | nullable |
| unit | string | default 'm3' |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

**Model:** `UtilityMeter`. **Relations:** apartment (BelongsTo Apartment), readings (HasMany MeterReading).

---

### 25. **meter_readings**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| utility_meter_id | bigint | FK → utility_meters.id, CASCADE |
| value | decimal(10,2) | |
| reading_date | date | |
| status | enum | 'pending','verified' — default 'pending' |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

**Model:** `MeterReading`. **Relations:** utilityMeter (BelongsTo UtilityMeter).

---

### 26. **invoices**
| Column | Type | Attributes |
|--------|------|-------------|
| id | bigint | PK, auto increment |
| apartment_id | bigint | FK → apartments.id, CASCADE |
| amount | decimal(10,2) | |
| due_date | date | |
| paid_at | date | nullable |
| status | enum | 'unpaid','paid','overdue' — default 'unpaid' |
| created_at | timestamp | nullable |
| updated_at | timestamp | nullable |

**Model:** `Invoice`. **Relations:** apartment (BelongsTo Apartment).

---

## Entity relationship summary

- **users** ↔ **roles** (many-to-many: role_user)
- **users** ↔ **entrances** (many-to-many: entrance_user, “responsible staff”)
- **users** → **user_profiles** (one-to-one)
- **user_profiles** → **workload_stats** (one-to-one)
- **users** → **apartments** (owner_id, tenant_id)
- **buildings** → **entrances** → **apartments**
- **apartments** → **requests** (creator_id, assignee_id on users)
- **apartments** → **utility_meters** → **meter_readings**
- **apartments** → **invoices**
- **conversations** ↔ **users** (many-to-many: conversation_participants)
- **conversations** → **messages** (user_id = sender)
