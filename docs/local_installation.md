:small_orange_diamond: **Note:** The following document is written with OS X and Linux readers in mind.

:small_orange_diamond: **Note:** This document is still in progress and needs revision.

# Local Installation

The Make/Model prototyping environment can be installed on your local machine. Here's how:

### Establish a local PHP server

We recommend using a tool like [MAMP](http://www.mamp.info/en/) or [PHP’s built-in web server](http://www.php.net/manual/en/features.commandline.webserver.php), however, prototypes will run in most any hosting environment with PHP and [cURL](https://en.wikipedia.org/wiki/CURL) functionality available. 

### Install Composer

We use the popular [Composer](https://getcomposer.org/) framework to manage dependencies. For Prototypes, the main dependency is [Make/Model](https://github.com/athletics/make-model).

Composer relies on the `composer.json` file to manage and install the necessary dependencies.

You can install Composer by following the instructions here:
<https://getcomposer.org/download/>

This will ultimately create a file called `composer.phar` on your machine. You can place this file in whatever location you choose. We recommend a simple location such as:

`/Users/janedoe/composer.phar`

Next, you may find it useful to create an alias to Composer so that it's always available in Terminal. Here's one way of doing that:

Add an alias to `.bash_profile`. On OS X, the file exists here:

`/Users/janedoe/.bash_profile`

If this file doesn't already exist, you can create it.

The following line can be added to `.bash_profile` to provide a handy shortcut to Composer:

```bash
alias composer="php ~/composer.phar"
```

You'll want to make sure that you provide the correct path to your `composer.phar` file. In the example above, we use `~/` as a shortcut to your user directory (i.e. `/Users/janedoe/`).

> **Note:** `.bash_profile` is a hidden file, and you won't see it unless you choose to [show hidden files](http://osxdaily.com/2009/02/25/show-hidden-files-in-os-x/) in OS X. Or you can leave hidden files hidden (they can be a bit overwhelming) and use your text editor to toggle visibility of hidden files. In [TextMate](http://macromates.com/), you'll find [a simple toggle](https://www.dropbox.com/s/15anav7yxnsk600/Screenshot%202014-05-21%2012.32.47.png) for showing hidden files within the `Open` dialog box. In [Sublime Text](http://www.sublimetext.com/) (our editor of choice), you can press `cmd + shift + .` within the `Open` dialog box to toggle visibility.

### Install Project Dependencies using Composer

Now that you have Composer installed, let's have it install the dependencies that we need.

Composer gets its instructions from `composer.json`.

In a Terminal window, navigate to the location of Prototype repository, specifically the directory containing `composer.json`.

Now run:

```bash
composer install
```

This will download and install a variety of files, placing them in the `vendor` directory.

### Adjust permissions

Make/Model will need the ability to write to a number of directories. We recommend opening up permissions on the following directory:

`vendor/athletics/make-model/app`

The easiest way to do this on OS X is to navigate to this directory, press `cmd + i` and change the privileges to `Read & Write` for the available users, and apply these changes to the enclosed items as well.

### Create config.php

In the `base-project/config` directory you'll see a file called `config.example.php`.

You'll need to make your own version of this file called `config.php` in the same directory using the appropriate `url` and `password` for the prototyping environment.

:checkered_flag: