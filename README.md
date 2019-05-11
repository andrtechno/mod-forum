mod-forum
===========
Module for PIXELION CMS

[![Latest Stable Version](https://poser.pugx.org/panix/mod-forum/v/stable)](https://packagist.org/packages/panix/mod-forum) [![Total Downloads](https://poser.pugx.org/panix/mod-forum/downloads)](https://packagist.org/packages/panix/mod-forum) [![Monthly Downloads](https://poser.pugx.org/panix/mod-forum/d/monthly)](https://packagist.org/packages/panix/mod-forum) [![Daily Downloads](https://poser.pugx.org/panix/mod-forum/d/daily)](https://packagist.org/packages/panix/mod-forum) [![Latest Unstable Version](https://poser.pugx.org/panix/mod-forum/v/unstable)](https://packagist.org/packages/panix/mod-forum) [![License](https://poser.pugx.org/panix/mod-forum/license)](https://packagist.org/packages/panix/mod-forum)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer require --prefer-dist panix/mod-forum "*"
```

or add

```
"panix/mod-forum": "*"
```

to the require section of your `composer.json` file.

Add to web config.
```
'modules' => [
    'forum' => ['class' => 'panix\mod\forum\Module'],
],
```

