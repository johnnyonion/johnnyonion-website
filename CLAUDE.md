# johnnyonion.com

Giovanni Cipolla's portfolio site — composer/playwright/author. Built on the
"Forty" HTML5UP template. Goal beyond a portfolio: build an audience and
generate production/publishing opportunities for the shows and the Detector
novel series.

## Hosting & deploy (read this before changing anything structural)

- **Host:** Hostinger (PHP-capable, LiteSpeed). This is why `contact.php`
  works — it is *not* GitHub Pages.
- **Deploy mechanism:** Hostinger's Git integration watches the GitHub repo
  (`origin` = `git@github.com:johnnyonion/johnnyonion-website.git`, branch
  `main`) and mirrors whatever's committed there directly to the live
  document root. **There is no build step on the host.** Whatever's in the
  repo is exactly what's live, seconds after a push.
- Because of that constraint, the blog (see below) builds locally/in CI and
  commits its *output* to the repo — the host never runs `npm`.

## Site structure

- **7 hand-coded static pages at the repo root**: `index.html`, `500M.html`,
  `ATL.html`, `Detector.html`, `Hypothesis.html`, `FutureMan.html`,
  `Einstein.html`. Each show page repeats the same header/nav/contact/footer
  markup by copy-paste (no templating) — editing shared chrome means editing
  it in all 7.
- **`500M-onesheet.html`** — a print-optimized one-page pitch doc for *500
  Miles, 2 Hearts and a Guitar*, linked from `500M.html`. Has a "Save /
  Print One-Sheet" button (`window.print()`) and print-only CSS that hides
  nav/footer/contact-form and re-sizes the banner (the theme's `#banner` has
  `height: 60vh` / `max-height: 32em` baked in for the on-screen hero effect
  — this **must** be overridden with `height: auto !important` etc. in any
  print stylesheet, or it silently reserves several blank inches and pushes
  the page to 2 pages).
- **`contact.php`** — PHP mail form handler. Sanitizes the `name` field
  against CR/LF (mail header injection) and has a honeypot field.
- **Blog** — built with Eleventy (11ty), lives at `/blog/`. See below.

## The blog (Eleventy)

- **Source:** `src/_includes/layout.njk` (shared chrome — header/nav/banner/
  subscribe box/contact/footer, reused by every blog page), `src/_includes/
  post.njk` (wraps individual posts), `src/blog/index.njk` (post listing),
  `src/blog/feed.njk` (RSS/Atom feed → `/blog/feed.xml`), `src/blog/
  sitemap.njk` (→ `/blog/sitemap.xml`, auto-includes every post — no manual
  upkeep needed as new posts are added), `src/blog/posts/*.md` (the actual
  posts).
- **Build config:** `.eleventy.js` — input `src/blog`, output `blog/`.
  Custom filters `readableDate`/`htmlDateString` defined there.
- **Important gotcha:** blog pages are nested (`/blog/posts/<slug>/`), so
  `layout.njk` uses **absolute paths** (`/assets/css/main.css`,
  `/images/...`) everywhere, unlike the root-level static pages which use
  relative paths. Also, Eleventy's auto-computed `post.url` does **not**
  know the output folder (`blog/`) becomes part of the live URL — templates
  manually prepend `/blog` wherever `post.url` is used (see `index.njk` and
  `feed.njk`). An explicit `permalink:` front-matter value, by contrast,
  *is* already relative to the output dir (don't double-prefix those).
- **How to add a post:** see `HOW-TO-BLOG.md`. Short version: add a `.md`
  file to `src/blog/posts/`, push (even via GitHub's web editor — no local
  setup required).
- **How it actually deploys:** `.github/workflows/build-blog.yml` triggers
  on push to `src/**`/`.eleventy.js`/`package.json`/`package-lock.json`,
  runs `npm ci && npm run build`, and commits the regenerated `blog/`
  output back to `main` as `github-actions[bot]` (message: `Build blog
  output [skip ci]`). Hostinger then deploys that commit like any other.
  **Never hand-edit files under `blog/`** — they're generated output and
  will be silently overwritten by the next Action run.
- When testing builds locally (`npm install && npm run build`), remember
  to `git checkout -- blog/` (or `rm -rf blog node_modules` and re-pull)
  before committing, so you don't commit a stale local build over what the
  Action would produce — this repo's workflow has consistently let the
  Action be the one to produce/commit the real `blog/` output.

## Third-party services wired in

- **Email capture:** Kit (formerly ConvertKit), account `johnnyonion.kit.com`,
  form UID `2c139a7063`. Embed script lives in `src/_includes/layout.njk`
  ("Subscribe for Updates" section) — currently **blog pages only**, not
  the 7 static pages.
- **Analytics:** GoatCounter, `johnnyonion.goatcounter.com` (free, no
  cookies/consent banner needed). Snippet is on all 7 static pages
  individually plus once in `layout.njk` for the blog — i.e. site-wide.

## SEO

- Meta description + Open Graph + Twitter card tags on all main pages.
- `robots.txt` (repo root) + `sitemap.xml` (repo root, static, the 7 main
  pages) + `blog/sitemap.xml` (auto-generated, see above). `robots.txt`
  also disallows `/contact.php` and the build/source files (`/src/`,
  `package.json`, etc.) that are technically fetchable since Hostinger
  mirrors the whole repo, but aren't real content.
- **Still needs a human:** Google Search Console verification + submitting
  `https://johnnyonion.com/sitemap.xml` — needs Giovanni's Google account,
  can't be done from here.

## Housekeeping already done this round

- Removed 4 stale/unedited files that were live but unlinked:
  `Hypo.html` (superseded draft), `generic.html`, `landing.html`,
  `elements.html` (untouched HTML5UP template pages).
- Fixed dead `generic.html` image links on several show pages (leftover
  from the template, pointed nowhere useful).
- `.gitignore` added (`node_modules/`, `.DS_Store`); the two previously
  tracked `.DS_Store` files were untracked.
- Typo fixes on the homepage; `FutureMan.html`'s title tag was missing a
  space.

## Open ideas / possible next steps (nothing here is started)

- **One-sheets for other shows** — same pattern as `500M-onesheet.html`.
  `FutureMan.html` explicitly asks for a bookwriter collaborator; that ask
  is currently buried in body text and could use its own visual callout
  and/or one-sheet.
- **"Show" field on the contact form** so replies are easier to triage —
  right now every page's contact form is identical/generic.
- **Full character & doubling breakdown** for *500 Miles* as a separate
  document (Giovanni provided the full cast list with vocal ranges and
  doubling suggestions in conversation — intentionally left off the
  one-sheet, which should stay skimmable, but worth its own page/PDF for
  later-stage submissions).
- **Bio/press page** with a downloadable press kit — no formal author bio
  or headshot page exists yet.
- **Status tags per show** (Seeking Production / In Development / Seeking
  Collaborator / Dormant) for at-a-glance scanning — phrasing is currently
  inconsistent show-to-show.
- **Traffic**: check whether Instagram (@caffeinated_composer) and
  SoundCloud (johnyonion) bios actually link back to the site (free win if
  not); Google Search Console (see above); industry-specific communities
  (NAMT, NewMusicalTheatre.com, r/PubTips, etc.) will likely outperform
  general traffic for this site's actual goals; the blog only pays off if
  posts get cross-promoted on social, not just published.
- **Cross-project Claude workflow**: a Claude Code session working in a
  different project (e.g. the Detector manuscript) can edit files in this
  repo directly by absolute path — no special integration needed, but it
  won't spontaneously know to sync anything; someone has to ask it to, in
  the moment something changes.
