# Hotel Management System (HMS) - Product Requirements Document

---

## 1. Overview

### 1.1 Tujuan

Membangun sistem Hotel Management System (HMS) berbasis web untuk mengelola operasional hotel secara end-to-end, mencakup reservasi, manajemen kamar, check-in/out, housekeeping, billing, dan reporting.

### 1.2 Target User

* Front Office Staff
* Admin / Manager
* Housekeeping
* Finance

### 1.3 Tech Stack

* Frontend: Laravel Livewire + TailwindCSS
* Backend: Laravel
* Database: MySQL
* Auth: Laravel Sanctum (opsional)
* Architecture: Modular Monolith

---

## 2. Scope (Version 1)

### Core Features

* Room Management
* Booking / Reservation
* Guest Management
* Check-in / Check-out
* Payment & Billing
* Dashboard & Reporting

### Advanced Features (Included in V1)

* Audit Log
* Multi-Branch
* Housekeeping Scheduling
* Role & Permission (RBAC)
* Activity Tracking
* Invoice System
* Soft Delete

---

## 3. Modules

### 3.1 Authentication & Authorization

Roles:

* Admin
* Front Office
* Housekeeping
* Manager

---

### 3.2 Room Management

**Table: rooms**

| Field        | Type    |
| ------------ | ------- |
| id           | bigint  |
| room_number  | varchar |
| room_type_id | FK      |
| branch_id    | FK      |
| status       | enum    |
| price        | decimal |
| floor        | int     |
| notes        | text    |

**Status:**

* available
* reserved
* occupied
* cleaning
* maintenance

---

### 3.3 Room Type

**Table: room_types**

| Field       | Type    |
| ----------- | ------- |
| id          | bigint  |
| name        | varchar |
| capacity    | int     |
| base_price  | decimal |
| description | text    |

---

### 3.4 Guest

**Table: guests**

| Field           | Type    |
| --------------- | ------- |
| id              | bigint  |
| name            | varchar |
| email           | varchar |
| phone           | varchar |
| identity_number | varchar |
| address         | text    |

---

### 3.5 Booking

**Table: bookings**

| Field          | Type    |
| -------------- | ------- |
| id             | bigint  |
| booking_code   | varchar |
| guest_id       | FK      |
| room_id        | FK      |
| check_in_date  | date    |
| check_out_date | date    |
| status         | enum    |
| total_price    | decimal |

**Status:**

* pending
* confirmed
* checked_in
* checked_out
* cancelled

---

### 3.6 Payment

**Table: payments**

| Field      | Type    |
| ---------- | ------- |
| id         | bigint  |
| booking_id | FK      |
| amount     | decimal |
| method     | enum    |
| status     | enum    |

**Method:**

* cash
* transfer
* e-wallet

**Status:**

* pending
* paid
* failed

---

### 3.7 Housekeeping

**Table: housekeeping_tasks**

| Field         | Type   |
| ------------- | ------ |
| id            | bigint |
| room_id       | FK     |
| staff_id      | FK     |
| schedule_date | date   |
| status        | enum   |

**Status:**

* scheduled
* in_progress
* completed

---

### 3.8 Branch

**Table: branches**

| Field   | Type    |
| ------- | ------- |
| id      | bigint  |
| name    | varchar |
| address | text    |

---

### 3.9 Audit Log

**Table: audit_logs**

| Field       | Type      |
| ----------- | --------- |
| id          | bigint    |
| user_id     | FK        |
| action      | varchar   |
| entity_type | varchar   |
| entity_id   | bigint    |
| old_value   | json      |
| new_value   | json      |
| created_at  | timestamp |

---

## 4. State Machine

### Booking Flow

```
pending → confirmed → checked_in → checked_out
            ↓
        cancelled
```

### Room Flow

```
available → reserved → occupied → cleaning → available
                          ↓
                     maintenance
```

### Housekeeping Flow

```
scheduled → in_progress → completed
```

---

## 5. API Endpoint Outline

### Auth

```
POST /api/login
POST /api/logout
```

### Rooms

```
GET    /api/rooms
POST   /api/rooms
PUT    /api/rooms/{id}
DELETE /api/rooms/{id}
```

### Bookings

```
GET    /api/bookings
POST   /api/bookings
PUT    /api/bookings/{id}
POST   /api/bookings/{id}/checkin
POST   /api/bookings/{id}/checkout
```

### Payments

```
POST /api/payments
GET  /api/payments/{booking_id}
```

### Housekeeping

```
GET /api/housekeeping
POST /api/housekeeping
PUT /api/housekeeping/{id}
```

### Audit Logs

```
GET /api/audit-logs
```

---

## 6. Dashboard

Metrics:

* Total Rooms
* Occupancy Rate
* Revenue
* Active Bookings
* Room Status Distribution

---

## 7. Business Rules

* Booking harus confirmed sebelum check-in
* Tidak boleh double booking
* Check-out otomatis trigger housekeeping
* Semua perubahan tercatat di audit log
* Semua data scoped by branch_id

---

## 8. Validation Rules

* check_out_date > check_in_date
* room harus available saat booking
* payment >= total_price untuk status paid

---

## 9. Security

* RBAC middleware
* Audit logging
* Soft delete
* Validation (Laravel FormRequest)

---

## 10. Testing

* Unit Test
* Feature Test
* API Test
* Permission Test

---

## 11. Deployment

* VPS / Cloud
* Daily DB Backup

---

## 12. Future Enhancement

* OTA Integration
* Mobile App
* AI Pricing
* Notification System

---

## Final Notes

* Frontend menggunakan Livewire (bukan React SPA)
* PRD ini siap digunakan sebagai acuan development
* Sudah mencakup struktur database, state machine, dan API outline

---
