# Publishing to GitHub

A step-by-step guide for getting Nocturne onto GitHub, and for keeping it
growing afterwards.

---

## 1. Create the repository

### On GitHub

1. **New repository**
2. Name: `nocturne`
3. Description: `A luxury editorial dark WordPress theme for authors and publishers`
4. **Public** ✅
5. ⚠️ **Do NOT** tick "Add a README", `.gitignore` or a licence — you already
   have all three and it will cause a conflict.
6. **Create repository**

### Locally

```bash
cd /path/to/nocturne

git remote add origin https://github.com/YOUR-USERNAME/nocturne.git
git branch -M main
git push -u origin main
git push -u origin develop
git push --tags
```

You will need a **Personal Access Token** instead of your password:

- GitHub → **Settings** → **Developer settings** → **Personal access tokens**
- **Tokens (classic)** → **Generate new token**
- Scope: `repo`, `public_repo`, `workflow`, `gist`
- Copy it once — you cannot see it again

```bash
git remote set-url origin https://YOUR-USERNAME:YOUR_TOKEN@github.com/YOUR-USERNAME/nocturne.git
```

> 💡 Safer: use **GitHub CLI** instead.
> ```bash
> gh auth login
> gh repo create nocturne --public --source=. --remote=origin --push
> ```

---

## 2. Turn on the essentials

Once the repo is on GitHub, open **Settings** and set these.

### Pages — the live preview

**Settings → Pages → Build and deployment**
- Source: **GitHub Actions**

The `pages.yml` workflow will publish `preview/` to
`https://YOUR-USERNAME.github.io/nocturne/`.

### Branches

**Settings → Branches → Add rule**
- Branch name pattern: `main`
- ✅ Require a pull request before merging
- ✅ Require approvals: `1`
- ✅ Require branches to be up to date
- ✅ Require status checks to pass: *lint*, *build*, *security*, *style*

**Settings → Branches → Add rule** (second one)
- Branch name pattern: `develop`
- ✅ Require a pull request before merging

### Issues

**Settings → General → Issues** → ✅ Enable
**Settings → General → Issues → Templates** → point at `.github/ISSUE_TEMPLATE/`

### Discussions

**Settings → General → Features** → ✅ Enable Discussions

### Topics

Add these so people can find it:
`wordpress-theme`, `woocommerce`, `dark-theme`, `elementor`, `author-website`,
`bookstore`, `wordpress-child-theme`, `dark-romance`, `design-system`

### Social preview

**Settings → General → Social preview** → upload a 1280×630 image. The
`docs/screenshots/home-desktop.png` works well.

---

## 3. The branch model

```
main      ←  production. Only via PR + CI green. Tagged releases.
  │
  └── develop   ←  the workbench. PRs land here.
        │
        └── feature/xyz   ←  your branch
```

**The rule:** never commit directly to `main`.

### Your daily loop

```bash
git checkout develop
git pull

git checkout -b feature/dark-mode-toggle
# ... work ...
git add -A
git commit -m "feat: add a manual dark/light toggle"
git push -u origin feature/dark-mode-toggle
```

Then open a PR → `develop`. When it is reviewed and merged, `develop` gets
merged into `main` and tagged.

### Cutting a release

```bash
git checkout develop
git pull

# bump the version in BOTH places
#   template/style.css      →  Version: 1.1.0
#   template/functions.php  →  define( 'NOCTURNE_VERSION', '1.1.0' );

git commit -am "chore: bump version to 1.1.0"
git checkout main
git merge develop --no-ff -m "chore: release v1.1.0"
git tag -a v1.1.0 -m "Nocturne v1.1.0 — Gutenberg blocks"
git push origin main --tags

# GitHub Actions publishes the release with the theme zip attached
```

---

## 4. Keep it growing

### A weekly rhythm

| When | What |
|---|---|
| **Monday** | Pick one item from the roadmap. Open an issue for it. |
| **Tue–Thu** | Build it on a feature branch. Run the QA suite. |
| **Friday** | Open the PR. Write the changelog entry. |
| **Weekend** | Merge, tag, release, write a short blog post. |

That is how open-source projects actually stay alive.

### Writing good commits

```
feat: add a manual dark/light mode toggle

The site follows prefers-color-scheme by default, but a reader may want
to override it. This adds a toggle that persists to localStorage and
respects the admin's default.

Fixes #42
```

Types: `feat` `fix` `docs` `style` `refactor` `perf` `test` `chore` `build`

### Good issue titles

```
[Feature]: Reader typography controls (font, size, line height)
[Bug]: Hero shows a 12px gap at 320px on iPhone SE
[Docs]: The Stripe section needs a screenshot
```

### Good PR titles

```
feat: add Gutenberg blocks for the hero, book grid and post grid
```

Follow the template:

```markdown
## What does this change?
One paragraph.

## Why?
The problem it solves.

## Screenshots
Before / after — **especially a 375px mobile shot** if the hero changed.

## Checklist
- [ ] QA suite passes
- [ ] PHP lint clean
- [ ] Documentation updated
- [ ] CHANGELOG updated
```

---

## 5. Growing the audience

### WordPress.org (long game)

The theme can be submitted to the directory once v2.0 lands (standalone FSE
theme). Checklist for that:

- [ ] `readme.txt` in WordPress format
- [ ] `screenshot.png` at 1200×900
- [ ] Theme review guidelines met
- [ ] No trademarked names (no "Veloria", no "Calypso")
- [ ] GPL-2.0+ compatible, all assets licensed
- [ ] `theme.json` present
- [ ] No bundled paid plugins

### Showcase sites

Publish a live demo and link it from the README. The GitHub Pages preview
does this automatically.

### Content

Write about what you learned. The posts that get shared:

- *"How to make a WordPress hero slider truly edge-to-edge on mobile"*
- *"Designing a dark theme that stays readable"*
- *"What I got wrong building a WooCommerce book shop"*

Each one links back to the repo. This is how projects get discovered.

---

## 6. Keeping the repo healthy

### Dependabot

Already enabled by default for GitHub Actions. To add more:

**Settings → Advanced → Dependabot and updates → Configure**
- Package ecosystem: `github-actions`
- Directory: `/`
- ✅ Enable version updates

### Security

The `security` CI job runs **gitleaks** on every push. If it ever fires on
a real secret, treat it as compromised — rotate it immediately.

### Housekeeping

| Task | How often |
|---|---|
| Merge `develop` into `main` | Weekly |
| Review open PRs | Twice a week |
| Check dependency updates | Monthly |
| Refresh screenshots | With each release |
| Archive stale issues | Quarterly |

---

## 7. Command reference

```bash
# Setup
git remote add origin https://github.com/YOU/nocturne.git
git push -u origin main develop --tags

# Daily
git checkout develop && git pull
git checkout -b feature/thing
# work
git add -A && git commit -m "feat: thing"
git push -u origin feature/thing

# Release
bash scripts/build-theme.sh
git tag -a v1.1.0 -m "Release v1.1.0"
git push origin main develop --tags

# Test
cd preview && python3 -m http.server 8080 &
NOCTURNE_BASE=http://localhost:8080 python3 scripts/test_theme.py
```

---

<div align="center">

**Ship something small every week.**
That is the entire secret to a repository that stays alive.

</div>
