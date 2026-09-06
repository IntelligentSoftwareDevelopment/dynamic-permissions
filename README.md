# isoftd/dynamic-permissions

Dynamic roles and permissions for Laravel on top of `spatie/laravel-permission` 8: permission and
role enums (`PermissionEnum`, `RoleEnum` + `HasPermissionEnum` / `HasRoleEnumMethods`), a
`PermissionContainer` with prefix support, `PermissionNameBuilder`, `DynamicPolicy` (a policy
that resolves its abilities from the container), `Policies` registration, and an
`AddPermissionsOperation` deploy operation (`dragon-code/laravel-deploy-operations`).

## Source of truth and syncing

The package is **developed inside the apps that use it** (`modules/dynamic-permissions` in
`IntelligentSoftwareDevelopment/writers-heaven`) and pushed here with the `sync-module`
workflow (manual trigger in the app repo). Do not edit this repository by hand; change the module
in the app and run the sync. The version in `composer.json` is the version to tag here after a
sync (bare `MAJOR.MINOR.PATCH` tags, no `v`).

Last sync: `writers-heaven@develop`, 2026-09-06 (0.1.0 — spatie 8, prefix support, permission
container, dynamic policy rewrite).

## Install

```json
"require": { "isoftd/dynamic-permissions": "^0.1" },
"repositories": [
    { "type": "vcs", "url": "git@github.com:IntelligentSoftwareDevelopment/dynamic-permissions.git" },
    { "type": "vcs", "url": "git@github.com:IntelligentSoftwareDevelopment/enum-methods.git" }
]
```

Requires `isoftd/enummethods` and `dragon-code/laravel-deploy-operations`. `HasNovaResource` is
an optional trait for apps that run Laravel Nova.
