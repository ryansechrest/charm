<?php

namespace Charm\Enums\Result;

/**
 * Indicates the status of a result.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
enum Status: string
{
    // The operation succeeded
    case Success = 'success';

    // The operation succeeded but triggered a warning
    case Warning = 'warning';

    // The operation failed
    case Error = 'error';

    // *************************************************************************

    /**
     * Get the status code.
     *
     * @return string
     */
    public function code(): string
    {
        return $this->value;
    }

    /**
     * Get the status label.
     *
     * @return string
     */
    public function label(): string {
        return match ($this) {
            self::Success => __('Success', 'charm'),
            self::Warning => __('Warning', 'charm'),
            self::Error => __('Error', 'charm'),
        };
    }
}
