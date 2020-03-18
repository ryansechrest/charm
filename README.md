# Charm

Charm is still in its very early stages, but it needs a repository for safekeeping. Proceed at your own risk!

## Getting Started

Open up Terminal and `cd` into your project's directory. It's the one with your `wp-config.php` file.

### 1. Create `mu-plugins` directory

Create the `mu-plugins` directory (unless it already exists):

```shell
mkdir -p wp-content/mu-plugins
```

### 2. Clone Charm into `mu-plugins` directory

Clone Charm into the `mu-plugins` directory as `charm`:

```shell
cd wp-content/mu-plugins
git clone https://github.com/ryansechrest/charm.git
```

Your `mu-plugins` directory should now look something like this:

```
|-- mu-plugins
|   |-- charm
|   |   |-- app
|   |   |-- data-type
|   |   |-- feature
|   |   |-- module
|   |   |-- wordpress
|   |   |-- autoloader.php
|   |   |-- Charm.php
|   |   |-- plugin.php
|   |   |-- README.md
|   |   |-- run.php
```

### 3. Activate Charm

Activate Charm by copying `plugin.php` into the root of your `mu-plugins` directory as `_charm.php`, or run:

```shell
php charm/cli.php activate
```

Your `mu-plugins` directory should now look something like this:

```
|-- mu-plugins
|   |-- charm
|   |   |-- app
|   |   |-- data-type
|   |   |-- feature
|   |   |-- module
|   |   |-- wordpress
|   |   |-- autoloader.php
|   |   |-- Charm.php
|   |   |-- plugin.php
|   |   |-- README.md
|   |   |-- run.php
|   |-- _charm.php
```

The reason for the underscore is so that Charm loads as early as possible.

That allows you to use Charm in your plugin, theme, or even another mu-plugin.

You're all set from here!

---

### Update Charm

Update Charm by running:

```shell
php charm/cli.php update
```

### Deactivate Charm

Temporarily deactivate Charm by removing `_charm.php`, or run:

```shell
php charm/cli.php deactivate
```

### Remove Charm

Permanently remove Charm by deleting `_charm.php` and the `charm` directory in `mu-plugins`:

```shell
rm _charm.php
rm -rf charm
```

Please make sure you're not actually using Charm when you do this, otherwise prepare for your error logs to blow up.

`Ƹ̵̡Ӝ̵̨̄Ʒ`