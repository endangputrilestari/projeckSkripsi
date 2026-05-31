# Deploy to Vercel Serverless

This project is a CodeIgniter 3 PHP application adapted to run behind the
community `vercel-php` runtime.

## Project Root

Deploy from this directory:

```powershell
D:\Recruitment Management System (1)\Recruitment Management System
```

The Vercel function entrypoint is:

```text
api/index.php
```

The original app remains in:

```text
script/
```

`script/vendor` is intentionally included in the Vercel upload because the
Composer project lives inside `script/`, not the repository root.

## Required Vercel Environment Variables

Set these in Vercel Project Settings or with `vercel env add`:

```text
CF_BASE_URL=https://your-domain.vercel.app
CF_DB_HOST=your-mysql-host
CF_DB_NAME=your-database
CF_DB_USER=your-database-user
CF_DB_PASSWORD=your-database-password
CF_DB_PORT=3306
CF_DB_PREFIX=
CF_DB_TYPE=mysqli
CF_VIEW=beta
CF_DEMO=false
CI_ENV=production
```

Optional session overrides:

```text
CF_SESSION_DRIVER=database
CF_SESSION_SAVE_PATH=ci_sessions
```

## Database Session Table

Serverless deployments cannot use local file sessions. The app now defaults to
CodeIgniter database sessions on Vercel.

If you install through the app schema flow, the `ci_sessions` table is created
by `SchemaModel`. If you already have an existing database, run this SQL once:

```sql
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`),
  PRIMARY KEY (`id`)
);
```

If you use `CF_DB_PREFIX`, create the table with that prefix or let the schema
installer create it.

## Deploy

```powershell
vercel
vercel --prod
```

If `vercel` is not available in your shell, install/use the CLI from the same
terminal:

```powershell
npm i -g vercel
vercel login
```

## Important Storage Limitation

Vercel serverless functions do not provide persistent project filesystem
writes. This app still has runtime writes for:

- candidate/user/blog/department/question/identity image uploads under
  `script/assets/images`
- generated resume files
- generated language files
- generated `custom-style.css` and `lang.js`

The app can deploy and serve requests, but those write-heavy features need an
external storage migration for production. Use S3, Cloudflare R2, Supabase
Storage, or Cloudinary, then replace local upload/write calls that use
`ASSET_ROOT`.
