# user-bundle
Symfony user bundle

# Configuration

```yaml
# config/packages/doctrine.yaml
    orm:
        resolve_target_entities:
            Symfony\Component\Security\Core\User\UserInterface: App\Entity\User
```


```yaml
# config/packages/security.yaml

security:
    encoders:
        Symfony\Component\Security\Core\User\UserInterface: sha512

    # https://symfony.com/doc/current/security.html#where-do-users-come-from-user-providers
    providers:
        # used to reload user from session & other features (e.g. switch_user)
        app_user_provider:
            entity:
                class: Symfony\Component\Security\Core\User\UserInterface
                property: email

        main:
            pattern: ^/
            form_login:
                csrf_token_generator: security.csrf.token_manager
                login_path:     discutea_user_login
                use_forward:    false
                check_path:     app_login

            logout:
                path:   discutea_user_logout
            anonymous:    true
            switch_user:  { role: ROLE_DEVELOPER }
            guard:
                authenticators:
                    - Discutea\UserBundle\Security\FormLoginAuthenticator

```

```yaml
# Add this in symfony recipes
discutea_user:
  resource: "@DiscuteaUserBundle/Controller/"
  type: annotation
```
