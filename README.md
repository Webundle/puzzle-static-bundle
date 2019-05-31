# Puzzle Static Bundle

Project based on Symfony project for managing static accounts and static security.

## **Install bundle**

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```yaml
composer require webundle/puzzle-static-bundle
```

## **Step 1: Enable bundle**
Enable admin bundle by adding it to the list of registered bundles in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Puzzle\StaticBundle\StaticBundle(),
        );

        // ...
    }

    // ...
}
```

## **Step 2: Configure bundle security**
Configure security by adding it in the `app/config/security.yml` file of your project:

```yaml
security:
   	...
    role_hierarchy:
        ...
        # User
        ROLE_STATIC: ROLE_ADMIN
        ROLE_SUPER_ADMIN: [..,ROLE_STATIC]
        
	...
    access_control:
        ...
        # User
        - {path: ^%admin_prefix%static, host: "%admin_host%", roles: ROLE_STATIC }

```

## **Step 3: Enable bundle routing**

Register default routes by adding it in the `app/config/routing.yml` file of your project:

```yaml
....
user:
    resource: "@StaticBundle/Resources/config/routing.yml"
    prefix:   /
```
See all static routes by typing: `php bin/console debug:router | grep static`

## **Step 4: Configure bundle**

Configure admin bundle by adding it in the `app/config/config.yml` file of your project:

```yaml
admin:
    ...
    modules_available: '..,static'
    navigation:
        nodes:
            ...
            # Static
            static:
                label: 'static.title'
                description: 'static.description'
                translation_domain: 'static'
                attr:
                    class: 'fa fa-file-text'
                parent: ~
                user_roles: ['ROLE_STATIC']
            static_page:
                label: 'static.page.navigation'
                description: 'static.page.description'
                translation_domain: 'static'
                path: 'puzzle_admin_static_page_list'
                sub_paths: ['puzzle_admin_static_page_create', 'puzzle_admin_static_page_update']
                parent: static
                user_roles: ['ROLE_STATIC']
            static_category:
                label: 'static.category.sidebar'
                description: 'static.category.description'
                translation_domain: 'static'
                path: 'puzzle_admin_static_category_list'
                sub_paths: ['puzzle_admin_static_category_create', 'puzzle_admin_static_category_update']
                parent: static
                user_roles: ['ROLE_STATIC']
```
