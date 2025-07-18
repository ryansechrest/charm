<?php

namespace Charm\Enums\Site;

/**
 * Indicates the menu position in the WordPress site admin.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
enum MenuPosition: int
{
    case Dashboard = 2;

    case Separator1 = 4;

    case Posts = 5;

    case Media = 10;

    case Links = 15;

    case Pages = 20;

    case Comments = 25;

    case Separator2 = 59;

    case Appearance = 60;

    case Plugins = 65;

    case Users = 70;

    case Tools = 75;

    case Settings = 80;

    case Separator3 = 99;
}