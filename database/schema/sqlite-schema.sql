CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "avatar" varchar,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "last_login_at" datetime,
  "last_login_ip" varchar,
  "profile_photo_path" varchar
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_resets"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime
);
CREATE INDEX "password_resets_email_index" on "password_resets"("email");
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "personal_access_tokens"(
  "id" integer primary key autoincrement not null,
  "tokenable_type" varchar not null,
  "tokenable_id" integer not null,
  "name" varchar not null,
  "token" varchar not null,
  "abilities" text,
  "last_used_at" datetime,
  "expires_at" datetime,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" on "personal_access_tokens"(
  "tokenable_type",
  "tokenable_id"
);
CREATE UNIQUE INDEX "personal_access_tokens_token_unique" on "personal_access_tokens"(
  "token"
);
CREATE TABLE IF NOT EXISTS "addresses"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "address_line_1" varchar not null,
  "address_line_2" varchar,
  "city" varchar not null,
  "postal_code" varchar not null,
  "state" varchar not null,
  "country" varchar not null,
  "type" integer not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "permissions"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "guard_name" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "permissions_name_guard_name_unique" on "permissions"(
  "name",
  "guard_name"
);
CREATE TABLE IF NOT EXISTS "roles"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "guard_name" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "roles_name_guard_name_unique" on "roles"(
  "name",
  "guard_name"
);
CREATE TABLE IF NOT EXISTS "model_has_permissions"(
  "permission_id" integer not null,
  "model_type" varchar not null,
  "model_id" integer not null,
  foreign key("permission_id") references "permissions"("id") on delete cascade,
  primary key("permission_id", "model_id", "model_type")
);
CREATE INDEX "model_has_permissions_model_id_model_type_index" on "model_has_permissions"(
  "model_id",
  "model_type"
);
CREATE TABLE IF NOT EXISTS "model_has_roles"(
  "role_id" integer not null,
  "model_type" varchar not null,
  "model_id" integer not null,
  foreign key("role_id") references "roles"("id") on delete cascade,
  primary key("role_id", "model_id", "model_type")
);
CREATE INDEX "model_has_roles_model_id_model_type_index" on "model_has_roles"(
  "model_id",
  "model_type"
);
CREATE TABLE IF NOT EXISTS "role_has_permissions"(
  "permission_id" integer not null,
  "role_id" integer not null,
  foreign key("permission_id") references "permissions"("id") on delete cascade,
  foreign key("role_id") references "roles"("id") on delete cascade,
  primary key("permission_id", "role_id")
);
CREATE TABLE IF NOT EXISTS "tds"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "section" varchar,
  "rate" numeric not null,
  "threshold_limit" numeric,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "contact_groups"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "user_id" integer,
  "is_default" tinyint(1) not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete set null
);
CREATE TABLE IF NOT EXISTS "activity_logs"(
  "id" integer primary key autoincrement not null,
  "user_id" integer,
  "type" varchar not null,
  "module" varchar not null,
  "action" varchar not null,
  "subject_type" varchar,
  "subject_id" integer,
  "properties" text,
  "duration" integer,
  "started_at" datetime,
  "ended_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete set null
);
CREATE TABLE IF NOT EXISTS "currencies"(
  "id" integer primary key autoincrement not null,
  "code" varchar not null,
  "name" varchar not null,
  "symbol" varchar,
  "exchange_rate" numeric not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "currencies_code_unique" on "currencies"("code");
CREATE TABLE IF NOT EXISTS "leads"(
  "id" integer primary key autoincrement not null,
  "owner_id" integer not null,
  "first_name" varchar not null,
  "last_name" varchar not null,
  "email" varchar,
  "phone" varchar,
  "company" varchar,
  "status" varchar not null default 'New',
  "source" varchar,
  "notes" text,
  "inquiry_about" varchar,
  "enquiry_number" integer not null,
  "street" varchar,
  "city" varchar,
  "state" varchar,
  "country" varchar,
  "zip_code" varchar,
  "follow_ups" text,
  "meetings" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("owner_id") references "users"("id") on delete cascade
);
CREATE UNIQUE INDEX "leads_enquiry_number_unique" on "leads"("enquiry_number");
CREATE TABLE IF NOT EXISTS "contacts"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "company_name" varchar,
  "email" varchar,
  "phone" varchar,
  "pan" varchar,
  "gst" varchar,
  "msme_registration_id" varchar,
  "opening_balance" numeric not null default '0',
  "payment_terms" integer not null default '0',
  "documents" text,
  "addresses" text,
  "contact_type" varchar check("contact_type" in('individual', 'company')) not null,
  "is_customer" tinyint(1) not null default '0',
  "is_vendor" tinyint(1) not null default '0',
  "user_id" integer,
  "tds_id" integer,
  "bank_details" text,
  "upi_id" varchar,
  "is_portal_enabled" tinyint(1) not null default '0',
  "portal_password" varchar,
  "contact_group_id" integer,
  "lead_id" integer,
  "credit_limit" numeric,
  "contact_persons" text,
  "default_currency" varchar,
  "website" varchar,
  "notes" text,
  "status" varchar check("status" in('active', 'inactive')) not null default 'active',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete set null,
  foreign key("tds_id") references "tds"("id") on delete set null,
  foreign key("contact_group_id") references "contact_groups"("id") on delete set null,
  foreign key("lead_id") references "leads"("id") on delete set null
);
CREATE TABLE IF NOT EXISTS "project_types"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "stages" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "custom_field_definitions"(
  "id" integer primary key autoincrement not null,
  "module" varchar not null,
  "label" varchar not null,
  "name" varchar not null,
  "type" varchar not null,
  "options" text,
  "is_required" tinyint(1) not null default '0',
  "is_visible_in_table" tinyint(1) not null default '0',
  "order" integer not null default '0',
  "created_by" integer,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("created_by") references "users"("id") on delete set null
);

INSERT INTO migrations VALUES(1,'2014_10_12_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO migrations VALUES(3,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO migrations VALUES(4,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO migrations VALUES(5,'2023_05_28_090500_add_login_fields_to_users_table',1);
INSERT INTO migrations VALUES(6,'2023_06_12_013333_add_profile_photo_path_column_to_users_table',1);
INSERT INTO migrations VALUES(7,'2023_10_09_041104_create_addresses_table',1);
INSERT INTO migrations VALUES(8,'2024_07_01_100049_create_permission_tables',1);
INSERT INTO migrations VALUES(10,'2025_05_16_100416_create_tds_table',1);
INSERT INTO migrations VALUES(11,'2025_05_16_100552_create_contact_groups_table',1);
INSERT INTO migrations VALUES(12,'2025_05_16_131052_create_activity_logs_table',2);
INSERT INTO migrations VALUES(14,'2025_05_19_165312_create_currencies_table',3);
INSERT INTO migrations VALUES(15,'2025_05_19_205225_create_leads_table',4);
INSERT INTO migrations VALUES(16,'2025_05_16_100249_create_contacts_table',5);
INSERT INTO migrations VALUES(18,'2025_05_19_234215_create_project_types_table',6);
INSERT INTO migrations VALUES(19,'2025_05_23_133229_create_custom_field_definitions_table',7);
