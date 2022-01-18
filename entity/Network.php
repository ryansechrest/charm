<?php

namespace Charm\Entity;

use Charm\Feature\Meta as MetaFeature;
use Charm\WordPress\Network as WpNetwork;

/**
 * Class Network
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class Network extends WpNetwork
{
    use MetaFeature;

    /**
     * Meta class
     *
     * @var string
     */
    const META = 'Charm\Entity\NetworkMeta';

    /************************************************************************************/
    // Object access methods

    /**
     * Get network ID
     *
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }
}