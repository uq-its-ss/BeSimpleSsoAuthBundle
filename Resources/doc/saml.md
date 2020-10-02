SAML Authentication with Symfony2
=================================

This feature should be considered beta quality. Feedback and pull requests are
welcome.

Here are potential caveats to be aware of:

* Uses `onelogin/php-saml` to handle the SAML protocol
* Logout is not supported. If you want to logout from your app, you need to
  create your own controller action for this. More details below.
* Your implementation of `UserProviderInterface` must be changed to implement
  `SamlUserProviderInterface`. This will allow you to map SAML response
  attributes back into a username. More details below.


Configure Composer dependency and install the bundles
-----------------------------------------------------

Add the following repositories:

    {
        "repositories": [
            {
                "type": "vcs",
                "url": "git://github.com/uq-its-ss/BeSimpleSsoAuthBundle.git"
            }
        ]
    }

Add the following dependencies:

    {
        "require": {
            "besimple/sso-auth-bundle": "dev-saml2"
        }
    }



Configure SAML
--------------

In `app/config/security.yml` or wherever you elect to put your security
configs:

    be_simple_sso_auth:
        saml_sso:
            config:
                id: saml
                idp_sso_url:     %idp_sso_url%
                idp_entity_id:   %idp_entity_id%
                idp_certificate: %idp_certificate%
                sp_issuer:       %sp_issuer%
                sp_callback_url: %sp_callback_url%
                name_id_format:  %name_id_format%

* `idp_sso_url`: This is the URL to your SAML IdP
* `idp_entity_id`: The SAML IdP metadata entity ID
* `idp_certificate`: The contents of your SAML IdP's X.509 public certificate
* `sp_issuer`: I usually use the application's base URL, eg. `https://example.org/`
* `sp_callback_url`: The `check_path` value tacked onto the application's base URL,
  eg. `https://example.org/login_check`
* `name_id_format`: The name of a `NAMEID_` constant in [`OneLogin\Saml2\Settings`][1] or
  a value like `urn:oasis:names:tc:SAML:2.0:nameid-format:persistent`

I recommend putting these values in `parameters.yml`.

[1]: https://github.com/onelogin/php-saml/blob/master/lib/Saml2/Constants.php


Create a firewall
-----------------

    security:
        firewalls:
            my_firewall:
                pattern: ^/
                saml_sso:
                    manager: saml_sso
                    login_path: /login
                    check_path: /login_check
                    login_action: false
                    logout_action: false
                    provider: my_user_provider


Enable HTTPS
------------

    security:
        access_control:
            - { path: ^/, roles: [IS_AUTHENTICATED_ANONYMOUSLY, IS_AUTHENTICATED_FULLY], requires_channel: https }


Configure secure cookies
------------------------

    framework:
        session:
            cookie_httponly: true
            cookie_secure: true


Implement your user provider
----------------------------

Here is a sample user provider implementation, with only the relevant parts
included.

    <?php

    use BeSimple\SsoAuthBundle\Security\Core\User\SamlUserProviderInterface;

    class MyUserProvider implements SamlUserProviderInterface
    {

        public function extractUsername($nameId, array $samlAttributes)
        {
            // Look into the $samlAtributes array for a username or
            // your identifier of choice. Return this value. The returned
            // value is used to feed into loadUserByUsername.
            $username = 'blah';
            return $username;
        }

        /* Implement the rest of the UserProviderInterface methods */

    }


Implement logout
----------------

Here is a sample controller for implementing logout.

    <?php

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;

    class LogoutController extends Controller
    {

        /**
         * @Route("/logout", name = "logout")
         * @Method("GET")
         */
        public function logoutAction(Request $request)
        {
            // Delete security tokens, invalidate the user's session.
            $this->get('security.context')->setToken(NULL);
            $request->getSession()->invalidate();

            // Do something to initiate SAML Single Logout, if you have it
            // implemented.

            return $this->redirect('/');
        }

    }
