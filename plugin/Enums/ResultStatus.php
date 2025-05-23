<?php

namespace Charm\Enums;

/**
 * Indicates the status of a result.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
enum ResultStatus: string
{
    // The operation succeeded
    case Success = 'success';

    // The operation succeeded with additional information
    case Info = 'info';

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
            self::Info => __('Info', 'charm'),
            self::Error => __('Error', 'charm'),
        };
    }
}
