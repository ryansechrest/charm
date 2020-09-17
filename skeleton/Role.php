<?php

namespace Charm\Skeleton;

use Charm\Module\Role as RoleModule;

/**
 * Class Role
 *
 * @author Ryan Sechrest
 * @package Charm\Skeleton
 */
abstract class Role
{
    /************************************************************************************/
    // Action methods

    /**
     * Delete role
     */
    public static function delete(): void
    {
        if (!$role = static::role()) {
            return;
        }
        $role->delete();
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get role
     *
     * @return RoleModule|null
     */
    abstract public static function role(): ?RoleModule;

    /*----------------------------------------------------------------------------------*/

    /**
     * Get name
     *
     * @return string
     */
    public static function name(): string
    {
        return static::role()->get_name();
    }
}