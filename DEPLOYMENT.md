# Backend Deployment — Laravel API on Hostinger

CI/CD for the Laravel 13 API. The frontend is handled separately on Vercel; this
repo deploys **only** the backend to **Hostinger Premium** (shared, PHP 8.3 +
MariaDB 11) over SSH.

| Branch | Workflow | Deploys to |
| --- | --- | --- |
| `main` | `deploy-production.yml` | `https://api.emcey-brows-aesthetics.com` |
| `staging` | `deploy-staging.yml` | `https://staging-api.emcey-brows-aesthetics.com` |

Pull requests run the **test suite only** — no deploy.

## How the pipeline works

Staging and production are **separate workflow files** so each is self-contained
and only its own branch can trigger it:

- **`tests.yml`** — PHP 8.3 + a **MariaDB 11** service (matches Hostinger prod),
  `composer install`, `php artisan test`. Runs on every PR, and is called by
  both deploy workflows before they ship (`jobs.test.uses: ./…/tests.yml`), so
  the test logic lives in exactly one place.
- **`deploy-staging.yml`** (push to `staging`) / **`deploy-production.yml`**
  (push to `main`): after tests pass, each:
  1. Builds `vendor/` (`composer install --no-dev`) + assets (`npm run build`)
     **on the GitHub runner** — the shared host never runs composer/npm, which
     avoids its memory limits.
  2. `rsync`s the built app to its own `DEPLOY_PATH`, **excluding** `.env`,
     `storage/`, `public/storage`, `node_modules/`, `.git/` — so server secrets
     and uploads are never overwritten.
  3. Runs `migrate --force` + config/route/view/event caching over SSH.
  4. Smoke-tests `GET /api/v1/services?featured=1` (fails the run if not `200`).

Each deploy file **hardcodes its own** `DEPLOY_PATH` + `APP_URL` (not secrets —
just paths), so the two environments can never cross-contaminate. Only the SSH
connection details are shared secrets.

---

## One-time setup

### 1. Hostinger — subdomains & document roots  ✅ DONE

Both subdomains exist. Because hPanel fixes a subdomain's document root at
`public_html/<name>` (not editable), the Laravel apps live **outside** the web
root and each docroot is a **symlink** to the app's `public/` folder — this also
keeps `.env`/`vendor` unreachable from the web:

| Subdomain | Docroot (symlink) | → App dir (`DEPLOY_PATH`) |
| --- | --- | --- |
| `api.emcey-brows-aesthetics.com` | `.../public_html/api` | `/home/u279697774/laravel/api-prod` |
| `staging-api.emcey-brows-aesthetics.com` | `.../public_html/staging-api` | `/home/u279697774/laravel/api-staging` |

Storage skeleton + `bootstrap/cache` are pre-created (rsync excludes them so live
logs/sessions survive deploys). PHP is **8.3.30**. SSL is active on both.

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

The `.env` files are already created at `~/laravel/api-prod/.env` and
`~/laravel/api-staging/.env` with a generated `APP_KEY`. Only the DB credentials
(`DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD`, currently `FILL_ME`) need filling
once the databases exist:

```bash
nano ~/laravel/api-prod/.env       # replace the three FILL_ME values
nano ~/laravel/api-staging/.env
```

Make sure `storage/` and `bootstrap/cache/` are writable:

```bash
chmod -R 775 storage bootstrap/cache
```

### 5. GitHub — repo & secrets

Push this folder to its own GitHub repo (see below). The two deploy paths and
URLs are **hardcoded in the workflow files** (not secret), so you only add the
**4 shared SSH secrets, once, at the repo level** — Settings → Secrets and
variables → Actions → *New repository secret*:

| Secret | Value |
| --- | --- |
| `SSH_HOST` | `145.79.28.130` |
| `SSH_PORT` | `65002` |
| `SSH_USER` | `u279697774` |
| `SSH_PRIVATE_KEY` | full contents of the `emcey_deploy` private key (incl. the `-----BEGIN/END-----` lines) |

**Optional — production approval gate:** Settings → Environments → create
`production` → add yourself as a *Required reviewer*. Then every prod deploy
pauses for your one-click approval. (`staging` needs no environment config.)

> `PHP_BIN` is **not** needed — this account's `php` is already 8.3.30.

### 6. DNS & SSL — ✅ DONE

Subdomains resolve and **SSL is already active** on both `api.` and
`staging-api.` (auto-issued by Hostinger). Nothing to do here.

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
cd ~/laravel/api-prod
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
