# BuscoPhylo

BuscoPhylo is a PHP/MySQL web application for BUSCO-based phylogenomic analyses. This repository contains source code only: no production database, accounts, uploads, BUSCO datasets, job results, logs, archives, or credentials are included.

## First-time installation

1. Install PHP 8.1+ with `pdo_mysql`, `mysqli`, `mbstring`, and `zip`, plus MySQL 8 or MariaDB 10.4+.
2. Clone the repository to a web-server document root. Let the web-server account write `db/` during installation and `admin/core/data/` for analysis jobs.
3. Create an empty database and a database user with full privileges on that database.
4. Visit `/install/`. The one-time installer validates the database, creates the starter tables, writes `db/config.php`, and creates the administrator account.
5. Remove write access to `db/` after installation. Sign in at `/login` with the credentials you chose.

The installer creates `install/.installed` after success. Do not remove that lock or commit `db/config.php`.

## Local development

```bash
git clone https://github.com/alaesa2001/BuscoPhylo.git
cd BuscoPhylo
php -S 127.0.0.1:8080
```

Open <http://127.0.0.1:8080/install/>. PHP's built-in server is only for local development.

## Production notes

The compute host needs BUSCO, MUSCLE, trimAl, IQ-TREE, Python, and BUSCO lineage data. Configure the legacy worker scripts in `admin/core/` for local tools and a job queue before accepting public jobs. Put the site behind HTTPS and enforce resource limits; it runs analysis jobs on the host.

## Security policy

- The installer creates the admin password using PHP `password_hash()`.
- `db/config.php` and all runtime data are ignored by Git.
- Configure SMTP from the administration area after installation; no mail password is committed.
- Do not commit `uploads/`, `admin/core/data/`, `admin/core/test_data/`, BUSCO datasets, logs, result ZIPs, or user avatars.

## Attribution

The workflow follows Sahbou et al. (2022), *BuscoPhylo: a webserver for Busco-based phylogenomic analysis for non-specialists*, Scientific Reports 12:17352. Original project: <https://github.com/alaesahbou/BuscoPhylo>.
