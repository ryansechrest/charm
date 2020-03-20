# Charm

Charm is still in its very early stages, but it needs a repository for safekeeping. There is no documentation (yet) on how to use Charm, because it's still ever-evolving. Proceed at your own risk, or just poke around the code to take a look.

## Getting Started

Open up Terminal and `cd` into your project's directory. It's the one with your `wp-config.php` file.

### 1. Create `mu-plugins` directory

Create the `mu-plugins` directory (unless it already exists):

```shell
mkdir -p wp-content/mu-plugins
```

### 2. Add Charm as submodule to `mu-plugins` directory

Add Charm as a submodule in the `wp-content/mu-plugins/charm` directory:

```shell
git submodule add https://github.com/ryansechrest/charm.git wp-content/mu-plugins/charm
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
php wp-content/mu-plugins/charm/cli.php activate
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

This allows you to use Charm in your plugins, themes, or even other mu-plugins.

You're all set from here!

---

### Update Charm

Update Charm by running:

```shell
php wp-content/mu-plugins/charm/cli.php update
```

### Deactivate Charm

Temporarily deactivate Charm by removing `_charm.php`, or run:

```shell
php wp-content/mu-plugins/charm/cli.php deactivate
```

### Remove Charm

Remove all the submodule references and then the Charm files.

#### 1. Remove submodule from `.git/config`

Run the following command:

```
git submodule deinit wp-content/mu-plugins/charm
```

-OR- manually the following lines:

```
[submodule "wp-content/mu-plugins/charm"]
	url = https://github.com/ryansechrest/charm.git
	active = true
```

#### 2. Remove submodule from `.gitmodules`

Remove the following lines:

```
[submodule "wp-content/mu-plugins/charm"]
	path = wp-content/mu-plugins/charm
	url = https://github.com/ryansechrest/charm.git
```

#### 3. Remove Charm files

```
rm wp-content/mu-plugins/_charm.php
rm -rf wp-content/mu-plugins/charm
```

Please make sure you're not actually using Charm when you do this, otherwise prepare for your error logs to blow up.

`Ƹ̵̡Ӝ̵̨̄Ʒ`
