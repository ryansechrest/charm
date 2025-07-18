<?php

namespace Charm\Enums\Network;

/**
 * Indicates the parent slug of a WordPress network admin page.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
enum ParentSlug: string
{
    case Dashboard = 'index.php';

    case Sites = 'sites.php';

    case Users = 'users.php';

    case Themes = 'themes.php';

    case Plugins = 'plugins.php';

    case Settings = 'settings.php';
}