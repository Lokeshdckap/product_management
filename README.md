Product Management System

Project Overview

Overview:

A Laravel 12–based Product Management System designed to demonstrate clean architecture, multi-authentication, real-time WebSocket presence, and scalable bulk import processing. The system supports Admin and Customer roles with strict route protection, real-time online/offline tracking, and optimized Excel-based product imports.

Features

Multi-authentication (Admin & Customer)

Product & Category CRUD

Real-time online/offline presence (WebSockets)

Bulk product import via Excel (Queued)

Import progress tracking & failed row export

Admin & Customer dashboards

Feature & unit testing

Clean, scalable architecture

Tech Stack

Backend: Laravel 12 (PHP 8+)

Database: MySQL

Authentication: Laravel Multi-Auth (Guards)

Queues: Laravel Queue Worker

WebSockets: Laravel Echo + Pusher Protocol

Excel Import: maatwebsite/excel

Testing: PHPUnit (Feature & Unit Tests)

Frontend: Blade / Vite (minimal UI)

Authentication & Authorization

Explain how access is controlled.

Separate guards for:

admin

customer

Route-level protection using middleware

Admins cannot access customer routes and vice versa

Separate login & logout flows

Configured in:

config/auth.php
routes/admin.php
routes/customer.php

Real-Time Presence (WebSockets)

Purpose

Display accurate real-time online/offline status

Avoid polling-based solutions

Channels
Channel	Type	Description
presence-admins	Presence	Track admin presence
presence-customers	Presence	Track customer presence
private-customer-monitor	Private	Admin listens to customer events

Design Rule:
Admins do not join customer presence channels. Presence channels do not support read-only listeners.

Bulk Product Import

Implementation

Queued Excel import using maatwebsite/excel

Chunk reading + batch inserts

Row-level validation using OnEachRow

Performance

Chunk size: 1000

Batch size: 1000

Queue-based processing to avoid timeouts

Failed Rows

Stored as CSV files

Path: storage/app/imports/failed/failed_{import_id}.csv


Import Status

pending

completed

completed_with_errors

Sample Import File

storage/app/sample/products_sample_import.csv


Contains:

Valid rows

Invalid rows (for validation testing)

Installation & Setup

git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
composer install
npm install
npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
php artisan queue:work

Testing

php artisan test


Coverage

Guest access restrictions

Admin route access

Import logic validation

Authentication flows

Uses MySQL, not SQLite
Configured via .env.testing

Architectural Decisions

Show why your solution is good.

Multi-auth guards instead of role-based tables for clarity

Presence channels instead of polling for real-time accuracy

Queued imports to prevent request timeouts

Chunked processing for memory efficiency

Separate dashboards per user type

⚡ Performance Considerations

Chunked Excel processing

Batch DB inserts

Indexed foreign keys

Minimal WebSocket payloads

Conclusion

This project demonstrates:

Real-time systems with WebSockets

Scalable bulk data processing

Clean Laravel architecture

Strong access control and testing discipline