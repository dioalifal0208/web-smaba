# AGENTS.md

Permanent instructions for coding agents working in this repository.

## Source Of Truth

Use `docs/PRD.md` as the source of truth for product scope, architecture, feature behavior, content model, deployment constraints, and Definition of Done.

Before implementing any feature, read `docs/PRD.md` and align the work with its requirements.

## Project

Official Website SMA Negeri 1 Babat.

## Stack

- Laravel 11
- PHP 8.2+
- MySQL
- Blade
- Tailwind CSS
- Filament
- Cloud media storage
- Hostinger production

## Rules

- Read `docs/PRD.md` before implementing features.
- Follow Laravel conventions.
- Do not change the approved stack.
- Do not introduce React, Vue, or Next.js.
- Do not over-engineer V1.
- Change database schema only through Laravel migrations.
- Do not hardcode content that should be CMS-driven.
- Do not commit credentials or secrets.
- Public UI must be responsive.
- Preserve Hostinger compatibility.
- Use reusable components for repeated UI.
- Do not put heavy business logic in Blade templates.

## Workflow

1. Inspect the code.
2. Make the minimum coherent change.
3. Run the relevant test or check.
4. Review the diff.
5. Report the changes.
