# Composer Spryker Package template

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.0-8892BF.svg)](https://php.net/)

This module is used alongside `valantic-spryker/sitemap` Sitemap module to extend the sitemap with concrete product URLs.

## Integration

### Add composer registry
```
composer config repositories.gitlab.nxs360.com/461 '{"type": "composer", "url": "https://gitlab.nxs360.com/api/v4/group/461/-/packages/composer/packages.json"}'
```

### Add Gitlab domain
```
composer config gitlab-domains gitlab.nxs360.com
```

### Authentication
Go to Gitlab and create a personal access token. Then create an **auth.json** file:
```
composer config gitlab-token.gitlab.nxs360.com <personal_access_token>
```

# Usage

## 1. Install package

```shell
composer require valantic-spryker/product-concrete-sitemap-connector
```

## 2. Update `config_default.php` file

Add the `ValanticSpryker` namespace in the following config keys:

```php
$config[KernelConstants::CORE_NAMESPACES] = [
    // [...]
    'ValanticSpryker',
];
$config[KernelConstants::PROJECT_NAMESPACES] = [
    // [...]
    'ValanticSpryker',
];
```

## 3. Register `ProductConcreteSitemapCreatorPlugin`

Add `ProductConcreteSitemapCreatorPlugin` in:
```php
\Pyz\Zed\Sitemap\SitemapDependencyProvider::getSitemapCreatorPluginStack
```

## 4. Register `ProductConcreteSitemapConnectorConstants`

Add `ProductConcreteSitemapConnectorConstants` in:
```php
\Pyz\Yves\Sitemap\SitemapDependencyProvider::getAvailableSitemapRouteResources
```

