<?php

namespace Charm\Entity;

use Charm\DataType\DateTime;
use Charm\Helper\Database;
use Charm\Module\Taxonomy;

/**
 * Class Log
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class Log
{
    /************************************************************************************/
    // Constants

    /**
     * Logs table
     *
     * @var string
     */
    const TABLE = 'logs';

    /**
     * User class
     *
     * @var string
     */
    const USER = 'Charm\Entity\User';

    /**
     * DateTime class
     *
     * @var string
     */
    const DATE_TIME = 'Charm\DataType\DateTime';

    /************************************************************************************/
    // Properties

    /**
     * ID
     *
     * @var int
     */
    protected $id = 0;

    /**
     * User ID
     * @var int
     */
    protected $user_id = 0;

    /**
     * User name
     *
     * @var string
     */
    protected $user_name = '';

    /**
     * Action
     *
     * @var string
     */
    protected $action = '';

    /**
     * Object type
     *
     * @var string
     */
    protected $object_type = '';

    /**
     * Object ID
     *
     * @var int
     */
    protected $object_id = 0;

    /**
     * Object name
     *
     * @var string
     */
    protected $object_name = '';

    /**
     * Sub action
     *
     * @var string
     */
    protected $sub_action = '';

    /**
     * Sub object type
     *
     * @var string
     */
    protected $sub_object_type = '';

    /**
     * Sub object ID
     *
     * @var int
     */
    protected $sub_object_id = 0;

    /**
     * Sub object name
     *
     * @var string
     */
    protected $sub_object_name = '';

    /**
     * Success
     *
     * @var string
     */
    protected $success = 0;

    /**
     * Message
     *
     * @var string
     */
    protected $message = '';

    /**
     * Detail
     *
     * @var null
     */
    protected $detail = null;

    /**
     * Date
     *
     * @var string
     */
    protected $date = '';

    /*----------------------------------------------------------------------------------*/

    /**
     * User object
     *
     * @var User|null
     */
    protected $user_obj = null;

    /**
     * Date object
     *
     * @var DateTime|null
     */
    protected $date_obj = null;

    /*----------------------------------------------------------------------------------*/

    /**
     * Database
     *
     * @var Database
     */
    private $db = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Log constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->db = Database::init();
        if (count($data) === 0) {
            return;
        }
        $this->load($data);
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['id'])) {
            $this->id = (int) $data['id'];
        }
        if (isset($data['user_id'])) {
            $this->user_id = (int) $data['user_id'];
        }
        if (isset($data['user_name'])) {
            $this->user_name = $data['user_name'];
        }
        if (isset($data['action'])) {
            $this->action = $data['action'];
        }
        if (isset($data['object_type'])) {
            $this->object_type = $data['object_type'];
        }
        if (isset($data['object_id'])) {
            $this->object_id = (int) $data['object_id'];
        }
        if (isset($data['object_name'])) {
            $this->object_name = $data['object_name'];
        }
        if (isset($data['sub_action'])) {
            $this->sub_action = $data['sub_action'];
        }
        if (isset($data['sub_object_type'])) {
            $this->sub_object_type = $data['sub_object_type'];
        }
        if (isset($data['sub_object_id'])) {
            $this->sub_object_id = (int) $data['sub_object_id'];
        }
        if (isset($data['sub_object_name'])) {
            $this->sub_object_name = $data['sub_object_name'];
        }
        if (isset($data['success'])) {
            $this->success = (int) $data['success'];
        }
        if (isset($data['message'])) {
            $this->message = $data['message'];
        }
        if (isset($data['detail'])) {
            $this->detail = $data['detail'];
        }
        if (isset($data['date'])) {
            $this->date = $data['date'];
        }
    }

    /************************************************************************************/
    // Setup methods

    /**
     * Create logs table
     */
    public static function create_table()
    {
        $db = Database::init();
        if (!$db->table_exists(static::TABLE)) {
            $db->create_table(static::TABLE, [
                'id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT',
                'user_id bigint(20) UNSIGNED',
                'user_name varchar(255)',
                'action varchar(100) NOT NULL',
                'object_type varchar(100) NOT NULL',
                'object_id bigint(20) UNSIGNED NOT NULL',
                'object_name varchar(255) NOT NULL',
                'sub_action varchar(100)',
                'sub_object_type varchar(100)',
                'sub_object_id bigint(20) UNSIGNED',
                'sub_object_name varchar(255)',
                'success int(1) UNSIGNED NOT NULL',
                'message varchar(255) NOT NULL',
                'detail text',
                'date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'PRIMARY KEY (id)',
                'FOREIGN KEY (user_id) REFERENCES ' . $db->prefix('users') . '(id)',
            ]);
        }
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Log everything
     */
    public static function everything()
    {
        // Posts (and CPTs)
        static::when_post_saved();
        static::when_post_deleted();

        static::when_post_meta_added();
        static::when_post_meta_updated();
        static::when_post_meta_deleted();

        // Terms
        static::when_term_created();
        static::when_term_edited();
        static::when_term_deleted();

        static::when_term_meta_added();
        static::when_term_meta_updated();
        static::when_term_meta_deleted();

        // Term Relationships
        static::when_term_relationship_added();
        static::when_term_relationship_deleted();

        // Users
        static::when_user_registered();
        static::when_user_updated();
        static::when_user_deleted();

        static::when_user_meta_added();
        static::when_user_meta_updated();
        static::when_user_meta_deleted();
    }

    /**
     * Get class name
     *
     * @param string $key
     * @param string $sub_key
     * @return string
     */
    public static function get_class_name(string $key, string $sub_key = ''): string
    {
        $class_names = static::get_class_names();
        if (!isset($class_names[$key])) {
            return '';
        }
        if ($sub_key === '') {
            return $class_names[$key];
        }
        if (!isset($class_names[$key][$sub_key])) {
            return '';
        }

        return $class_names[$key][$sub_key];
    }

    /**
     * Get class names
     *
     * @return string[]
     */
    public static function get_class_names(): array
    {
        return [
            'comment_meta' => CommentMeta::class,
            'page' => Page::class,
            'post' => Post::class,
            'post_meta' => PostMeta::class,
            'taxonomy' => Taxonomy::class,
            'term' => Term::class,
            'term_meta' => TermMeta::class,
            'user' => User::class,
            'user_meta' => UserMeta::class,
        ];
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Log when post is created or updated
     */
    public static function when_post_saved(): void
    {
        add_action('save_post', function($post_id, $post, $update) {
            if (in_array($post->post_type, static::ignore_post_types())) {
                return;
            }
            if (wp_is_post_revision($post_id)) {
                return;
            }
            $class_name = static::get_class_name($post->post_type);
            if ($class_name === '') {
                return;
            }
            /** @var Post $post */
            $post = call_user_func($class_name . '::init', $post_id);
            static::new([
                'action' => $update === true ? 'update' : 'create',
                'object_id' => $post->get_id(),
                'object_type' => $post->get_post_type(),
                'object_name' => $post->get_post_title(),
                'success' => 1,
                'detail' => $post->to_json(),
            ]);
        }, 10, 3);
    }

    /**
     * Log when post is deleted
     */
    public static function when_post_deleted(): void
    {
        add_action('delete_post', function($post_id, $post) {
            if (in_array($post->post_type, static::ignore_post_types())) {
                return;
            }
            $class_name = static::get_class_name($post->post_type);
            if ($class_name === '') {
                return;
            }
            /** @var Post $post */
            $post = call_user_func($class_name . '::init', $post_id);
            static::new([
                'action' => 'delete',
                'object_id' => $post->get_id(),
                'object_type' => $post->get_post_type(),
                'object_name' => $post->get_post_title(),
                'success' => 1,
                'detail' => $post->to_json(),
            ]);
        }, 10, 2);
    }

    /**
     * Ignore specified post types from being logged
     *
     * @return array
     */
    public static function ignore_post_types(): array
    {
        return [];
    }

    /**
     * Log when post meta is added
     */
    public static function when_post_meta_added(): void
    {
        add_action('added_post_meta', function($meta_id, $object_id, $meta_key, $meta_value) {
            if (in_array($meta_key, static::ignore_post_metas())) {
                return;
            }
            $class_name = static::get_class_name(get_post_type($object_id));
            if ($class_name === '') {
                return;
            }
            /** @var Post $post */
            $post = call_user_func($class_name . '::init', $object_id);
            /** @var PostMeta $post_meta */
            $post_meta = call_user_func(
                static::get_class_name('post_meta') . '::init',
                $meta_id
            );
            $post_meta->set_meta_value($meta_value);
            static::new([
                'action' => 'update',
                'object_id' => $post->get_id(),
                'object_type' => $post->get_post_type(),
                'object_name' => $post->get_post_title(),
                'sub_action' => 'add',
                'sub_object_id' => $post_meta->get_meta_id(),
                'sub_object_type' => $post_meta->get_meta_type() . '_meta',
                'sub_object_name' => $post_meta->get_meta_key(),
                'success' => 1,
                'detail' => $post_meta->to_json(),
            ]);
        }, 10, 4);
    }

    /**
     * Log when post meta is updated
     */
    public static function when_post_meta_updated(): void
    {
        add_action('update_post_meta', function($meta_id, $object_id, $meta_key, $meta_value) {
            if (in_array($meta_key, static::ignore_post_metas())) {
                return;
            }
            $class_name = static::get_class_name(get_post_type($object_id));
            if ($class_name === '') {
                return;
            }
            /** @var Post $post */
            $post = call_user_func($class_name . '::init', $object_id);
            /** @var PostMeta $post_meta */
            $post_meta = call_user_func(
                static::get_class_name('post_meta') . '::init',
                $meta_id
            );
            $post_meta->set_meta_value($meta_value);
            static::new([
                'action' => 'update',
                'object_id' => $post->get_id(),
                'object_type' => $post->get_post_type(),
                'object_name' => $post->get_post_title(),
                'sub_action' => 'update',
                'sub_object_id' => $post_meta->get_meta_id(),
                'sub_object_type' => $post_meta->get_meta_type() . '_meta',
                'sub_object_name' => $post_meta->get_meta_key(),
                'success' => 1,
                'detail' => $post_meta->to_json(),
            ]);
        }, 10, 4);
    }

    /**
     * Log when post meta is deleted
     */
    public static function when_post_meta_deleted(): void
    {
        add_action('delete_post_meta', function($meta_ids, $object_id, $meta_key, $meta_value) {
            if (in_array($meta_key, static::ignore_post_metas())) {
                return;
            }
            $class_name = static::get_class_name(get_post_type($object_id));
            if ($class_name === '') {
                return;
            }
            /** @var Post $post */
            $post = call_user_func($class_name . '::init', $object_id);
            foreach ($meta_ids as $meta_id) {
                /** @var PostMeta $post_meta */
                $post_meta = call_user_func(
                    static::get_class_name('post_meta') . '::init',
                    $meta_id
                );
                $post_meta->set_meta_value($meta_value);
                static::new([
                    'action' => 'update',
                    'object_id' => $post->get_id(),
                    'object_type' => $post->get_post_type(),
                    'object_name' => $post->get_post_title(),
                    'sub_action' => 'delete',
                    'sub_object_id' => $post_meta->get_meta_id(),
                    'sub_object_type' => $post_meta->get_meta_type() . '_meta',
                    'sub_object_name' => $post_meta->get_meta_key(),
                    'success' => 1,
                    'detail' => $post_meta->to_json(),
                ]);
            }
        }, 10, 4);
    }

    /**
     * Ignore specified post metas from being logged
     *
     * @return array
     */
    public static function ignore_post_metas(): array
    {
        return ['_edit_last', '_edit_lock', '_encloseme'];
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Log when term is created
     */
    public static function when_term_created()
    {
        add_action('create_term', function($term_id, $tt_id, $taxonomy) {
            /** @var Taxonomy $taxonomy */
            $taxonomy = call_user_func(
                static::get_class_name('taxonomy') . '::init',
                $taxonomy
            );
            $class_name = static::get_class_name('taxonomies', $taxonomy->get_name());
            if ($class_name === '') {
                $class_name = static::get_class_name('term');
            }
            /** @var Term $term */
            $term = call_user_func($class_name . '::init', $term_id);
            static::new([
                'action' => 'update',
                'object_type' => 'taxonomy',
                'object_name' => $taxonomy->get_label(),
                'sub_action' => 'create',
                'sub_object_id' => $term->get_term_id(),
                'sub_object_type' => 'term',
                'sub_object_name' => $term->get_name(),
                'success' => 1,
                'detail' => $term->to_json(),
            ]);
        }, 10, 3);
    }

    /**
     * Log when term is edited
     */
    public static function when_term_edited()
    {
        add_action('edited_term', function($term_id, $tt_id, $taxonomy) {
            /** @var Taxonomy $taxonomy */
            $taxonomy = call_user_func(
                static::get_class_name('taxonomy') . '::init',
                $taxonomy
            );
            $class_name = static::get_class_name('taxonomies', $taxonomy->get_name());
            if ($class_name === '') {
                $class_name = static::get_class_name('term');
            }
            /** @var Term $term */
            $term = call_user_func($class_name . '::init', $term_id);
            static::new([
                'action' => 'update',
                'object_type' => 'taxonomy',
                'object_name' => $taxonomy->get_label(),
                'sub_action' => 'edit',
                'sub_object_id' => $term->get_term_id(),
                'sub_object_type' => 'term',
                'sub_object_name' => $term->get_name(),
                'success' => 1,
                'detail' => $term->to_json(),
            ]);
        }, 10, 3);
    }

    /**
     * Log when term is deleted
     */
    public static function when_term_deleted()
    {
        add_action('delete_term', function($term_id, $tt_id, $taxonomy, $deleted_term, $object_ids) {
            /** @var Taxonomy $taxonomy */
            $taxonomy = call_user_func(
                static::get_class_name('taxonomy') . '::init',
                $taxonomy
            );
            $class_name = static::get_class_name('taxonomies', $taxonomy->get_name());
            if ($class_name === '') {
                $class_name = static::get_class_name('term');
            }
            /** @var Term $term */
            $term = call_user_func($class_name . '::init', $term_id);
            static::new([
                'action' => 'update',
                'object_type' => 'taxonomy',
                'object_name' => $taxonomy->get_label(),
                'sub_action' => 'delete',
                'sub_object_id' => $term->get_term_id(),
                'sub_object_type' => 'term',
                'sub_object_name' => $term->get_name(),
                'success' => 1,
                'detail' => $term->to_json(),
            ]);
        }, 10, 5);
    }

    /**
     * Log when term meta is added
     */
    public static function when_term_meta_added(): void
    {
        add_action('added_term_meta', function($meta_id, $object_id, $meta_key, $meta_value) {
            if (in_array($meta_key, static::ignore_term_metas())) {
                return;
            }
            /** @var Term $term */
            $term = call_user_func(
                static::get_class_name('term') . '::init',
                $object_id
            );
            /** @var TermMeta $term_meta */
            $term_meta = call_user_func(
                static::get_class_name('term_meta') . '::init',
                $meta_id
            );
            $term_meta->set_meta_value($meta_value);
            static::new([
                'action' => 'update',
                'object_id' => $term->get_term_id(),
                'object_type' => 'term',
                'object_name' => $term->get_name(),
                'sub_action' => 'add',
                'sub_object_id' => $term_meta->get_meta_id(),
                'sub_object_type' => $term_meta->get_meta_type() . '_meta',
                'sub_object_name' => $term_meta->get_meta_key(),
                'success' => 1,
                'detail' => $term_meta->to_json(),
            ]);
        }, 10, 4);
    }

    /**
     * Log when term meta is updated
     */
    public static function when_term_meta_updated(): void
    {
        add_action('update_term_meta', function($meta_id, $object_id, $meta_key, $meta_value) {
            if (in_array($meta_key, static::ignore_term_metas())) {
                return;
            }
            /** @var Term $term */
            $term = call_user_func(
                static::get_class_name('term') . '::init',
                $object_id
            );
            /** @var TermMeta $term_meta */
            $term_meta = call_user_func(
                static::get_class_name('term_meta') . '::init',
                $meta_id
            );
            $term_meta->set_meta_value($meta_value);
            static::new([
                'action' => 'update',
                'object_id' => $term->get_term_id(),
                'object_type' => 'term',
                'object_name' => $term->get_name(),
                'sub_action' => 'update',
                'sub_object_id' => $term_meta->get_meta_id(),
                'sub_object_type' => $term_meta->get_meta_type() . '_meta',
                'sub_object_name' => $term_meta->get_meta_key(),
                'success' => 1,
                'detail' => $term_meta->to_json(),
            ]);
        }, 10, 4);
    }

    /**
     * Log when term meta is deleted
     */
    public static function when_term_meta_deleted(): void
    {
        add_action('delete_term_meta', function($meta_ids, $object_id, $meta_key, $meta_value) {
            if (in_array($meta_key, static::ignore_term_metas())) {
                return;
            }
            /** @var Term $term */
            $term = call_user_func(
                static::get_class_name('term') . '::init',
                $object_id
            );
            foreach ($meta_ids as $meta_id) {
                /** @var TermMeta $term_meta */
                $term_meta = call_user_func(
                    static::get_class_name('term_meta') . '::init',
                    $meta_id
                );
                $term_meta->set_meta_value($meta_value);
                static::new([
                    'action' => 'update',
                    'object_id' => $term->get_term_id(),
                    'object_type' => 'term',
                    'object_name' => $term->get_name(),
                    'sub_action' => 'delete',
                    'sub_object_id' => $term_meta->get_meta_id(),
                    'sub_object_type' => $term_meta->get_meta_type() . '_meta',
                    'sub_object_name' => $term_meta->get_meta_key(),
                    'success' => 1,
                    'detail' => $term_meta->to_json(),
                ]);
            }
        }, 10, 4);
    }

    /**
     * Ignore specified term metas from being logged
     *
     * @return array
     */
    public static function ignore_term_metas(): array
    {
        return [];
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Log when term relationship added
     */
    public static function when_term_relationship_added(): void
    {
        add_action('added_term_relationship', function($object_id, $tt_id, $taxonomy) {
            $class_name = static::get_class_name(get_post_type($object_id));
            if ($class_name === '') {
                return;
            }
            /** @var Post $post */
            $post = call_user_func($class_name . '::init', $object_id);
            $class_name = static::get_class_name('taxonomies', $taxonomy);
            if ($class_name === '') {
                $class_name = static::get_class_name('term');
            }
            /** @var Term $term */
            $term = call_user_func($class_name . '::init', $tt_id);
            static::new([
                'action' => 'update',
                'object_id' => $post->get_id(),
                'object_type' => $post->get_post_type(),
                'object_name' => $post->get_post_title(),
                'sub_action' => 'add',
                'sub_object_id' => $term->get_term_id(),
                'sub_object_type' => 'term',
                'sub_object_name' => $term->get_name(),
                'success' => 1,
                'detail' => $term->to_json(),
            ]);
        }, 10, 3);
    }

    /**
     * Log when term relationship deleted
     */
    public static function when_term_relationship_deleted(): void
    {
        add_action('deleted_term_relationships', function($object_id, $tt_ids, $taxonomy) {
            $class_name = static::get_class_name(get_post_type($object_id));
            if ($class_name === '') {
                return;
            }
            /** @var Post $post */
            $post = call_user_func($class_name . '::init', $object_id);
            $class_name = static::get_class_name('taxonomies', $taxonomy);
            if ($class_name === '') {
                $class_name = static::get_class_name('term');
            }
            foreach ($tt_ids as $tt_id) {
                /** @var Term $term */
                $term = call_user_func($class_name . '::init', $tt_id);
                static::new([
                    'action' => 'update',
                    'object_id' => $post->get_id(),
                    'object_type' => $post->get_post_type(),
                    'object_name' => $post->get_post_title(),
                    'sub_action' => 'delete',
                    'sub_object_id' => $term->get_term_id(),
                    'sub_object_type' => 'term',
                    'sub_object_name' => $term->get_name(),
                    'success' => 1,
                    'detail' => $term->to_json(),
                ]);
            }
        }, 10, 3);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Log when user is registered
     */
    public static function when_user_registered(): void
    {
        add_action('user_register', function($user_id) {
            /** @var User $user */
            $user = call_user_func(
                static::get_class_name('user') . '::init',
                $user_id
            );
            static::new([
                'action' => 'register',
                'object_id' => $user->get_id(),
                'object_type' => 'user',
                'object_name' => $user->get_best_name(),
                'success' => 1,
                'detail' => $user->to_json(),
            ]);
        }, 10, 1);
    }

    /**
     * Log when user is updated
     */
    public static function when_user_updated(): void
    {
        add_action('profile_update', function($user_id, $old_user_data) {
            /** @var User $user */
            $user = call_user_func(
                static::get_class_name('user') . '::init',
                $user_id
            );
            static::new([
                'action' => 'update',
                'object_id' => $user->get_id(),
                'object_type' => 'user',
                'object_name' => $user->get_best_name(),
                'success' => 1,
                'detail' => $user->to_json(),
            ]);
        }, 10, 2);
    }

    /**
     * Log when user is deleted
     */
    public static function when_user_deleted(): void
    {
        add_action('delete_user', function($user_id, $assign_id, $wp_user) {
            /** @var User $user */
            $user = call_user_func(
                static::get_class_name('user') . '::init',
                $wp_user
            );
            static::new([
                'action' => 'delete',
                'object_id' => $user->get_id(),
                'object_type' => 'user',
                'object_name' => $user->get_best_name(),
                'success' => 1,
                'detail' => $user->to_json(),
            ]);
        }, 10, 3);
    }

    /**
     * Log when user meta is added
     */
    public static function when_user_meta_added(): void
    {
        add_action('added_user_meta', function($meta_id, $object_id, $meta_key, $meta_value) {
            if (in_array($meta_key, static::ignore_user_metas())) {
                return;
            }
            /** @var User $user */
            $user = call_user_func(
                static::get_class_name('user') . '::init',
                $object_id
            );
            /** @var UserMeta $user */
            $user_meta = call_user_func(
                static::get_class_name('user_meta') . '::init',
                $meta_id
            );
            $user_meta->set_meta_value($meta_value);
            static::new([
                'action' => 'update',
                'object_id' => $user->get_id(),
                'object_type' => 'user',
                'object_name' => $user->get_best_name(),
                'sub_action' => 'add',
                'sub_object_id' => $user_meta->get_meta_id(),
                'sub_object_type' => $user_meta->get_meta_type() . '_meta',
                'sub_object_name' => $user_meta->get_meta_key(),
                'success' => 1,
                'detail' => $user_meta->to_json(),
            ]);
        }, 10, 4);
    }

    /**
     * Log when user meta is updated
     */
    public static function when_user_meta_updated(): void
    {
        add_action('update_user_meta', function($meta_id, $object_id, $meta_key, $meta_value) {
            if (in_array($meta_key, static::ignore_user_metas())) {
                return;
            }
            /** @var User $user */
            $user = call_user_func(
                static::get_class_name('user') . '::init',
                $object_id
            );
            /** @var UserMeta $user */
            $user_meta = call_user_func(
                static::get_class_name('user_meta') . '::init',
                $meta_id
            );
            $user_meta->set_meta_value($meta_value);
            static::new([
                'action' => 'update',
                'object_id' => $user->get_id(),
                'object_type' => 'user',
                'object_name' => $user->get_best_name(),
                'sub_action' => 'update',
                'sub_object_id' => $user_meta->get_meta_id(),
                'sub_object_type' => $user_meta->get_meta_type() . '_meta',
                'sub_object_name' => $user_meta->get_meta_key(),
                'success' => 1,
                'detail' => $user_meta->to_json(),
            ]);
        }, 10, 4);
    }

    /**
     * Log when user meta deleted
     */
    public static function when_user_meta_deleted(): void
    {
        add_action('delete_user_meta', function($meta_ids, $object_id, $meta_key, $meta_value) {
            if (in_array($meta_key, static::ignore_user_metas())) {
                return;
            }
            /** @var User $user */
            $user = call_user_func(
                static::get_class_name('user') . '::init',
                $object_id
            );
            foreach ($meta_ids as $meta_id) {
                /** @var UserMeta $user */
                $user_meta = call_user_func(
                    static::get_class_name('user_meta') . '::init',
                    $meta_id
                );
                $user_meta->set_meta_value($meta_value);
                static::new([
                    'action' => 'update',
                    'object_id' => $user->get_id(),
                    'object_type' => 'user',
                    'object_name' => $user->get_best_name(),
                    'sub_action' => 'delete',
                    'sub_object_id' => $user_meta->get_meta_id(),
                    'sub_object_type' => $user_meta->get_meta_type() . '_meta',
                    'sub_object_name' => $user_meta->get_meta_key(),
                    'success' => 1,
                    'detail' => $user_meta->to_json(),
                ]);
            }
        }, 10, 4);
    }

    /**
     * Ignore specified user metas from being logged
     *
     * @return array
     */
    public static function ignore_user_metas(): array
    {
        return ['use_ssl'];
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize log
     *
     * @param int|object $key
     * @return static|null
     */
    public static function init($key): ?Log
    {
        $log = new static();
        if (is_int($key) || ctype_digit($key)) {
            $log->load_from_id($key);
        } elseif (is_object($key)) {
            $log->load_from_object($key);
        }
        if ($log->get_id() === 0) {
            return null;
        }

        return $log;
    }

    /**
     * Get logs
     *
     * @param array $conditions
     * @param string $order_by
     * @return static[]
     */
    public static function get(array $conditions = [], $order_by = 'id DESC'): array
    {
        $db = Database::init();
        $logs = $db->select_where([], [static::TABLE], $conditions, $order_by);
        if (!is_array($logs)) {
            return [];
        }

        return array_map(function(object $log) {
            return static::init($log);
        }, $logs);
    }

    /**
     * New log
     *
     * @param array $params
     * @return static|null
     */
    public static function new(array $params): ?Log
    {
        $log = new static($params);
        if ($log->get_user_id() === 0) {
            $log->set_user_id(get_current_user_id());
        }
        if ($log->get_user_id() !== 0 && $log->get_user_name() === '') {
            /** @var User $user */
            $user = call_user_func(
                static::USER . '::init', $log->get_user_id()
            );
            $log->set_user_name($user->get_best_name());
        }
        if ($log->create() === true) {
            return $log;
        }

        return null;
    }

    /************************************************************************************/
    // Protected load methods

    /**
     * Load instance from ID
     *
     * @param int $id
     */
    protected function load_from_id(int $id): void
    {
        if (!$object = $this->db->select_where_id(static::TABLE, $id)) {
            return;
        }
        $this->load_from_object($object);
    }

    /**
     * Load instance from object
     *
     * @param object $object
     */
    protected function load_from_object(object $object): void
    {
        $this->id = (int) $object->id;
        $this->user_id = (int) $object->user_id;
        $this->user_name = $object->user_name;
        $this->action = $object->action;
        $this->object_id = (int) $object->object_id;
        $this->object_type = $object->object_type;
        $this->object_name = $object->object_name;
        $this->sub_action = $object->sub_action;
        $this->sub_object_id = (int) $object->sub_object_id;
        $this->sub_object_type = $object->sub_object_type;
        $this->sub_object_name = $object->sub_object_name;
        $this->success = (int) $object->success;
        $this->message = $object->message;
        $this->detail = $object->detail;
        $this->date = $object->date;
    }

    /**
     * Reload instance from database
     */
    protected function reload(): void
    {
        if (!$this->id) {
            return;
        }
        $this->load_from_id($this->id);
    }

    /************************************************************************************/
    // Action methods

    /**
     * Save log
     *
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->id) {
            return $this->create();
        }

        return $this->update();
    }

    /**
     * Create new log
     *
     * @return bool
     */
    public function create(): bool
    {
        $id = $this->db->insert_into(
            static::TABLE, $this->to_array()
        );
        if ($id === 0) {
            return false;
        }
        $this->id = $id;
        $this->reload();

        return true;
    }

    /**
     * Update log
     *
     * @return bool
     */
    public function update(): bool
    {
        return $this->db->update_where_id(
            static::TABLE, $this->to_array(), $this->id
        );
    }

    /**
     * Delete log
     *
     * @return bool
     */
    public function delete(): bool
    {
        return $this->db->delete_where_id(
            $this->id, static::TABLE
        );
    }

    /************************************************************************************/
    // Cast methods

    /**
     * Cast instance to array
     *
     * @return array
     */
    public function to_array(): array
    {
        $data = [];
        if ($this->id !== 0) {
            $data['id'] = $this->id;
        }
        if ($this->user_id !== 0) {
            $data['user_id'] = $this->user_id;
        }
        if ($this->user_name !== '') {
            $data['user_name'] = $this->user_name;
        }
        if ($this->action !== '') {
            $data['action'] = $this->action;
        }
        if ($this->object_id !== 0) {
            $data['object_id'] = $this->object_id;
        }
        if ($this->object_type !== '') {
            $data['object_type'] = $this->object_type;
        }
        if ($this->object_name !== '') {
            $data['object_name'] = $this->object_name;
        }
        if ($this->sub_action !== '') {
            $data['sub_action'] = $this->sub_action;
        }
        if ($this->sub_object_id !== 0) {
            $data['sub_object_id'] = $this->sub_object_id;
        }
        if ($this->sub_object_type !== '') {
            $data['sub_object_type'] = $this->sub_object_type;
        }
        if ($this->sub_object_name !== '') {
            $data['sub_object_name'] = $this->sub_object_name;
        }
        if ($this->success !== 0) {
            $data['success'] = $this->success;
        }
        if ($this->message !== '') {
            $data['message'] = $this->message;
        }
        if ($this->detail !== null) {
            $data['detail'] = $this->detail;
        }
        if ($this->date !== '') {
            $data['date'] = $this->date;
        }

        return $data;
    }

    /**
     * Cast instance to JSON
     *
     * @return string
     */
    public function to_json(): string
    {
        return json_encode($this->to_array());
    }
    /**
     * Cast instance to object
     *
     * @return object
     */
    public function to_object(): object
    {
        return (object) $this->to_array();
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get (or set) database
     *
     * @param Database|null $db
     * @return Database
     */
    protected function db(Database $db = null): Database
    {
        if ($db !== null) {
            $this->db = $db;
        }

        return $this->db;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get user
     *
     * @return User|null
     */
    public function user(): ?User
    {
        if ($this->user_obj) {
            return $this->user_obj;
        }
        if (!$this->user_id) {
            return null;
        }

        return $this->user_obj = call_user_func(
            static::USER . '::init', $this->user_id
        );
    }

    /**
     * Get date
     *
     * @return DateTime
     */
    public function date(): DateTime
    {
        if ($this->date_obj) {
            return $this->date_obj;
        }
        $timezone = get_option('timezone_string');

        return $this->date_obj = call_user_func(
            static::DATE_TIME . '::init', $this->date, $timezone
        );
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get ID
     *
     * @return int
     */
    public function get_id(): int
    {
        return $this->id;
    }

    /**
     * Set ID
     *
     * @param int $id
     */
    public function set_id(int $id): void
    {
        $this->id = $id;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get user ID
     *
     * @return int
     */
    public function get_user_id(): int
    {
        return $this->user_id;
    }

    /**
     * Set user ID
     *
     * @param int $user_id
     */
    public function set_user_id(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get user name
     *
     * @return string
     */
    public function get_user_name(): string
    {
        return $this->user_name;
    }

    /**
     * Set user name
     *
     * @param string $user_name
     */
    public function set_user_name(string $user_name): void
    {
        $this->user_name = $user_name;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get action
     *
     * @return string
     */
    public function get_action(): string
    {
        return $this->action;
    }

    /**
     * Set action
     *
     * @param string $action
     */
    public function set_action(string $action): void
    {
        $this->action = $action;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get object type
     *
     * @return string
     */
    public function get_object_type(): string
    {
        return $this->object_type;
    }

    /**
     * Set object type
     *
     * @param string $object_type
     */
    public function set_object_type(string $object_type): void
    {
        $this->object_type = $object_type;
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

    /*----------------------------------------------------------------------------------*/

    /**
     * Get object name
     *
     * @return string
     */
    public function get_object_name(): string
    {
        return $this->object_name;
    }

    /**
     * Set object name
     *
     * @param string $object_name
     */
    public function set_object_name(string $object_name): void
    {
        $this->object_name = $object_name;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get sub action
     *
     * @return string
     */
    public function get_sub_action(): string
    {
        return $this->sub_action;
    }

    /**
     * Set sub action
     *
     * @param string $sub_action
     */
    public function set_sub_action(string $sub_action): void
    {
        $this->sub_action = $sub_action;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get sub object type
     *
     * @return string
     */
    public function get_sub_object_type(): string
    {
        return $this->sub_object_type;
    }

    /**
     * Set sub object type
     *
     * @param string $sub_object_type
     */
    public function set_sub_object_type(string $sub_object_type): void
    {
        $this->sub_object_type = $sub_object_type;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get sub object ID
     *
     * @return int
     */
    public function get_sub_object_id(): int
    {
        return $this->sub_object_id;
    }

    /**
     * Set sub object ID
     *
     * @param int $sub_object_id
     */
    public function set_sub_object_id(int $sub_object_id): void
    {
        $this->sub_object_id = $sub_object_id;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get sub object name
     *
     * @return string
     */
    public function get_sub_object_name(): string
    {
        return $this->sub_object_name;
    }

    /**
     * Set sub object name
     *
     * @param string $sub_object_name
     */
    public function set_sub_object_name(string $sub_object_name): void
    {
        $this->sub_object_name = $sub_object_name;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get success
     *
     * @return string
     */
    public function get_success(): string
    {
        return $this->success;
    }

    /**
     * Set success
     *
     * @param string $success
     */
    public function set_success(string $success): void
    {
        $this->success = $success;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get message
     *
     * @return string
     */
    public function get_message(): string
    {
        return $this->message;
    }

    /**
     * Set message
     *
     * @param string $message
     */
    public function set_message(string $message): void
    {
        $this->message = $message;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get detail
     *
     * @return mixed
     */
    public function get_detail()
    {
        return $this->detail;
    }

    /**
     * Set detail
     *
     * @param mixed $detail
     */
    public function set_detail($detail): void
    {
        $this->detail = $detail;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get date
     *
     * @return string
     */
    public function get_date(): string
    {
        return $this->date;
    }

    /**
     * Set date
     *
     * @param string $date
     */
    public function set_date(string $date): void
    {
        $this->date = $date;
    }
}