# Charm

Charm started on Feb 29, 2020, but is still in its early stages and continuously evolving. If you're curious about how Charm works under the hood, have a look around or take it for a spin in a development environment.

## Prerequisites

- PHP 8.0 or higher

## Getting Started

Open up Terminal and `cd` into your project's directory. It's the one with your `wp-config.php` file.

### 1. Create `mu-plugins` directory

Create the `mu-plugins` directory (unless it already exists):

```shell
mkdir -p wp-content/mu-plugins
```

### 2. Add Charm as submodule to `mu-plugins` directory

Add Charm as a submodule called `charm` in the `wp-content/mu-plugins` directory:

```shell
git submodule add https://github.com/ryansechrest/charm.git wp-content/mu-plugins/charm
```

Your `mu-plugins` directory should now look something like this:

```
|-- mu-plugins
|   |-- charm
|   |   |-- admin
|   |   |-- ...
|   |   |-- wordpress
|   |   |-- autoloader.php
|   |   |-- Charm.php
|   |   |-- cli.php
|   |   |-- MuPlugin.php
|   |   |-- plugin.php
|   |   |-- README.md
```

### 3. Activate Charm

Activate Charm by copying `plugin.php` into the root of your `mu-plugins` directory as `_charm.php`, or run:

```shell
php wp-content/mu-plugins/charm/cli.php activate
```

Your `mu-plugins` directory should now look something like this:

```
|-- mu-plugins
|   |-- charm
|   |   |-- admin
|   |   |-- ...
|   |   |-- wordpress
|   |   |-- autoloader.php
|   |   |-- Charm.php
|   |   |-- cli.php
|   |   |-- MuPlugin.php
|   |   |-- plugin.php
|   |   |-- README.md
|   |-- _charm.php
```

The reason for the underscore is so that Charm loads as early as possible.

This allows you to use Charm in your plugins, themes, or even other mu-plugins.

You're all set from here!

---

### Configure Charm

Charm offers features that can be enabled by editing your `_charm.php` file:

```php
Charm::init([

    /**
     * View cron events and schedules in WordPress admin
     * 
     * Navigate to: Tools > Cron Viewer
     *
     * true  | Enable cron viewer
     * false | Disable cron viewer (default)
     */
    'cron_viewer' => false,

]);
```

---

### Update Charm

Update Charm by running:

```shell
php wp-content/mu-plugins/charm/cli.php update
```

---

### Deactivate Charm

Temporarily deactivate Charm by removing `_charm.php`, or run:

```shell
php wp-content/mu-plugins/charm/cli.php deactivate
```

---

### Remove Charm

Remove all the submodule references and Charm files.

#### 1. Remove submodule from `.git/config` file

Run the following command:

```
git submodule deinit wp-content/mu-plugins/charm
```

-OR- remove the following lines from the `.git/config` file:

```
[submodule "wp-content/mu-plugins/charm"]
  url = https://github.com/ryansechrest/charm.git
  active = true
```

#### 2. Remove submodule from `.gitmodules` file

Remove the following lines from the `.gitmodules` file:

```
[submodule "wp-content/mu-plugins/charm"]
  path = wp-content/mu-plugins/charm
  url = https://github.com/ryansechrest/charm.git
```

#### 3. Remove submodule from `.git/modules` directory:

Run the following command:

```
rm -rf .git/modules/wp-content/mu-plugins/charm
```

-OR- if you have no other submodules in the `wp-content` directory:

```
rm -rf .git/modules/wp-content
```

#### 4. Remove Charm files

Run the following commands:

```
rm wp-content/mu-plugins/_charm.php
rm -rf wp-content/mu-plugins/charm
```

---

Thanks for checking out Charm, and if you have any feedback, <a href="https://ryansechrest.com/contact/">I'd love to hear it</a>!

`Ƹ̵̡Ӝ̵̨̄Ʒ`
