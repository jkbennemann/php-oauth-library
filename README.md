Social Connection Authentication
==================

[![Packagist](https://img.shields.io/packagist/v/jkbennemann/php-oauth-library.svg?style=flat-square)](https://packagist.org/packages/jkbennemann/php-oauth-library)
[![License](http://img.shields.io/packagist/l/jkbennemann/php-oauth-library.svg?style=flat-square)](https://github.com/jkbennemann/php-oauth-library/blob/master/LICENSE)

> Open source social sign on PHP. Connect your application(s) with social network(s).

Code examples you can find in [example](./example) directory

This library is a fork from [SocialConnect/auth](https://github.com/SocialConnect/auth)

# Features

* <b>Functional</b>: support [30+ providers]((https://socialconnect.lowl.io/providers.html)) such as Facebook, Google, Twitter, GitHub, Vk and another.
* <b>Completely</b>: We supports all social sign standarts: OAuth1/OAuth2/OpenID/OpenIDConnect.
* <b>Follow standards</b>: We follow PSR-7/PSR-17/PSR-18 standards.
* <b>Modular</b>: Use only what, that you need, see [architecture overview](http://localhost:4000/architecture.html).
* <b>Quality</b>: CodeCoverage with 80%+ and We are using static analyzers.


* <b>Flexible</b>: Change configurations for any provider during runtime

## Supported type of providers

- [x] OAuth1 [spec RFC 5849](https://tools.ietf.org/html/rfc5849)
- [x] OAuth2 [spec RFC 6749](https://tools.ietf.org/html/rfc6749)
- [X] OpenID v1 (1.1) (WIP!) [spec](https://openid.net/specs/openid-authentication-1_1.html)
- [X] OpenID v2 [spec](http://openid.net/specs/openid-authentication-2_0.html)
- [X] OpenID Connect (1.0) [spec](http://openid.net/specs/openid-connect-core-1_0.html#OpenID.Discovery)
    - [X] JWT (JSON Web Token) [spec RFC 7519](https://tools.ietf.org/html/rfc7519)
    - [X] JWK (JSON Web Keys) [spec RFC 7517](https://tools.ietf.org/html/rfc7517)

## Supported providers

`SocialConnect/Auth` support 30+ providers such as Facebook, Google, Twitter, GitHub, Vk and another.

[See all 30+ provider](https://socialconnect.lowl.io/providers.html)

## Installation & Getting Started
> Instead of installing the Socialconnect/auth composer package
> you may install this package `composer require jkbennemann/php-oauth-library` 
 
For further documentation see [Installation & Getting Started](https://socialconnect.lowl.io/installation.html)

## Versions

| Version                                                  | Status      | EOL        | PHP Version |
|----------------------------------------------------------|-------------|------------|-------------|
| [1.x](https://github.com/jkbennemann/php-oauth-library/tree/master) | Current     |     --     | >= 7.1      |

Contributors
============

This project exists thanks to all the people who contribute. Contributions are welcome!

### License

This project is open-sourced software licensed under the MIT License.

See the [LICENSE](LICENSE) file for more information.