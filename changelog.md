# Changelog

All notable changes to `laravel-2fa` will be documented in this file.

## Version 0.12
- Support laravel 10
  
## Version 0.11
- Support laravel 9

## Version 0.10

### Changed
- Changed way of matching redirect

## Version 0.9

### Added
- Possibility for users to change their 2fa (thanks to [@niekBr](https://github.com/niekBr))
- Allowed php 8.0

### Changed
- changed namespace to correct names

## Version 0.8.1

### Changed
- Change redirect url to include query string

## Version 0.8

### Changed
- Fixed a bug

## Version 0.7

### Added
- Configuration option for required 2fa

## Version 0.6

### Added
- support for laravel 8

## Version 0.5

### Added
- added bootstrap 3 views, default is still bootstrap 4
- registers @error and @enderror blade directives if laravel version is < 5.8.13

### Changed
- change how migrations work / are published

### Fixed
- fixed view publishing bug
- removed old() from provide & setup token views
- fixed input types of provide & setup token views

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
