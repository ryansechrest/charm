<?php

namespace Charm\Entity;

use Charm\Feature\Meta as MetaFeature;
use Charm\WordPress\Term as WpTerm;

/**
* Class Term
*
* @author Ryan Sechrest
* @package Charm\Entity
*/
class Term extends WpTerm
{
    use MetaFeature;

    /************************************************************************************/
    // Constants

    /**
     * Meta class
     *
     * @var string
     */
    const META = 'Charm\Entity\TermMeta';
}