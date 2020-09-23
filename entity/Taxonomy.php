<?php

namespace Charm\Entity;

use Charm\Entity\Term as Term;
use Charm\WordPress\Taxonomy as WpTaxonomy;

/**
 * Class Taxonomy
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class Taxonomy extends WpTaxonomy
{
    /************************************************************************************/
    // Constants

    /**
     * Term class
     *
     * @var string
     */
    const TERM = 'Charm\Entity\Term';

    /************************************************************************************/
    // Properties

    /**
     * Object ID
     *
     * @var int
     */
    protected $object_id = 0;

    /**
     * Taxonomy terms
     *
     * @var array
     */
    protected $terms = [];

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['object_id'])) {
            $this->object_id = $data['object_id'];
        }
        $data['taxonomy'] = static::taxonomy();
        parent::load($data);
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize taxonomy
     *
     * @param string $key
     * @return static|null
     */
    public static function init($key = ''): ?Taxonomy
    {
        $key = static::taxonomy();

        return parent::init($key);
    }

    /**
     * Get taxonomies
     *
     * @param array $params
     * @return static[]
     */
    public static function get(array $params = []): array
    {
        $params['taxonomy'] = static::taxonomy();

        return parent::get($params);
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get taxonomy name
     *
     * @return string
     */
    public static function taxonomy(): string
    {
        return '';
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get terms
     *
     * @param array $params
     * @return Term|Term[]
     */
    public function get_terms($params = [])
    {
        if (!isset($params['object_ids'])) {
            $params['object_ids'] = $this->object_id;
        }
        if (!isset($params['taxonomy'])) {
            $params['taxonomy'] = $this->name;
        }
        return call_user_func(
            static::TERM . '::get', $params
        );
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get object ID
     *
     * @return int
     */
    public function get_object_id(): int
    {
        return $this->object_id;
    }

    /**
     * Set object ID
     *
     * @param int $object_id
     */
    public function set_object_id(int $object_id): void
    {
        $this->object_id = $object_id;
    }
}