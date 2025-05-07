<?php

namespace Charm\Models;

use Charm\Traits\User\Fields\WithCreatedAt;
use Charm\Traits\User\Fields\WithDisplayName;
use Charm\Traits\User\Fields\WithEmail;
use Charm\Traits\User\Fields\WithPassword;
use Charm\Traits\User\Fields\WithSlug;
use Charm\Traits\User\Fields\WithUsername;
use Charm\Traits\User\Fields\WithWebsite;
use Charm\Traits\User\Metas\WithBioInfo;
use Charm\Traits\User\Metas\WithFirstName;
use Charm\Traits\User\Metas\WithLastName;
use Charm\Traits\User\Metas\WithNickname;
use Charm\Traits\User\WithRole;

/**
 * Represents a user in WordPress.
 *
 * @package Charm
 */
class User extends Base\User
{
    // --- User Fields ---------------------------------------------------------

    use WithCreatedAt;

    use WithUsername;
    use WithSlug;
    use WithEmail;
    use WithPassword;

    use WithDisplayName;
    use WithWebsite;

    // --- User Metas ----------------------------------------------------------

    use WithFirstName;
    use WithLastName;
    use WithNickname;
    use WithBioInfo;

    // --- User Helpers --------------------------------------------------------

    use WithRole;
}
