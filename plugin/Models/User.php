<?php

namespace Charm\Models;

use Charm\Traits\User\Fields\HasCreatedAt;
use Charm\Traits\User\Fields\HasDisplayName;
use Charm\Traits\User\Fields\HasEmail;
use Charm\Traits\User\Fields\HasPassword;
use Charm\Traits\User\Fields\HasUsername;
use Charm\Traits\User\Fields\HasWebsite;

/**
 * Represents a user in WordPress.
 *
 * @package Charm
 */
class User extends Base\User
{
    // --- User Fields ---------------------------------------------------------

    use HasCreatedAt;

    use HasUsername;
    use HasEmail;
    use HasPassword;

    use HasDisplayName;
    use HasWebsite;
}
