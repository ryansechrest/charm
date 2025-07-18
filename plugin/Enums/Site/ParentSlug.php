<?php

namespace Charm\Enums\Site;

/**
 * Indicates the parent slug of a WordPress site admin page.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
enum ParentSlug: string
{
    case Dashboard = 'index.php';

    case Posts = 'edit.php';

    case Media = 'upload.php';

    case Pages = 'edit.php?post_type=page';

    case Comments = 'edit-comments.php';

    case Appearance = 'themes.php';

    case Plugins = 'plugins.php';

    case Users = 'users.php';

    case Tools = 'tools.php';

    case Settings = 'options-general.php';
}