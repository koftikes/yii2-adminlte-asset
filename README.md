AdminLTE Asset Bundle
=====================

*Backend UI for Yii2 Framework, based on [AdminLTE](https://github.com/almasaeed2010/AdminLTE)*

This package contains an [Asset Bundle for Yii 2.0 Framework](http://www.yiiframework.com/doc-2.0/guide-structure-assets.html)
which registers the CSS files for the AdminLTE user-interface.

The CSS files are installed via Yii's recommended usage of the `fxp/composer-asset-plugin` v1.1.1 or later.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

To install run:

```
composer require sbs/yii2-adminlte-asset "*"
```

Customization
-------------

- Copy files from `vendor/sbs/yii2-adminlte-asset/example-views` to `@app/views`.
- Remove the custom `view` configuration from your application by deleting the path mappings, if you have made them before.
- Edit your views adhering to html markup `@bower/admin-lte/pages`

### Skins

By default the extension uses blue skin for AdminLTE. You can change it in config file.
```php
'components' => [
    'assetManager' => [
        'bundles' => [
            'sbs\web\AdminLteAsset' => [
                'skin' => 'skin-black',
            ],
        ],
    ],
],
```

Here is the list of available skins:
```
"skin-blue",
"skin-black",
"skin-red",
"skin-yellow",
"skin-purple",
"skin-green",
"skin-blue-light",
"skin-black-light",
"skin-red-light",
"skin-yellow-light",
"skin-purple-light",
"skin-green-light"
```

You can use `AdminLTE::skinClass()` if you don't want to alter every view file when you change skin color.
```html
<body class="<?= \sbs\helpers\AdminLTE::skinClass(); ?>">
```

**Note:** Use `AdminLTE::skinClass()` only if you override the skin through configuration. Otherwise you will not get the correct css class of body.


Further Information
-------------------

For AdminLTE documentation, please read https://almsaeedstudio.com/themes/AdminLTE/documentation/index.html
> Namespace rules follow the Yii 2.0 framework structure, eg. `sbs\web` for the Asset Bundle.
