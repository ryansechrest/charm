<?php

namespace Charm\Models;

use Charm\Traits\User\Fields;
use Charm\Traits\User\Metas;
use Charm\Traits\User\WithRole;

/**
 * Represents a user in WordPress.
 *
 * @package Charm
 */
class User extends Base\User
{
    // --- User Fields ---------------------------------------------------------

    use Fields\WithCreatedAt;

    use Fields\WithUsername;
    use Fields\WithSlug;
    use Fields\WithEmail;
    use Fields\WithPassword;

    use Fields\WithDisplayName;
    use Fields\WithWebsite;

    // --- User Metas ----------------------------------------------------------

    use Metas\WithFirstName;
    use Metas\WithLastName;
    use Metas\WithNickname;
    use Metas\WithBioInfo;

    // --- User Helpers --------------------------------------------------------

    use WithRole;
}
