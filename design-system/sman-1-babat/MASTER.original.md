# Design System Master File

> **LOGIC:** When building a specific page, first check `design-system/sman-1-babat/pages/[page-name].md`.
> If that file exists, its rules override this Master file.
> If not, follow the rules below.

---

**Project:** SMA Negeri 1 Babat Official Website  
**Design Direction:** Modern Institutional Editorial  
**Category:** Public Education Information Portal  
**Source of Truth:** `docs/PRD.md`  
**Stack Boundary:** Laravel Blade, Livewire where needed, Tailwind CSS, Filament admin, minimal JavaScript  

---

## 1. Identity And Principles

The public website represents SMA Negeri 1 Babat as an official school information portal and gateway to digital services. It must feel credible, clear, calm, public-facing, and human. The design should prioritize access to information over persuasion.

Core principles:

- **Official first:** school identity, navigation, announcements, and service access must be immediately clear.
- **Information hierarchy over promotion:** the homepage is a public dashboard, not a commercial landing page.
- **Editorial clarity:** news, announcements, agendas, achievements, and galleries should use readable editorial patterns.
- **Authentic school presence:** real photos of school buildings, activities, students, teachers, events, and facilities are the main visual material.
- **Mobile-first:** mobile layouts must prioritize urgent information, layanan digital, and current content.
- **CMS-driven:** never design around hardcoded operational content that admins need to update.
- **Hostinger-compatible:** avoid heavy frontend dependencies, complex animation libraries, and asset-heavy patterns.
- **Accessible by default:** target WCAG 2.1 AA where practical for V1.

The interface must not look like a SaaS landing page, startup homepage, generic agency template, or AI-generated bento layout.

---

## 2. Page Patterns

### Institutional Homepage Pattern

The homepage is a **public school dashboard** with a strong editorial rhythm. It should expose the most useful information quickly and give every major audience a clear path.

Recommended order:

1. Top bar with official contact, social links, and admin/login link if needed.
2. Header and navigation with school identity.
3. Hero with authentic school photo, official welcome, concise tagline, and two practical links: Profil Sekolah and Layanan Digital.
4. Quick access for frequently used services.
5. Pengumuman Penting.
6. Layanan Digital.
7. Berita Terbaru.
8. Agenda Sekolah.
9. Profil Singkat Sekolah.
10. Prestasi Terbaru.
11. Galeri Kegiatan.
12. Statistik Sekolah if data is CMS-managed.
13. Sambutan Kepala Sekolah.
14. Footer with official address, contacts, navigation, and public links.

Priority order on mobile:

1. Pengumuman penting.
2. Layanan Digital and quick access.
3. Berita terbaru.
4. Agenda terdekat.
5. Profil and prestasi.

### Interior Page Pattern

Use consistent public page structure:

- Page title with short context, not oversized marketing hero copy.
- Breadcrumbs for pages three levels deep or more.
- Main content area with readable line length.
- Related content or sidebar only when useful.
- Pagination for large collections.
- Clear empty state for unpublished or unavailable content.

### Detail Page Pattern

Editorial content pages should use:

- Category, date, author/source, and status metadata where relevant.
- Featured image with descriptive alt text.
- Body text with comfortable measure and heading hierarchy.
- Related content after the article, not before the main content.
- Share links only if implemented lightly and accessibly.

---

## 3. Color System

Use blue, white, neutral tones, and limited gold/yellow accent as required by the PRD. Gold is for emphasis, active markers, and official highlights. It is not a commercial conversion color.

| Role | Hex | CSS Variable | Usage |
|---|---:|---|---|
| Primary Blue | `#1D4E89` | `--color-primary` | Header, primary links, active nav, institutional identity |
| Primary Blue Dark | `#12355B` | `--color-primary-dark` | Footer, high-emphasis text on light surfaces, deep header bands |
| Primary Blue Light | `#E8F1FA` | `--color-primary-soft` | Soft section backgrounds and selected states |
| Gold Accent | `#C9A227` | `--color-accent` | Limited highlights, active underline, important markers |
| Gold Soft | `#FFF7D6` | `--color-accent-soft` | Important announcement background, subtle emphasis |
| Background | `#FFFFFF` | `--color-background` | Main page background |
| Surface | `#F8FAFC` | `--color-surface` | Quiet section bands |
| Surface Raised | `#FFFFFF` | `--color-surface-raised` | Cards, panels, dropdowns |
| Text Strong | `#0F172A` | `--color-text-strong` | Headings and primary text |
| Text Body | `#334155` | `--color-text` | Body copy |
| Text Muted | `#64748B` | `--color-text-muted` | Metadata and secondary copy |
| Border | `#D9E2EC` | `--color-border` | Dividers, card borders, inputs |
| Border Strong | `#B6C5D6` | `--color-border-strong` | Focus-adjacent dividers and structured tables |
| Success | `#166534` | `--color-success` | Published/active status with text label |
| Warning | `#92400E` | `--color-warning` | Important/limited status with text label |
| Danger | `#B91C1C` | `--color-danger` | Errors and destructive actions |
| Focus Ring | `#1D4E89` | `--color-ring` | Keyboard focus indicator |

Rules:

- Use white as the dominant background.
- Use blue for official identity and navigation, not every decorative element.
- Use gold sparingly. Avoid large gold blocks that reduce readability.
- Avoid orange CTA colors, purple/pink gradients, glow effects, and decorative gradients.
- Do not rely on color alone for status. Always include text.
- Normal text must meet 4.5:1 contrast against its background.

---

## 4. Typography

Typography should feel formal, readable, and modern without becoming corporate SaaS.

Recommended default:

- **Heading font:** Plus Jakarta Sans, Inter, or system sans.
- **Body font:** Plus Jakarta Sans, Inter, or system sans.
- **Fallback stack:** `ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif`.

Type scale:

| Token | Size | Line Height | Usage |
|---|---:|---:|---|
| `--text-xs` | `0.75rem` | `1rem` | Metadata, labels |
| `--text-sm` | `0.875rem` | `1.25rem` | Secondary navigation, compact metadata |
| `--text-base` | `1rem` | `1.625rem` | Body text |
| `--text-lg` | `1.125rem` | `1.75rem` | Lead copy and intro text |
| `--text-xl` | `1.25rem` | `1.75rem` | Card and section item title |
| `--text-2xl` | `1.5rem` | `2rem` | Section heading |
| `--text-3xl` | `1.875rem` | `2.25rem` | Page title on mobile |
| `--text-4xl` | `2.25rem` | `2.5rem` | Homepage hero title on desktop |

Rules:

- Body text should be at least 16px.
- Keep paragraph line length around 60 to 75 characters on desktop.
- Avoid negative letter spacing.
- Use `font-semibold` or `font-bold` for headings, not ultra-heavy display weights.
- Metadata can be smaller, but must remain legible and high contrast.

---

## 5. Spacing And Containers

Spacing uses a 4px/8px rhythm with clear hierarchy.

| Token | Value | Usage |
|---|---:|---|
| `--space-1` | `4px` | Fine gap |
| `--space-2` | `8px` | Inline gap, metadata gap |
| `--space-3` | `12px` | Compact component padding |
| `--space-4` | `16px` | Base component padding |
| `--space-5` | `20px` | Mobile section internal gap |
| `--space-6` | `24px` | Card/list padding, mobile section gap |
| `--space-8` | `32px` | Section grouping |
| `--space-10` | `40px` | Medium section spacing |
| `--space-12` | `48px` | Desktop section spacing |
| `--space-16` | `64px` | Large homepage section spacing |

Container rules:

- Mobile gutter: `16px`.
- Tablet gutter: `24px`.
- Desktop gutter: `32px`.
- Main container max width: `72rem` to `80rem`.
- Reading container max width: `42rem` to `48rem`.
- Wide editorial/media container max width: `80rem`.
- Avoid fixed pixel widths that cause horizontal overflow.

---

## 6. Radius, Border, And Shadow

The design should use structure and spacing before heavy elevation.

Radius:

- General maximum radius: `8px`.
- Small controls and badges: `4px` to `6px`.
- Images: `6px` to `8px`.
- Avoid pill shapes unless the control is semantically a small status label or filter chip.

Border:

- Prefer `1px` borders and dividers for cards, lists, tables, inputs, and navigation separation.
- Use border color tokens, not arbitrary gray values per component.
- Use left borders sparingly for important announcements or active navigation states.

Shadow:

| Token | Value | Usage |
|---|---|---|
| `--shadow-xs` | `0 1px 2px rgba(15, 23, 42, 0.04)` | Header or subtle raised surfaces |
| `--shadow-sm` | `0 4px 12px rgba(15, 23, 42, 0.06)` | Dropdowns and occasional featured media |

Rules:

- Most content blocks should use borders, not shadows.
- Do not apply shadows to every card.
- Avoid large blurred shadows, decorative glows, and floating-card page sections.

---

## 7. Core UI Patterns

### Section Heading

Use a compact editorial heading:

- Eyebrow or category label only when it adds orientation.
- Heading text in 24px to 30px range.
- Optional short description.
- Optional "Lihat semua" link aligned to the section header.
- Do not attach decorative icons to every heading.

### Editorial List

Use for news, achievements, documents, and content collections.

Pattern:

- Thumbnail with fixed aspect ratio when media exists.
- Title, excerpt, category, date, and source metadata.
- Text-first layout on small screens.
- Avoid equal-height card grids for all editorial content.
- For collections, prefer vertical lists on mobile and mixed feature/list layouts on desktop.

### Announcement Pattern

Announcements are high-priority public information.

Pattern:

- Important announcements may use `--color-accent-soft` background and a left border in `--color-accent`.
- Include title, date range, short summary, attachment indicator if present, and clear status text.
- Do not make urgency depend on color alone.
- Keep important announcements near the top of the homepage.

### Agenda Pattern

Agenda items should be scannable by date.

Pattern:

- Date block with day, month, and year.
- Title, time, location, category, and status.
- Chronological ordering.
- Upcoming events should be visually distinct from archived events with text labels.

### Service Link Pattern

Layanan Digital is a gateway, not a feature grid for marketing.

Pattern:

- Service name, short description, category, access type, status, and external URL affordance.
- Use simple icon only when it improves recognition.
- Status labels: Aktif, Maintenance, Musiman, Pengembangan, Arsip.
- Service links must remain usable without hover.
- External links should be clear and safe for users.

### Buttons And Links

- Primary actions are practical navigation links, not conversion CTAs.
- Use filled blue for one primary action per area.
- Use bordered or text links for secondary actions.
- Minimum touch target: 44px height where practical.
- Hover may change color or border. Avoid transform movement.
- Focus state must be visible with ring or outline.

### Forms And Search

- Use visible labels.
- Use helper text for unfamiliar fields.
- Place validation errors near fields.
- Search inputs should be easy to find on collection pages.
- Do not use placeholder text as the only label.

---

## 8. Media And Authentic Photos

Photos are the primary visual asset for the public website.

Rules:

- Use authentic school photos whenever available.
- Avoid generic stock photos, dark overlays that hide context, and purely atmospheric images.
- Hero images should show school building, students, teachers, ceremonies, learning activities, or documented events.
- Provide descriptive alt text for meaningful images.
- Use empty alt only for decorative images.
- Use consistent aspect ratios:
  - News card: `16:9`.
  - Gallery cover: `4:3` or `3:2`.
  - Teacher/staff portrait: `4:5`.
  - Hero: responsive wide crop with safe focal point.
- Reserve image space with width/height or aspect-ratio to reduce layout shift.
- Lazy-load non-priority images.
- Do not show original unoptimized large images in public views.

---

## 9. Responsive Behavior

Mobile-first behavior:

- Core navigation must be reachable and clear.
- Prioritize announcements, service access, latest news, and agenda.
- Use vertical lists before multi-column grids.
- Keep touch targets at least 44px high where practical.
- Avoid horizontal scrolling.
- Long titles and URLs must wrap safely.

Desktop behavior:

- Use larger containers and multi-column editorial layouts only where they improve scanning.
- Avoid excessive whitespace that hides important public information below the fold.
- Keep sidebars secondary and optional.
- Do not turn the homepage into a marketing funnel.

Breakpoints to verify:

- 375px small phone.
- 768px tablet.
- 1024px laptop.
- 1440px desktop.

---

## 10. Accessibility

Target WCAG 2.1 AA where practical for V1.

Required:

- One clear `h1` per page.
- Sequential heading hierarchy.
- Skip link to main content.
- Visible focus state for links, buttons, menu triggers, inputs, and cards that are links.
- Text contrast at least 4.5:1 for normal text.
- Non-text UI contrast at least 3:1 for meaningful boundaries and icons.
- Do not remove outline without replacing it with a visible focus style.
- Link text must describe destination.
- Images need meaningful alt text when informative.
- Decorative icons must be hidden from assistive tech.
- Icon-only controls must have accessible names.
- Forms must use labels and clear error text.
- Status must use text, not color alone.
- Keyboard users must be able to open, navigate, and close menus.
- Moving or auto-rotating content must have a pause/stop control, or be avoided.

---

## 11. Motion

Motion should be minimal and functional.

Allowed:

- Color, border, and opacity transitions for hover/focus/active states.
- Menu open/close transition under 200ms.
- Disclosure transitions that clarify state.

Rules:

- Respect `prefers-reduced-motion`.
- Do not hide SEO-critical content behind JavaScript animation.
- Avoid scroll reveal as a default pattern.
- Avoid animation libraries for V1 unless explicitly approved.
- Do not use GSAP for the public website baseline.
- Do not use parallax, decorative motion, glow pulses, or animated backgrounds.

---

## 12. Anti-AI-Slop Rules

Do not use:

- SaaS category framing.
- Conversion-oriented CTA language.
- Sticky CTA or bottom CTA patterns.
- Hero + Features + CTA as the global page pattern.
- Pricing sections.
- Testimonials or commercial social proof.
- Fake statistics.
- Generic promotional copy.
- Generic bento grids.
- Glassmorphism.
- Neumorphism.
- Backdrop blur.
- Decorative glow.
- Purple/pink gradients.
- Orange conversion CTA accent.
- Cards around every piece of content.
- Large radius beyond 8px for normal surfaces.
- Heavy or blurry shadows.
- Decorative icons on every title.
- Emoji as icons.
- Decorative animation.
- Generic stock photos.

When in doubt, choose a quieter, clearer, more official editorial treatment.

---

## 13. Implementation Notes For Future Frontend Work

These notes are constraints for future implementation, not permission to build in this step.

- Use Blade layouts and reusable Blade components for repeated public UI.
- Keep JavaScript minimal. Alpine or Livewire interactions are acceptable only when needed.
- Do not introduce React, Vue, Next.js, or SPA architecture.
- Keep public content CMS-driven where admins need updates.
- Use semantic Tailwind config tokens before repeating raw hex values in views.
- Keep public UI independent from Filament styling.
- Do not place heavy business logic in Blade templates.

---

## 14. Pre-Delivery Checklist

Before any public UI is considered ready:

- [ ] Matches official school portal purpose from `docs/PRD.md`.
- [ ] Does not look like SaaS, startup, or commercial landing page.
- [ ] Uses blue, white, neutral, and limited gold accent.
- [ ] Uses real school media or clearly marked placeholders during development.
- [ ] No fake statistics or invented school claims.
- [ ] Homepage follows information-priority structure.
- [ ] Mobile layout exposes announcements and services early.
- [ ] Text is readable at 375px width.
- [ ] No horizontal overflow.
- [ ] Touch targets are practical on mobile.
- [ ] Focus states are visible.
- [ ] Images include correct alt behavior.
- [ ] Lists, announcements, agenda, and service links are scannable.
- [ ] Border and shadow use is restrained.
- [ ] Motion is minimal and respects reduced motion.
- [ ] Content that should be CMS-driven is not hardcoded in final implementation.
