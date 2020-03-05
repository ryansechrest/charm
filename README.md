# Charm

## Getting Started

Open up Terminal and `cd` into your project's directory. It's the one with your `wp-config.php` file.

### 1. Create `mu-plugins` directory

Create the `mu-plugins` directory if it doesn't already exist:

```shell
mkdir -p wp-content/mu-plugins
```

### 2. Clone Charm into `mu-plugins` directory

Clone Charm into its own directory called `charm` in `mu-plugins`:

```shell
cd wp-content/mu-plugins
git clone https://github.com/ryansechrest/charm.git
```

Your `mu-plugins` directory should now look something like this:

```shell
|-- mu-plugins
|   |-- charm
|   |   |-- wordpress
|   |   |-- autoloader.php
|   |   |-- Charm.php
|   |   |-- plugin.php
|   |   |-- README.md
|   |   |-- run
```

### 3. Activate Charm

Activate Charm by copying `plugin.php` into the root of your `mu-plugins` directory and rename it to `_charm.php`, or run:

```shell
php charm/run.php activate
```

Your `mu-plugins` directory should now look something like this:

```shell
|-- mu-plugins
|   |-- charm
|   |   |-- wordpress
|   |   |-- autoloader.php
|   |   |-- Charm.php
|   |   |-- plugin.php
|   |   |-- README.md
|   |   |-- run.php
|   |-- _charm.php
```

The reason for the underscore is so that Charm loads as early as possible, which means you can use it in your plugin, theme, or even another mu-plugin.

That's all there is to it. Charm is ready to go!

### Update Charm

Update Charm by running:

```shell
php charm/run.php update
```

### Deactivate Charm

Temporarily deactivate Charm by removing `_charm.php`, or run:

```shell
php charm/run.php deactivate
```

### Remove Charm

Permanently remove Charm by deleting `_charm.php` and the `charm` directory in `mu-plugins`:

```shell
rm _charm.php
rm -rf charm
```