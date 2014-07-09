SSO authentication for Symfony2
===============================


This bundle helps you to bring SSO authentication to your Symfony2 project.


It works in two ways:

-   **trusted**: authentication is done against a known server (like with CAS)
-   **open**: authentication is done with server of user's choice (like with OpenId)


Only CAS protocol is implemented for now, many other are planned.


-   [Read documentation](https://github.com/BeSimple/BeSimpleSsoAuthBundle/blob/master/Resources/doc/index.md)
-   [See the license](https://github.com/BeSimple/BeSimpleSsoAuthBundle/blob/master/Resources/meta/LICENSE)

Support for SAML protocol is implemented.

**Heads up!** This branch, `saml`, is no longer supported. Please use the `saml2`
branch instead. The `saml2` branch uses the official `onelogin/php-saml`
toolkit instead of a fork. The official toolkit also performs much more robust
validation of SAML responses.

- [SAML documentation](https://github.com/uq-its-ss/BeSimpleSsoAuthBundle/blob/saml/Resources/doc/saml.md)

