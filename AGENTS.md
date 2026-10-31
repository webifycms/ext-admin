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
assets/
  js/dashboard.js                         # the dashboard's page asset
```

- `AdminExtension` (id/slug `webifycms-admin`) supplies the extension's providers.
- The extension ships its own assets under the conventional `assets/` directory — the kernel
  discovers them from the package root (`ExtensionInterface::getRootPath()`), so **no PHP
  declares them** and no assets provider class exists. `assets/js/dashboard.js` is the
  dashboard's page asset; the view pins it by owner
  (`$view->assetEntrypoint('webifycms-admin:js/dashboard.js')`). The design system itself is
  the theme's: the dashboard is written against Canvas's documented classes, so the extension
  ships CSS only when it needs to (see `themes/theme-canvas/docs/ui-contract.md`).
- `Administration` derives both the route-group prefix and `isAdministration()` from one
  configured location, so moving the admin moves both together. The location comes from
  the `adminHost` / `adminPath` config values, with optional `adminScheme` and `adminPort`.
  When both are empty, it falls back to the default path `administration`. When a host is
  set, `isAdministration()` requires the request host to match; a host-only location
  (empty path) makes the entire host the administration. Routing remains path-based in
  this package — if the administration uses a dedicated host, route that host to the
  application (separate vhost/reverse proxy). `getUrl()` builds proper scheme/host/port
  URLs for host-based administration and respects `UrlType`; for a relative URL with a
  different host it falls back to a path-based URL.
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

## Comment rules

Every comment in this package follows the project rules in
[`docs/comment-rules.md`](../../docs/comment-rules.md): a comment says what the
code does and why it exists, in plain words a non-programmer can follow. A
comment that only restates the code is deleted.

## Verification

```bash
composer test          # PHPUnit
composer analyse       # PHPStan
composer sniff         # PHP-CS-Fixer dry-run
composer code-check    # analyse + sniff
composer code-format   # PHP-CS-Fixer fix
```
