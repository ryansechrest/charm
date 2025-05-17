<?php

namespace Charm\Enums\User;

/**
 * Indicates the status of a user.
 *
 * Table: `wp_user`
 * Column: `user_status`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
enum Status: int
{
    // User is active
    case ACTIVE = 0;
}