# WebifyCMS Admin Extension

Package `webifycms/ext-admin` (composer type `webifycms-extension`). Provides the
dashboard views and controllers, and the route group the admin lives under; the admin
shell is owned by the active theme (see Layout below). An extension of WebifyCMS (not a
standalone app). Requires `webifycms/ext-base`.

## Layout

```
src/
  Domain/{Exception,ValueObject}          # AdminLocation + its exception
  Infrastructure/
    Container/ServiceDefinitions.php      # DI group (AdminLocation, Administration)
    Extension/AdminExtension.php          # ExtensionInterface entry point
    Presentation/Http/{Controller,Routes} # Dashboard controller, route registration
    Provider/{Service,RegisterRoutes}.php
    Service/Administration.php            # AdministrationInterface implementation
templates/
  dashboard/index.php                     # dashboard page
```

- `AdminExtension` (id/slug `webifycms-admin`) supplies the extension's providers.
- The extension declares **no assets provider** — its design system belongs to the
  active theme.
- `Administration` derives both the route-group prefix and `isAdministration()` from one
  configured location, so moving the admin moves both together. The location comes from
  the `adminHost` / `adminPath` config values (default path `administration`).
- The admin shell (layouts/main and layouts/_sidebar) now lives in the theme; the dashboard
  renders with layout `'admin/layouts/main'`.

## Conventions

- Namespace `Webify\Admin\*` (autoload `Webify\Admin\` → `src/`); tests `Webify\Admin\Test\` → `test/`.
- Extensions register themselves via the ext-base extension/service-provider contracts
  (see `extensions/ext-base/AGENTS.md`).
- Templates are plain PHP, receive `$view`, and compose with `$view->partial()`. They never
  call `render()`, and they contribute scripts/styles with `pushScript()` / `pushStyle()`
  for the shell's layout to emit.
- A leading `_` marks a fragment by convention only; fragments are resolved against the
  including template's own directory with no fallback ladder.



## Verification

```bash
composer test          # PHPUnit
composer analyse       # PHPStan
composer sniff         # PHP-CS-Fixer dry-run
composer code-check    # analyse + sniff
composer code-format   # PHP-CS-Fixer fix
```
