# Repository Guidelines

## Project Structure & Module Organization

The library code lives under `src/` following PSR-4 (`Forlond\TestTools\…`). Companion docs for each integration sit in
`docs/` (for example `docs/php_unit.md`). Tests reside in `tests/` and mirror the namespace of the classes they cover;
if a class is at `src/Constraint/WithConsecutive.php`, its test belongs under
`tests/Constraint/WithConsecutiveTest.php`. Global PHPUnit settings are centralized in `phpunit.xml.dist`, and Composer
metadata plus autoload rules stay in `composer.json`.

## Build, Test, and Development Commands

- `composer install` — pulls dependencies and generates the autoloader; run after cloning or when `composer.json`
  changes.
- `composer dump-autoload -o` — rebuilds optimized autoload metadata after adding classes.
- `vendor/bin/phpunit -c phpunit.xml.dist` — executes the entire suite with colors, caching, and include/exclude rules
  already defined in the XML file.

## Coding Style & Naming Conventions

Follow PSR-12: four-space indentation, brace-on-next-line for classes and methods, and strict typing declarations when
possible. Namespace every class under `Forlond\TestTools\` with directories mirroring namespaces. Use descriptive,
StudlyCase class names (`MockBuilderFactory`) and snake_case file names only for fixtures. Keep public APIs immutable
once tagged; prefer adding new classes over breaking signatures. Document complex helpers inline with concise comments
explaining intent rather than mechanics.

## Testing Guidelines

Use PHPUnit 10+ features and static assertion calls (`self::assertSame`) as required by the repository’s agent brief.
Each test class should live in the same relative path as its target class beneath `tests/`, carry the `Test` suffix, and
focus on one behavior per method. Leverage the provided constraints (e.g., `WithConsecutive`) for mocking scenarios. Run
`vendor/bin/phpunit -c phpunit.xml.dist` locally before pushing; aim to keep coverage of new code near 100% for critical
utilities and never introduce untested public APIs.

## Commit & Pull Request Guidelines

Commits follow short, imperative subjects (`add doctrine serializer helper`, `fix workflow cache`) with optional detail
lines when needed; keep them scoped to one logical change and under ~60 characters. For pull requests, include: purpose
summary, testing evidence (command output or screenshots when relevant), and linked issues or discussions. Describe any
new constraints or integration docs added under `docs/`. If the change affects generated artifacts, mention regeneration
steps so reviewers can reproduce them.

## Agent-Specific Notes

Automations and human contributors alike must respect the AGENTS brief: mirror source structure for tests and always
call PHPUnit assertions statically. When scripting updates, avoid destructive git commands, leave user edits untouched,
and prefer `rg`/`composer` tooling already in the repo.
