<?php

namespace Charm\Enums\Network;

/**
 * Indicates the menu position in the WordPress network admin.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
enum MenuPosition: int
{
    case Dashboard = 2;

    case Separator1 = 4;

    case Sites = 5;

    case Users = 10;

    case Themes = 15;

    case Plugins = 20;

    case Settings = 25;

    case Updates = 30;

    case Separator2 = 99;
}