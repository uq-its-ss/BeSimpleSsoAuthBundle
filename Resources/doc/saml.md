SAML Authentication with Symfony2
=================================

This feature should be considered experimental. To make this work in your
app, be prepared to implement some hacks. Feedback and pull requests are
welcome.

Here (some of) the caveats and hacks:

* Uses `onelogin/php-saml` to handle the SAML protocol
* `onelogin/php-saml` integration has been 'smashed' into this bundle. The
  bundle as it stands, is very CAS centric.
* Logout is not supported. If you want to logout from your app, you need to
  create your own controller action for this. More details below.
* You must set `create_users` to be `true`, to support the next hack.
* Your implementation of `UserProviderInterface` must also implement
  `UserFactoryInterface`. This will allow you to retrieve the attributes
  payload from a SAML authentication response. More details below.

Still feeling brave? :)


Configure Composer dependency and install the bundles
-----------------------------------------------------

Add the following repositories:

    {
        "repositories": [
            {
                "type": "vcs",
                "url": "git://github.com/uq-its-ss/BeSimpleSsoAuthBundle.git"
            },
            {
                "type": "vcs",
                "url": "git://github.com/uq-its-ss/php-saml.git"
            }
        ]
    }

Add the following dependencies:

    {
        "require": {
            "onelogin/php-saml": "dev-composer",
            "besimple/sso-auth-bundle": "dev-saml"
        }
    }



Configure SAML
--------------

In `app/config/security.yml` or wherever you elect to put your security
configs:

    be_simple_sso_auth:
        saml_sso:
            protocol:
                id: saml
                idp_sso_url:     %idp_sso_url%
                idp_certificate: %idp_certificate%
                sp_issuer:       %sp_issuer%
                sp_callback_url: %sp_callback_url%
                name_id_format:  %name_id_format%
            server:
                id: saml
                idp_sso_url:     %idp_sso_url%
                idp_certificate: %idp_certificate%
                sp_issuer:       %sp_issuer%
                sp_callback_url: %sp_callback_url%
                name_id_format:  %name_id_format%

* `idp_sso_url`: This is the URL to your SAML IdP
* `idp_certificate`: The contents of your SAML IdP's X.509 public certificate
* `sp_issuer`: I usually use the application's base URL, eg. `https://example.org/`
* `sp_callback_url`: The `check_path` value tacked onto the application's base URL,
  eg. `https://example.org/login_check`
* `name_id_format`: The name of a constant in [`OneLogin_Saml_Settings`][1] or
  a value like `urn:oasis:names:tc:SAML:2.0:nameid-format:persistent`

I recommend putting these values in `parameters.yml`.

[1]: https://github.com/onelogin/php-saml/blob/master/src/OneLogin/Saml/Settings.php


Create a firewall
-----------------

    security:
        firewalls:
            my_firewall:
                pattern: ^/
                trusted_sso:
                    manager: saml_sso
                    login_path: /login
                    check_path: /login_check
                    login_action: false
                    logout_action: false
                    create_users: true
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

    use BeSimple\SsoAuthBundle\Security\Core\User\UserFactoryInterface;
    use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Symfony\Component\Security\Core\User\UserProviderInterface;

    class MyUserProvider implements UserProviderInterface, UserFactoryInterface
    {
        public function loadUserByUsername($username)
        {
            // $username is not a username, it is a SAML identifier.
            // This is a workaround to Symfony's username centric workflow.
            // This will trigger the `createUser` call in SsoAuthBundle when
            // createUsers is configured to `true`.
            throw new UsernameNotFoundException();
        }

        public function createUser($username, array $roles, array $attributes)
        {
            // Again, $username is a SAML identifier. Replace with information
            // from attributes hash. The information in this hash is dependendent
            // on what your IdP provides.

            // You will need to return a user object that implements
            // UserInterface.

            $user = ...

            return $user;
        }

        public function refreshUser(UserInterface $user)
        {
            // Load user roles, etc.
            return $user;
        }

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


