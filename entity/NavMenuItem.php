<?php

namespace Charm\Entity;

use Charm\Conductor\ObjectTaxonomy;

/**
 * Class NavMenuItem
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class NavMenuItem extends Post
{
    /************************************************************************************/
    // Properties

    /**
     * Menu item parent object
     *
     * @var NavMenuItem|null
     */
    protected $menu_item_parent_obj = null;

    /************************************************************************************/
    // Object access methods

    /**
     * Get post type
     *
     * @return string
     */
    public static function post_type(): string
    {
        return 'nav_menu_item';
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get nav menu taxonomy
     *
     * @return ObjectTaxonomy
     */
    public function nav_menu(): ObjectTaxonomy
    {
        $name = NavMenu::taxonomy();
        if (isset($this->taxonomies[$name])) {
            return $this->taxonomies[$name];
        }
        $object_taxonomy = ObjectTaxonomy::init($this->id, $name);
        $object_taxonomy->set_term_class(NavMenu::class);

        return $this->taxonomies[$name] = $object_taxonomy;
    }

    /**
     * Get menu item parent
     *
     * @return NavMenuItem|null
     */
    public function menu_item_parent(): ?NavMenuItem
    {
        if ($this->menu_item_parent_obj) {
            return $this->menu_item_parent_obj;
        }
        if (!$this->get_menu_item_parent()) {
            return null;
        }

        return $this->menu_item_parent_obj = static::init($this->get_menu_item_parent());
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get menu item URL
     *
     * @return string
     */
    public function get_menu_item_url(): string
    {
        return $this->meta('_menu_item_url')->string();
    }

    /**
     * Set menu item URL
     *
     * @param string $url
     */
    public function set_menu_item_url(string $url): void
    {
        $this->meta('_menu_item_url', $url);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get menu item XFN
     *
     * @return string
     */
    public function get_menu_item_xfn(): string
    {
        return $this->meta('_menu_item_xfn')->string();
    }

    /**
     * Set menu item XFN
     *
     * @param string $xfn
     */
    public function set_menu_item_xfn(string $xfn): void
    {
        $this->meta('_menu_item_xfn', $xfn);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get menu item classes
     *
     * @return array
     */
    public function get_menu_item_classes(): array
    {
        return $this->meta('_menu_item_classes')->array();
    }

    /**
     * Set menu item classes
     *
     * @param array $classes
     */
    public function set_menu_item_classes(array $classes): void
    {
        $this->meta('_menu_item_classes', $classes);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get menu item target
     *
     * @return string
     */
    public function get_menu_item_target(): string
    {
        return $this->meta('_menu_item_target')->string();
    }

    /**
     * Set menu item target
     *
     * @param string $target
     */
    public function set_menu_item_target(string $target): void
    {
        $this->meta('_menu_item_target', $target);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get menu item object
     *
     * @return string
     */
    public function get_menu_item_object(): string
    {
        return $this->meta('_menu_item_object')->string();
    }

    /**
     * Set menu item object
     *
     * @param string $object
     */
    public function set_menu_item_object(string $object): void
    {
        $this->meta('_menu_item_object', $object);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get menu item object ID
     *
     * @return int
     */
    public function get_menu_item_object_id(): int
    {
        return $this->meta('_menu_item_object_id')->int();
    }

    /**
     * Set menu item object ID
     *
     * @param int $object_id
     */
    public function set_menu_item_object_id(int $object_id): void
    {
        $this->meta('_menu_item_object_id', $object_id);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get menu item parent
     *
     * @return int
     */
    public function get_menu_item_parent(): int
    {
        return $this->meta('_menu_item_menu_item_parent')->int();
    }

    /**
     * Set menu item parent
     *
     * @param int $parent
     */
    public function set_menu_item_parent(int $parent): void
    {
        $this->meta('_menu_item_menu_item_parent', $parent);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get menu item type
     *
     * @return string
     */
    public function get_menu_item_type(): string
    {
        return $this->meta('_menu_item_type')->string();
    }

    /**
     * Set menu item type
     *
     * @param string $type
     */
    public function set_menu_item_type(string $type): void
    {
        $this->meta('_menu_item_type', $type);
    }
}