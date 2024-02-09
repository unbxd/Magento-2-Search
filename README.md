# Unbxd Product Feed Module For Magento 2

[![Latest Stable Version](https://poser.pugx.org/unbxd/magento2-search-js/v/stable)](https://packagist.org/packages/unbxd/magento2-search-js)
[![Total Downloads](https://poser.pugx.org/unbxd/magento2-search-js/downloads)](https://packagist.org/packages/unbxd/magento2-search-js)
[![License](https://poser.pugx.org/unbxd/magento2-search-js/license)](https://packagist.org/packages/unbxd/magento2-search-js)
[![composer.lock](https://poser.pugx.org/unbxd/magento2-search-js/composerlock)](https://packagist.org/packages/unbxd/magento2-search-js)

This module provide possibility for synchronization product catalog with Unbxd service.

Support Magento 2.1.\* || 2.2.\* || 2.3.\* || 2.4.\*

# Installation Guide

### Install by composer

```
composer require unbxd/magento2-search-js
php bin/magento module:enable Unbxd_SearchJs
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento cache:flush
```

### Manual installation

1. Download this module [Link](https://github.com/unbxd/Magento-2-Search/archive/1.0.39.zip)
3. Unzip module in the folder:

    app\code\Unbxd\SearchJs

4. Access the root of you Magento 2 instance from command line and run the following commands:

```
php bin/magento module:enable Unbxd_SearchJs
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento cache:flush
```

5. Configure module in backend


