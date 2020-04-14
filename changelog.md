# Changelog

All notable changes to `laravel-2fa` will be documented in this file.

## Version 0.4

### Changed
- `newOtp` function of `Traits\SharedMethods` has a changed signature, now including the issuer which defaults to the app.name config value
- make issuer configurable and label a property on the user model

## Version 0.3

### Added
- Configuration option `allowed-routes` for default allowed routes (eg. logout)
- Configuration options `[setup|provide]-default-redirect` to make default redirect locations customizable

## Version 0.2

### Added
- Support for laravel 7

## Version 0.1

### Added
- Everything
