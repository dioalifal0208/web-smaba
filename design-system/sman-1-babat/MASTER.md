# Design System Master File

> **LOGIC:** When building a specific page, first check `design-system/sman-1-babat/pages/[page-name].md`.
> If that file exists, its rules override this Master file.
> If not, follow the rules below.

---

**Project:** SMA Negeri 1 Babat Official Website  
**Design Direction:** Modern Institutional Editorial  
**Category:** Public Education Information Portal  
**Source of Truth:** `docs/PRD.md`  
**Stack Boundary:** Laravel Blade, Tailwind CSS, Filament admin, minimal JavaScript, Alpine.js only when needed  
**Not A:** SaaS product, commercial landing page, startup homepage, agency template, marketing funnel  

---

## 1. Identity And Design Principles

SMA Negeri 1 Babat's public website is the official public information portal and digital service gateway for the school. It must feel official, credible, clean, human, and mobile-first. The design should help students, parents, teachers, prospective students, alumni, and the public find current information quickly.

Primary focus:

- News and public information are the main content surface.
- Announcements, agenda, digital services, and official school profile must be easy to reach.
- Authentic school photography is the primary visual asset.
- The tone is institutional editorial, not commercial promotion.
- Content that changes operationally must remain CMS-driven.

Design principles:

- **Official first:** school identity, address/contact cues, and navigation must be clear before decorative elements.
- **Information hierarchy over persuasion:** prioritize announcements, services, latest news, and agenda above promotional sections.
- **Editorial clarity:** use readable list, article, media, and public-information patterns.
- **Human but restrained:** show authentic school life through photos, captions, and clear writing.
- **Mobile-first:** the 375px layout must expose urgent information early without horizontal overflow.
- **Accessible by default:** target WCAG 2.1 AA where practical for V1.
- **Hostinger-compatible:** keep frontend simple, server-rendered, and light.

Do not design the public website as a SaaS conversion page, pricing page, feature grid, sticky CTA funnel, or startup-style hero.

---

## 2. Homepage Information Architecture

The homepage is a public school dashboard. It must combine identity, current information, and access to official services.

Recommended homepage order:

1. Utility bar when useful: contact, service hours, social links, or admin/login link.
2. Header identity: logo, school name, short official descriptor.
3. Main navigation: Beranda, Profil, Akademik, Kesiswaan, Berita, Layanan Digital, Galeri, Kontak.
4. Photo-based hero using authentic school media.
5. Quick access for high-frequency links and services.
6. Important announcements.
7. Digital services.
8. Latest news.
9. Upcoming agenda.
10. Recent achievements.
11. Short profile or principal greeting.
12. Gallery preview.
13. Institutional footer.

Mobile priority:

1. Important announcements.
2. Digital services and quick access.
3. Latest news.
4. Upcoming agenda.
5. Profile, achievements, and gallery.

Rules:

- A hero may include practical links such as Profil Sekolah and Layanan Digital, but these are navigation links, not conversion CTAs.
- The first viewport should communicate that this is the official SMA Negeri 1 Babat website.
- Avoid hiding public information behind decorative hero height.
- Show a hint of the next section on common mobile and desktop viewports when possible.

---

## 3. Content Patterns

Use a mix of content-first patterns. Do not wrap every section or every item inside generic cards.

Approved patterns:

- **Editorial list:** vertical news or article list with title, excerpt, date, category, and optional thumbnail.
- **Article list:** compact list for latest content, related content, and archives.
- **Media feature:** one larger story or gallery item paired with a supporting list.
- **Announcement list:** high-priority public notices with date range, attachment cue, and importance label.
- **Agenda list:** date-led event rows with time, location, category, and status text.
- **Service link:** digital service item with name, description, status, access type, and external-link affordance.
- **Section band:** full-width background separation using white, soft blue, or neutral surface.
- **Public information table/list:** documents, service directories, staff lists, contact details, and official metadata.

Pattern rules:

- Use cards only for repeated items that benefit from a framed boundary.
- Prefer lists, rows, dividers, section bands, and media layouts for editorial content.
- Tables and structured lists must remain readable on mobile through wrapping or stacked row layouts.
- Every collection needs empty state behavior for unpublished or unavailable data.
- Do not present placeholder content as real school data.

---

## 4. Color Tokens

Use blue, white, neutral, and limited gold/yellow accent as required by the PRD. Gold is an institutional accent, not a conversion color. No decorative gradients.

| Role | Hex | CSS Variable | Usage |
|---|---:|---|---|
| Primary | `#1D4E89` | `--color-primary` | Header, active nav, primary links, official identity |
| On Primary | `#FFFFFF` | `--color-on-primary` | Text/icons on primary blue |
| Primary Dark | `#12355B` | `--color-primary-dark` | Footer, deep institutional bands |
| Primary Soft | `#E8F1FA` | `--color-primary-soft` | Soft blue section backgrounds and selected states |
| Secondary | `#2F6FA8` | `--color-secondary` | Secondary links, subtle navigation emphasis |
| On Secondary | `#FFFFFF` | `--color-on-secondary` | Text/icons on secondary blue |
| Accent | `#B88900` | `--color-accent` | Limited gold underline, markers, official highlights |
| Accent Soft | `#FFF4CC` | `--color-accent-soft` | Important announcement background, low-emphasis highlight |
| Background | `#FFFFFF` | `--color-background` | Main page background |
| Surface | `#F8FAFC` | `--color-surface` | Quiet section bands |
| Surface Raised | `#FFFFFF` | `--color-surface-raised` | Dropdowns, panels, repeated framed items |
| Text Strong | `#0F172A` | `--color-text-strong` | Page titles, section headings |
| Text | `#334155` | `--color-text` | Body copy |
| Text Muted | `#475569` | `--color-text-muted` | Metadata and supporting copy |
| Border | `#D9E2EC` | `--color-border` | Dividers, input borders, table lines |
| Border Strong | `#9FB2C7` | `--color-border-strong` | Structured tables, focus-adjacent separators |
| Success | `#166534` | `--color-success` | Active/published status with text label |
| Success Soft | `#DCFCE7` | `--color-success-soft` | Success status background |
| Warning | `#92400E` | `--color-warning` | Maintenance/important warning with text label |
| Warning Soft | `#FEF3C7` | `--color-warning-soft` | Warning status background |
| Error | `#B91C1C` | `--color-error` | Errors and destructive actions |
| Error Soft | `#FEE2E2` | `--color-error-soft` | Error status background |
| Information | `#1D4E89` | `--color-info` | Informational status and help text |
| Information Soft | `#E8F1FA` | `--color-info-soft` | Informational status background |
| Focus Ring | `#1D4E89` | `--color-focus-ring` | Keyboard focus outline/ring |

Contrast rules:

- `--color-text-strong`, `--color-text`, and `--color-text-muted` must meet WCAG AA on `--color-background`, `--color-surface`, and `--color-surface-raised`.
- Text on primary, secondary, success, warning, error, and info colors must use an approved high-contrast foreground token.
- Non-text UI boundaries and meaningful icons must meet at least 3:1 contrast.
- Status cannot rely on color alone; include status text.
- Do not use purple/pink gradients, orange conversion accents, decorative gradients, glow colors, or one-note blue-only decoration.

---

## 5. Typography Tokens

Typography must be formal, clean, and easy to read. Use a maximum of two font families.

Font tokens:

| Token | Value | Usage |
|---|---|---|
| `--font-sans` | `"Plus Jakarta Sans", Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif` | Headings, navigation, UI, body |
| `--font-serif` | `Georgia, "Times New Roman", serif` | Optional long-form editorial quote only |

Default recommendation:

- Use `--font-sans` for both heading and body.
- Load only needed weights: 400, 500, 600, 700.
- Use `font-display: swap` or equivalent behavior when loading web fonts.
- Avoid decorative, novelty, overly rounded startup fonts, and condensed display fonts.

Type scale:

| Token | Size | Line Height | Weight | Usage |
|---|---:|---:|---:|---|
| `--text-xs` | `0.75rem` / 12px | `1rem` / 16px | 500 | Metadata only |
| `--text-sm` | `0.875rem` / 14px | `1.25rem` / 20px | 400-600 | Labels, secondary nav |
| `--text-base` | `1rem` / 16px | `1.625rem` / 26px | 400 | Public body content |
| `--text-lg` | `1.125rem` / 18px | `1.75rem` / 28px | 400-500 | Lead paragraph |
| `--text-xl` | `1.25rem` / 20px | `1.875rem` / 30px | 600 | Item title |
| `--text-2xl` | `1.5rem` / 24px | `2rem` / 32px | 600-700 | Section heading |
| `--text-3xl` | `1.875rem` / 30px | `2.375rem` / 38px | 700 | Page title and mobile hero |
| `--text-4xl` | `2.25rem` / 36px | `2.75rem` / 44px | 700 | Desktop hero title |

Heading hierarchy:

- One `h1` per page.
- `h2` for main sections.
- `h3` for item groups or article cards/lists.
- Do not skip heading levels for visual styling.
- Avoid negative letter spacing.
- Public body content must be at least 16px.
- Desktop long-form prose should stay near 60-75 characters per line.

---

## 6. Spacing, Container, And Breakpoint Tokens

Use a 4px/8px rhythm. Choose spacing based on hierarchy, not decoration.

Spacing scale:

| Token | Value | Usage |
|---|---:|---|
| `--space-0` | `0` | Reset |
| `--space-1` | `4px` | Fine gap |
| `--space-2` | `8px` | Inline gap |
| `--space-3` | `12px` | Compact padding |
| `--space-4` | `16px` | Base mobile padding |
| `--space-5` | `20px` | Small section internal gap |
| `--space-6` | `24px` | Component/list padding |
| `--space-8` | `32px` | Section grouping |
| `--space-10` | `40px` | Medium section spacing |
| `--space-12` | `48px` | Desktop section spacing |
| `--space-16` | `64px` | Large homepage spacing |
| `--space-20` | `80px` | Maximum hero/major section spacing |

Container tokens:

| Token | Value | Usage |
|---|---:|---|
| `--container-page` | `80rem` / 1280px | Main public layout |
| `--container-content` | `48rem` / 768px | Article body and long-form content |
| `--container-narrow` | `42rem` / 672px | Forms, focused text |
| `--container-wide` | `90rem` / 1440px | Full media/editorial layouts only |
| `--gutter-mobile` | `16px` | 375px viewport |
| `--gutter-tablet` | `24px` | Tablet viewport |
| `--gutter-desktop` | `32px` | Desktop viewport |

Breakpoint tokens:

| Token | Value | Verify |
|---|---:|---|
| `--breakpoint-sm` | `375px` | Small phone |
| `--breakpoint-md` | `768px` | Tablet |
| `--breakpoint-lg` | `1024px` | Laptop |
| `--breakpoint-xl` | `1440px` | Desktop |

Rules:

- Mobile is the base layout.
- Avoid fixed widths that can create horizontal overflow.
- Long titles, URLs, tables, and service names must wrap safely.
- Use vertical lists on mobile before multi-column grids.
- Desktop grids are allowed only when they improve scanning.

---

## 7. Border, Radius, Shadow, And Layer Tokens

Shape must stay restrained and institutional.

Border tokens:

| Token | Value | Usage |
|---|---:|---|
| `--border-width-default` | `1px` | Cards, rows, tables, inputs |
| `--border-width-strong` | `2px` | Focus-adjacent and active states |
| `--border-color` | `var(--color-border)` | Default boundary |
| `--border-color-strong` | `var(--color-border-strong)` | Strong boundary |

Radius tokens:

| Token | Value | Usage |
|---|---:|---|
| `--radius-none` | `0` | Tables, section bands when needed |
| `--radius-sm` | `4px` | Labels, badges |
| `--radius-md` | `6px` | Inputs, thumbnails |
| `--radius-lg` | `8px` | Cards, panels, images, buttons |

Rules:

- General radius maximum is 8px.
- Pills are allowed only for semantic labels, filters, or compact status chips.
- Do not use rounded startup-style capsules as decoration.

Shadow tokens:

| Token | Value | Usage |
|---|---|---|
| `--shadow-none` | `none` | Default |
| `--shadow-xs` | `0 1px 2px rgba(15, 23, 42, 0.04)` | Header or subtle raised surface |
| `--shadow-sm` | `0 4px 12px rgba(15, 23, 42, 0.06)` | Dropdowns, occasional feature media |

Rules:

- Use border and background difference before shadow.
- Shadows must be very subtle and used only for hierarchy.
- Do not use glow, glassmorphism, backdrop blur, heavy shadows, or shadow on every card.

Layer tokens:

| Token | Value | Usage |
|---|---:|---|
| `--z-base` | `0` | Normal content |
| `--z-raised` | `10` | Raised local panels |
| `--z-header` | `30` | Header and navigation |
| `--z-dropdown` | `50` | Menus and dropdowns |
| `--z-modal` | `100` | Dialogs if introduced later |
| `--z-skip-link` | `110` | Skip link above header |

---

## 8. Interaction Tokens And Rules

JavaScript must stay minimal. Use Alpine.js only for light interactions such as mobile menu, dropdown, disclosure, and gallery lightbox when needed. Do not use GSAP.

Interaction tokens:

| Token | Value | Usage |
|---|---:|---|
| `--target-touch` | `44px` | Minimum practical touch target |
| `--focus-ring-width` | `3px` | Keyboard focus ring |
| `--focus-ring-offset` | `2px` | Ring offset from element |
| `--duration-fast` | `120ms` | Hover/focus feedback |
| `--duration-base` | `180ms` | Menu/disclosure feedback |
| `--duration-slow` | `240ms` | Rare, larger state changes |
| `--ease-standard` | `cubic-bezier(0.2, 0, 0, 1)` | Standard UI transitions |

Rules:

- Every interactive element must have hover and focus-visible states.
- Focus-visible must be clear on links, buttons, menu triggers, inputs, service links, and linked cards.
- Touch targets should be at least 44px high where practical.
- Hover/focus should change color, border, background, or underline, not move layout.
- Avoid transform movement on hover for public content lists.
- Disabled states must be visibly and semantically disabled.
- Respect `prefers-reduced-motion`.
- Motion is only for interaction feedback, not decoration.
- No parallax, scroll reveal baseline, animated background, glow pulse, or decorative entrance animation.

---

## 9. Core Component Guidance

### Header And Navigation

- Header identity includes logo, school name, and official context.
- Navigation labels must be text-first and readable.
- Current page state must be visible through weight, underline, border, or background.
- Mobile navigation must be keyboard-operable and closable.
- Do not use icon-only primary navigation.

### Hero

- Use authentic school photo as the visual anchor.
- Text must remain readable without darkening the image so much that context disappears.
- The hero is not a sales pitch. Use official welcome and short portal description.
- Primary links are practical: Profil Sekolah, Layanan Digital, Berita, or Pengumuman.
- Avoid oversized hero height that pushes announcements too far down.

### Editorial Lists

- Use title, category, date, excerpt, and optional thumbnail.
- Prefer vertical lists on mobile.
- Use fixed media aspect ratios to prevent layout shift.
- Do not force all editorial content into equal-height card grids.

### Announcements

- Important announcements may use `--color-accent-soft` and a left border using `--color-accent`.
- Include title, date range, short summary, attachment cue if present, and importance/status text.
- Place important announcements early on the homepage.
- Do not communicate urgency by color alone.

### Agenda

- Use a date-led row pattern.
- Include date, time, location, category, and status text.
- Upcoming and archived agenda states must have clear labels.
- Keep chronological order obvious.

### Digital Services

- Treat layanan digital as a public gateway, not a marketing feature grid.
- Include service name, description, status, access type, and destination affordance.
- External links must be clearly identifiable.
- Maintenance, seasonal, development, and archived services must be labeled in text.
- Service links must remain usable without hover.

### Buttons And Links

- Primary button/link: blue fill, white text, practical navigation only.
- Secondary button/link: border or text style.
- Accent/gold is for emphasis and markers, not primary CTA fill.
- Link text should describe destination.
- Use underline or clear state where inline links appear inside prose.

### Forms, Search, And Filters

- Use visible labels. Placeholder is not a label.
- Inputs must be at least 16px text size.
- Validation error appears near the field and explains recovery.
- Search and filters should be easy to reach on collection pages.
- Filter chips may wrap; do not hide selected filters without an operable disclosure.

### Tables And Public Information Lists

- Use clear headers, row separators, and sufficient spacing.
- On mobile, allow stacked rows or horizontal-safe wrapping.
- Do not use color alone for status or category.
- Provide download/link affordances with descriptive text.

---

## 10. Media And Authentic Photography

Photos are the primary visual asset for the public website.

Rules:

- Use authentic photos of school buildings, learning activities, ceremonies, events, facilities, teachers, students, or documented achievements.
- Avoid generic stock photos, fake classroom imagery, dark atmospheric crops, and AI-looking placeholders.
- Hero images must preserve recognizable school context.
- Meaningful images need descriptive alt text.
- Decorative images use empty alt text.
- Reserve image space with width/height or `aspect-ratio`.
- Lazy-load non-priority images.
- Do not expose unoptimized original large images in public views.

Aspect ratio guidance:

| Asset | Ratio |
|---|---:|
| Hero image | `16:9` or wider responsive crop |
| News thumbnail | `16:9` |
| Gallery cover | `4:3` or `3:2` |
| Staff portrait | `4:5` |
| Achievement image | `4:3` or `16:9` |

---

## 11. Responsive And Accessibility Requirements

Responsive:

- Mobile-first implementation.
- Verify 375px, 768px, 1024px, and 1440px.
- No horizontal overflow.
- Text must wrap inside containers.
- Navigation must work on mobile and with keyboard.
- Avoid nested scroll regions for main content.

Accessibility:

- Provide skip link to main content.
- Use semantic HTML: `header`, `nav`, `main`, `section`, `article`, `aside`, `footer`, `time`, `figure`, `figcaption` where appropriate.
- One clear `h1` per page.
- Heading hierarchy must be sequential and consistent.
- Text contrast target: WCAG AA, 4.5:1 for normal text.
- Non-text UI contrast target: 3:1 for meaningful icons, borders, and controls.
- Images require meaningful alt text when informative.
- Decorative icons should be hidden from assistive technology.
- Icon-only controls require accessible names.
- Keyboard users must be able to open, navigate, and close menus.
- Focus must not be hidden behind sticky headers or overlays.
- Information must not depend on color alone.
- Auto-rotating content should be avoided; if used later, it must include pause/stop controls and respect reduced motion.

---

## 12. Motion And Effects

Motion is minimal and functional.

Allowed:

- Hover/focus color transition.
- Border/background/opacity feedback.
- Menu or disclosure open/close under `--duration-base`.
- Small state changes that clarify interaction.

Forbidden:

- GSAP and ScrollTrigger.
- Scroll reveal as a baseline pattern.
- Parallax.
- Animated decorative backgrounds.
- Glow pulse.
- Glassmorphism and backdrop blur.
- Layout-shifting hover movement.
- Decorative entrance animation.

Reduced motion:

- Respect `prefers-reduced-motion: reduce`.
- Keep content visible without animation.
- Do not hide SEO-critical or public information behind JavaScript.

---

## 13. Anti-AI-Slop Rules

The design must explicitly avoid:

- SaaS template appearance.
- Purple-pink or purple-red gradients.
- Generic bento grids.
- Glassmorphism.
- Backdrop blur.
- Decorative glow.
- Every section shaped as a card.
- Excessive cards and heavy shadows.
- Excessive pills or rounded capsules.
- Decorative icon bubbles.
- Decorative icon on every heading.
- Generic slogans such as "Empowering the future" without school-specific editorial context.
- Fake statistics.
- Fake testimonials.
- Commercial testimonials.
- Commercial social proof.
- Pricing sections.
- Conversion CTA, sticky CTA, and bottom CTA patterns.
- Orange accent used as a conversion CTA.
- Generic stock photos.
- AI-looking placeholder photos presented as real media.
- Decorative animation.
- Placeholder content presented as real school data.

When uncertain, choose the quieter official editorial treatment.

---

## 14. Future Frontend Implementation Boundary

These are constraints for later implementation, not permission to implement in this step.

- Use Laravel Blade layouts and reusable Blade components.
- Use Tailwind CSS tokens or config mappings before repeating raw hex values.
- Use Alpine.js only for lightweight progressive interactions.
- Do not introduce React, Vue, Next.js, SPA routing, GSAP, or extra frontend dependencies.
- Keep public content CMS-driven where admins need updates.
- Keep public UI independent from Filament styling.
- Do not put heavy business logic in Blade templates.
- Preserve Hostinger compatibility and fast mobile performance.

---

## 15. Pre-Delivery Checklist For Public UI

Before any public UI is considered ready:

- [ ] Matches the official school portal purpose from `docs/PRD.md`.
- [ ] Does not look like SaaS, startup, or commercial landing page.
- [ ] Uses blue, white, neutral, and limited gold accent.
- [ ] Uses authentic school media or clearly marked development placeholders.
- [ ] No fake statistics, fake testimonials, or invented school claims.
- [ ] Homepage follows the public dashboard information order.
- [ ] Mobile layout exposes announcements and services early.
- [ ] Body content is at least 16px.
- [ ] Text is readable at 375px width.
- [ ] No horizontal overflow.
- [ ] Touch targets are practical on mobile.
- [ ] Hover and focus-visible states exist for all interactive elements.
- [ ] Skip link is present.
- [ ] Semantic HTML and heading hierarchy are correct.
- [ ] Images include correct alt behavior.
- [ ] Lists, announcements, agenda, services, and tables are scannable.
- [ ] Border and background separation are used before shadow.
- [ ] Radius stays at or below 8px for normal surfaces.
- [ ] Motion is minimal and respects reduced motion.
- [ ] No GSAP, glassmorphism, backdrop blur, decorative glow, or decorative gradients.
- [ ] Content that should be CMS-driven is not hardcoded in final implementation.
