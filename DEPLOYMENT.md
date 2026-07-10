# Backend Deployment — Laravel API on Hostinger

CI/CD for the Laravel 13 API. The frontend is handled separately on Vercel; this
repo deploys **only** the backend to **Hostinger Premium** (shared, PHP 8.3 +
MySQL 8) over SSH.

| Branch | Deploys to | URL |
| --- | --- | --- |
| `main` | Production | `https://api.emcey-brows-aesthetics.com` |
| `staging` | Staging | `https://staging-api.emcey-brows-aesthetics.com` |

Pull requests run the **test suite only** — no deploy.

## How the pipeline works (`.github/workflows/deploy.yml`)

1. **test** — PHP 8.3 + SQLite in-memory, `composer install`, `php artisan test`.
2. **deploy** (only on push to `main`/`staging`):
   - Builds `vendor/` (`composer install --no-dev`) and assets (`npm run build`)
     **on the GitHub runner** — the shared host never runs composer/npm, which
     avoids its memory limits.
   - `rsync` the built app to the server, **excluding** `.env`, `storage/`,
     `public/storage`, `node_modules/`, `.git/` — so server secrets and uploads
     are never overwritten.
   - Runs `migrate --force` + config/route/view/event caching over SSH.
   - Smoke-tests `GET /api/v1/services?featured=1` (fails the run if not `200`).

The `main`/`staging` split is driven by **GitHub Environments**: the deploy job
picks environment `production` or `staging`, and each environment supplies its
own secret values (different `DEPLOY_PATH`, and different DB via the server
`.env`). One workflow file, two isolated targets.

---

## One-time setup

### 1. Hostinger — subdomains & document roots

In **hPanel → Domains → Subdomains**:

| Subdomain | App folder (rsync target = `DEPLOY_PATH`) | Document root (set this in hPanel) |
| --- | --- | --- |
| `api` (already created) | `.../public_html/api-emcey-brows-aesthetics` | `.../public_html/api-emcey-brows-aesthetics/public` |
| `staging-api` (create it) | `.../public_html/staging-api-emcey-brows-aesthetics` | `.../public_html/staging-api-emcey-brows-aesthetics/public` |

(`...` = `/home/u279697774/domains/emcey-brows-aesthetics.com`)

> **Critical:** Laravel serves from its `public/` folder. For each subdomain,
> **edit the document root so it ends in `/public`**. If it points at the app
> root you'll get a 404 (or worse, expose source). hPanel auto-creates the app
> folder when you make the subdomain; the `/public` inside it appears on the
> first deploy.

PHP is already **8.3.30** on this account — no version change needed.

### 2. Hostinger — databases

In **hPanel → Databases → MySQL**, create two databases + users:

- `uXXXXXXXX_emcey_prod` (+ user `uXXXXXXXX_emcey`)
- `uXXXXXXXX_emcey_staging` (+ user `uXXXXXXXX_emcey_stg`)

Grant each user all privileges on its own DB. Note the exact names — Hostinger
prefixes them with your account id (`uXXXXXXXX_`).

### 3. Hostinger — SSH key

Enable SSH in **hPanel → Advanced → SSH Access** (note the **host**, **port** —
usually `65002` — and **username**, e.g. `u123456789`).

On your machine, create a deploy key and add the **public** half to Hostinger
(hPanel → SSH Access → *Manage SSH keys*):

```bash
ssh-keygen -t ed25519 -C "github-actions-emcey" -f emcey_deploy -N ""
# paste emcey_deploy.pub into hPanel; keep emcey_deploy (private) for GitHub
```

Test: `ssh -p 65002 -i emcey_deploy u123456789@<host>`

### 4. Put `.env` on the server (once per environment)

`.env` is **never** committed or uploaded. Create it directly on the server:

```bash
# production
cd ~/domains/emcey-brows-aesthetics.com/public_html/api-emcey-brows-aesthetics
nano .env          # paste from .env.production.example, fill DB + mail creds
php artisan key:generate

# staging
cd ~/domains/emcey-brows-aesthetics.com/public_html/staging-api-emcey-brows-aesthetics
nano .env          # paste from .env.staging.example
php artisan key:generate
```

Make sure `storage/` and `bootstrap/cache/` are writable:

```bash
chmod -R 775 storage bootstrap/cache
```

### 5. GitHub — repo, secrets & environments

Push this folder to its own GitHub repo (see below), then in
**Settings → Environments** create **`production`** and **`staging`**. Add these
secrets **to each environment** (values differ per environment):

| Secret | production | staging |
| --- | --- | --- |
| `SSH_HOST` | `145.79.28.130` | `145.79.28.130` |
| `SSH_PORT` | `65002` | `65002` |
| `SSH_USER` | `u279697774` | `u279697774` |
| `SSH_PRIVATE_KEY` | contents of `emcey_deploy` | same |
| `DEPLOY_PATH` | `/home/u279697774/domains/emcey-brows-aesthetics.com/public_html/api-emcey-brows-aesthetics` | `/home/u279697774/domains/emcey-brows-aesthetics.com/public_html/staging-api-emcey-brows-aesthetics` |

> `PHP_BIN` is **not** needed — this account's `php` is already 8.3.30.

### 6. DNS (at Hostinger, since the domain is registered there)

**hPanel → Domains → DNS / Nameservers → DNS Records.** The frontend records
(apex + `www` → Vercel) are separate; for the API add:

| Type | Name | Value | Note |
| --- | --- | --- | --- |
| A | `api` | *Hostinger server IP* | production API |
| A | `staging-api` | *Hostinger server IP* | staging API |

Find the server IP in hPanel → Hosting → *Details*. Then issue SSL for both
subdomains in **hPanel → Security → SSL** (Let's Encrypt, free).

---

## Push the repo & branches

From `eye-brow-clinic-backend/`:

```bash
git init
git add .
git commit -m "Backend + CI/CD pipeline"
git branch -M main
git remote add origin git@github.com:<you>/emcey-brows-backend.git
git push -u origin main

# create the staging branch
git checkout -b staging
git push -u origin staging
```

## First deploy (seed the database once)

The workflow runs `migrate --force` automatically, but the **initial seed** is
manual (so it never re-seeds on every deploy). After the first successful
deploy, SSH in and run once per environment:

```bash
cd ~/domains/emcey-brows-aesthetics.com/public_html/api-emcey-brows-aesthetics
php artisan migrate --seed --force
```

## Day-to-day

- Work on a feature branch → open a PR → tests run.
- Merge/push to **`staging`** → auto-deploys to `staging-api…`. Verify there.
- Push/merge to **`main`** → auto-deploys to `api…` (production).

## Rollback

Re-deploy a known-good commit:

```bash
git checkout main
git reset --hard <good-commit-sha>
git push --force-with-lease origin main   # triggers a clean re-deploy
```

Migrations don't auto-rollback — take a DB export in hPanel before deploys that
include destructive migrations, and restore it if needed.

## Troubleshooting

| Symptom | Fix |
| --- | --- |
| `Permission denied (publickey)` in Actions | Public key not added in hPanel, or `SSH_PRIVATE_KEY` secret is truncated (paste the whole file incl. header/footer lines). |
| Deploy OK but site 500s | `.env` missing/incomplete on server, or `APP_KEY` empty → run `php artisan key:generate`. Check `storage/logs/laravel.log`. |
| `artisan: command not found` / wrong PHP | Set the `PHP_BIN` variable to the full PHP 8.3 path. |
| 404 at the subdomain | Document root isn't pointed at `.../public`. |
| Smoke test fails, HTTP 000 | DNS/SSL not finished propagating for the subdomain yet. |
