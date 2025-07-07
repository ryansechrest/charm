<?php

namespace Charm\Structures\Core;

use Charm\Support\Filter;
use Charm\Support\Result;
use WP_Post_Type;

/**
 * Represents a core post type in WordPress.
 *
 * [ ALERT ] Must register a `PostType` within the WordPress `init` hook.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class PostType
{
    /**
     * Post type key. Must not exceed 20 characters and may only contain
     * lowercase alphanumeric characters, dashes, and underscores.
     *
     * @var ?string post
     */
    protected ?string $key = null;

    /**
     * The name of the post type shown in the menu. Usually plural.
     *
     * Default is the value of `$labels['name']`.
     *
     * @var ?string
     */
    protected ?string $label = null;

    /**
     * An array of labels for this post type. If not set, post labels are
     * inherited for non-hierarchical types and page labels for hierarchical
     * ones. See `get_post_type_labels()` for a full list of supported labels.
     *
     * @var ?array
     */
    protected ?array $labels = null;

    /**
     * A short descriptive summary of what the post type is.
     *
     * @var ?string
     */
    protected ?string $description = null;

    /**
     * Whether a post type is intended for use publicly either via the admin
     * interface or by front-end users. While the default settings of
     * `$exclude_from_search`, `$publicly_queryable`, `$show_ui`, and
     * `$show_in_nav_menus` are inherited from `$public`, each does not rely on
     * this relationship and controls a very specific intention.
     *
     * Default is `false`.
     *
     * @var ?bool
     */
    protected ?bool $public = null;

    /**
     * Whether the post type is hierarchical (e.g. `page`).
     *
     * Default is `false`.
     *
     * @var ?bool
     */
    protected ?bool $hierarchical = null;

    /**
     * Whether to exclude posts with this post type from front end search
     * results.
     *
     * Default is the opposite value of `$public`.
     *
     * @var ?bool
     */
    protected ?bool $excludeFromSearch = null;

    /**
     * Whether queries can be performed on the front end for the post type as
     * part of `parse_request()`. Endpoints would include:
     *   - ?post_type={post_type_key}
     *   - ?{post_type_key}={single_post_slug}
     *   - ?{post_type_query_var}={single_post_slug}
     *
     * If not set, the default is inherited from `$public`.
     *
     * @var ?bool
     */
    protected ?bool $publiclyQueryable = null;

    /**
     * Whether to generate and allow a UI for managing this post type in the
     * admin.
     *
     * Default is the value of `$public`.
     *
     * @var ?bool
     */
    protected ?bool $showUi = null;

    /**
     * Where to show the post type in the admin menu. To work, `$show_ui` must
     * be `true`. If `true`, the post type is shown in its own top-level menu.
     * If `false`, no menu is shown. If a string of an existing top level menu
     * (`tools.php`, `edit.php?post_type=page`, etc.), the post type will be\
     * placed as a submenu of that.
     *
     * Default is the value of `$show_ui`.
     *
     * @var bool|string|null
     */
    protected bool|string|null $showInMenu = null;

    /**
     * Makes this post type available for selection in navigation menus.
     *
     * Default is the value of `$public`.
     *
     * @var ?bool
     */
    protected ?bool $showInNavMenus = null;

    /**
     * Makes this post type available via the admin bar.
     *
     * Default is the value of `$show_in_menu`.
     *
     * @var ?bool
     */
    protected ?bool $showInAdminBar = null;

    /**
     * Whether to include the post type in the REST API. Set this to `true` for
     * the post type to be available in the block editor.
     *
     * @var ?bool
     */
    protected ?bool $showInRest = null;

    /**
     * To change the base URL of the REST API route.
     *
     * Default is the `$post_type`.
     *
     * @var ?string
     */
    protected ?string $restBase = null;

    /**
     * To change the namespace URL of the REST API route.
     *
     * Default is `wp/v2`.
     *
     * @var ?string
     */
    protected ?string $restNamespace = null;

    /**
     * REST API controller class name.
     *
     * Default is the `WP_REST_Posts_Controller`.
     *
     * @var ?string
     */
    protected ?string $restControllerClass = null;

    /**
     * REST API controller class name to autosaves.
     *
     * Default is the `WP_REST_Autosaves_Controller`.
     *
     * @var bool|string|null
     */
    protected bool|string|null $autosaveRestControllerClass = null;

    /**
     * REST API controller class name for revisions.
     *
     * Default is `WP_REST_Revisions_Controller`.
     *
     * @var bool|string|null
     */
    protected bool|string|null $revisionsRestControllerClass = null;

    /**
     * A flag to direct the REST API controllers for autosave / revisions should
     * be registered before/after the post type controller.
     *
     * @var ?bool
     */
    protected ?bool $lateRouteRegistration = null;

    /**
     * The position in the menu order the post type should appear. To work,
     * `$show_in_menu must` be true.
     *
     * Default is `null` (at the bottom).
     *
     * @var ?int
     */
    protected ?int $menuPosition = null;

    /**
     * The URL to the icon to be used for this menu. Pass a base64-encoded SVG
     * using a data URI, which will be colored to match the color scheme — this
     * should begin with `data:image/svg+xml;base64`. Pass the name of a
     * Dashicons helper class to use a font icon, e.g. `dashicons-chart-pie`.
     * Pass `none` to leave `div.wp-menu-image` empty so an icon can be added
     * via CSS.
     *
     * Defaults to use the post icon.
     *
     * @var ?string
     */
    protected ?string $menuIcon = null;

    /**
     * The string to use to build the read, edit, and delete capabilities.
     * May be passed as an array to allow for alternative plurals when using
     * this argument as a base to construct the capabilities, e.g.
     * `['story', 'stories']`.
     *
     * Default is `post`.
     *
     * @var array|string|null
     */
    protected array|string|null $capabilityType = null;

    /**
     * Array of capabilities for this post type. `$capability_type` is used as
     * a base to construct capabilities by default.
     * See `get_post_type_capabilities()`.
     *
     * @var ?array
     */
    protected ?array $capabilities = null;

    /**
     * Whether to use the internal default meta capability handling.
     *
     * Default is `false`.
     *
     * @var ?bool
     */
    protected ?bool $mapMetaCap = null;

    /**
     * Core feature(s) the post type supports. Serves as an alias for calling
     * `add_post_type_support()` directly.
     *
     * Core features include 'title', 'editor', 'comments', 'revisions',
     * 'trackbacks', 'author', 'excerpt', 'page-attributes', 'thumbnail',
     * 'custom-fields', and 'post-formats'.
     *
     * Additionally, the 'revisions' feature dictates whether the post type will
     * store revisions, the 'autosave' feature dictates whether the post type
     * will be autosaved, and the 'comments' feature dictates whether the
     * comment count will show on the edit screen.
     *
     * For backward compatibility reasons, adding 'editor' support implies
     * 'autosave' support too.
     *
     * A feature can also be specified as an array of arguments to provide
     * additional information about supporting that feature.
     *
     * Example: `['my_feature', ['field' => 'value']]`.
     *
     * If `false`, no features will be added.
     *
     * Default is an array containing 'title' and 'editor'.
     *
     * @var array|false|null
     */
    protected array|false|null $supports = null;

    /**
     * Provide a callback function that sets up the meta boxes for the edit
     * form. Do `remove_meta_box()` and `add_meta_box()` calls in the callback.
     *
     * Default is `null`.
     *
     * @var ?string
     */
    protected ?string $registerMetaBoxCb = null;

    /**
     * An array of taxonomy identifiers that will be registered for the post
     * type. Taxonomies can be registered later with `register_taxonomy()` or
     * `register_taxonomy_for_object_type()`.
     *
     * @var ?array
     */
    protected ?array $taxonomies = null;

    /**
     * Whether there should be post type archives, or if a string, the archive
     * slug to use. Will generate the proper rewrite rules if `$rewrite` is
     * enabled.
     *
     * Default is `false`.
     *
     * @var bool|string|null
     */
    protected bool|string|null $hasArchive = null;

    /**
     * Triggers the handling of rewrites for this post type. To prevent rewrite,
     * set to `false`.
     *
     * Defaults to `true`, using `$post_type` as the slug.
     *
     * To specify rewrite rules, an array can be passed with any of these keys:
     *
     *   - 'slug'
     *      Customize the permastruct slug.
     *      Defaults to `$post_type` key.
     *
     *   - `with_front`
     *      Customize the permastruct slug.
     *      Defaults to `$post_type` key.
     *
     *   - `feeds`
     *      Whether the feed permastruct should be built for this post type.
     *      Default is value of `$has_archive`.
     *
     *   - `pages`
     *      Whether the permastruct should provide for pagination.
     *      Defaults to `true`.
     *
     *   - `ep_mask`
     *      Endpoint mask to assign. If not specified and `permalink_epmask` is
     *      set, inherits from `$permalink_epmask`. If not specified and
     *      `permalink_epmask` is not set, defaults to `EP_PERMALINK`.
     *
     * @var array|bool|null
     */
    protected array|bool|null $rewrite = null;

    /**
     * Sets the query_var key for this post type.
     *
     * Defaults to `$post_type key`.
     *
     * If `false`, a post type cannot be loaded at `?{query_var}={post_slug}`.
     *
     * If specified as a string, the query `?{query_var_string}={post_slug}`
     * will be valid.
     *
     * @var bool|string|null
     */
    protected bool|string|null $queryVar = null;

    /**
     * Whether to allow this post type to be exported.
     *
     * Default is `true`.
     *
     * @var ?bool
     */
    protected ?bool $canExport = null;

    /**
     * Whether to delete posts of this type when deleting a user.
     *
     * If `true`, posts of this type belonging to the user will be moved to
     * Trash when the user is deleted.
     *
     * If `false`, posts of this type belonging to the user will *not* be
     * trashed or deleted.
     *
     * If not set (the default), posts are trashed if post type supports the
     * 'author' feature. Otherwise, posts are not trashed or deleted.
     *
     * Default is `null`.
     *
     * @var ?bool
     */
    protected ?bool $deleteWithUser = null;

    /**
     * Array of blocks to use as the default initial state for an editor
     * session. Each item should be an array containing the block name and
     * optional attributes.
     *
     * @var ?array
     */
    protected ?array $template = null;

    /**
     * Whether the block template should be locked if $template is set.
     *
     * If set to `all`, the user is unable to insert new blocks, move existing
     * blocks, and delete blocks.
     *
     * If set to `insert`, the user is able to move existing blocks but is
     * unable to insert new blocks and delete blocks.
     *
     * Default is `false`.
     *
     * @var false|string|null
     */
    protected false|string|null $templateLock = null;

    // -------------------------------------------------------------------------

    /**
     * Whether the post type is registered.
     *
     * @var bool
     */
    protected bool $registered = false;

    // -------------------------------------------------------------------------

    /**
     * `WP_Post_Type` instance.
     *
     * @var ?WP_Post_Type
     */
    protected ?WP_Post_Type $wpPostType = null;

    // *************************************************************************

    /**
     * PostType constructor.
     *
     * @param string $key
     * @param array $data
     */
    public function __construct(string $key, array $data = [])
    {
        $this->key = $key;
        $this->load($data);
    }

    /**
     * Load the instance with data.
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['label'])) {
            $this->label = $data['label'];
        }

        if (isset($data['labels'])) {
            $this->labels = $data['labels'];
        }

        if (isset($data['description'])) {
            $this->description = $data['description'];
        }

        if (isset($data['public'])) {
            $this->public = (bool) $data['public'];
        }

        if (isset($data['hierarchical'])) {
            $this->hierarchical = (bool) $data['hierarchical'];
        }

        if (isset($data['excludeFromSearch'])) {
            $this->excludeFromSearch = (bool) $data['excludeFromSearch'];
        }

        if (isset($data['publiclyQueryable'])) {
            $this->publiclyQueryable = (bool) $data['publiclyQueryable'];
        }

        if (isset($data['showUi'])) {
            $this->showUi = (bool) $data['showUi'];
        }

        if (isset($data['showInMenu'])) {
            $this->showInMenu = (bool) $data['showInMenu'];
        }

        if (isset($data['showInNavMenus'])) {
            $this->showInNavMenus = (bool) $data['showInNavMenus'];
        }

        if (isset($data['showInAdminBar'])) {
            $this->showInAdminBar = (bool) $data['showInAdminBar'];
        }

        if (isset($data['showInRest'])) {
            $this->showInRest = (bool) $data['showInRest'];
        }

        if (isset($data['restBase'])) {
            $this->restBase = $data['restBase'];
        }

        if (isset($data['restNamespace'])) {
            $this->restNamespace = $data['restNamespace'];
        }

        if (isset($data['restControllerClass'])) {
            $this->restControllerClass = $data['restControllerClass'];
        }

        if (isset($data['autosaveRestControllerClass'])) {
            $this->autosaveRestControllerClass = $data['autosaveRestControllerClass'];
        }

        if (isset($data['revisionsRestControllerClass'])) {
            $this->revisionsRestControllerClass = $data['revisionsRestControllerClass'];
        }

        if (isset($data['lateRouteRegistration'])) {
            $this->lateRouteRegistration = (bool) $data['lateRouteRegistration'];
        }

        if (isset($data['menuPosition'])) {
            $this->menuPosition = (int) $data['menuPosition'];
        }

        if (isset($data['menuIcon'])) {
            $this->menuIcon = $data['menuIcon'];
        }

        if (isset($data['capabilityType'])) {
            $this->capabilityType = $data['capabilityType'];
        }

        if (isset($data['capabilities'])) {
            $this->capabilities = $data['capabilities'];
        }

        if (isset($data['mapMetaCap'])) {
            $this->mapMetaCap = (bool) $data['mapMetaCap'];
        }

        if (isset($data['supports'])) {
            $this->supports = $data['supports'];
        }

        if (isset($data['registerMetaBoxCb'])) {
            $this->registerMetaBoxCb = $data['registerMetaBoxCb'];
        }

        if (isset($data['taxonomies'])) {
            $this->taxonomies = $data['taxonomies'];
        }

        if (isset($data['hasArchive'])) {
            $this->hasArchive = $data['hasArchive'];
        }

        if (isset($data['rewrite'])) {
            $this->rewrite = $data['rewrite'];
        }

        if (isset($data['queryVar'])) {
            $this->queryVar = $data['queryVar'];
        }

        if (isset($data['canExport'])) {
            $this->canExport = (bool) $data['canExport'];
        }

        if (isset($data['deleteWithUser'])) {
            $this->deleteWithUser = (bool) $data['deleteWithUser'];
        }

        if (isset($data['template'])) {
            $this->template = $data['template'];
        }

        if (isset($data['templateLock'])) {
            $this->templateLock = $data['templateLock'];
        }

        if (isset($data['registered'])) {
            $this->registered = (bool) $data['registered'];
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Access the `WP_Post_type` instance.
     *
     * @return ?WP_Post_Type
     */
    public function wpPostType(): ?WP_Post_Type
    {
        return $this->wpPostType;
    }

    // *************************************************************************

    /**
     * Initialize the post type from a key.
     *
     * @param string $key
     * @return ?static
     */
    public static function fromKey(string $key): ?static
    {
        $postType = new static($key);
        $postType->loadFromKey($key);

        return $postType->registered ? $postType : null;
    }

    /**
     * Initialize the post type from a `WP_Post_Type` instance.
     *
     * @param WP_Post_Type $wpPostType
     * @return static
     */
    public static function fromWpPostType(WP_Post_Type $wpPostType): static
    {
        $postType = new static($wpPostType->name);
        $postType->loadFromWpPostType($wpPostType);

        return $postType;
    }

    // -------------------------------------------------------------------------

    /**
     * Register a new post type in WordPress.
     *
     * @param string $key
     * @param array $args
     * @return Result
     * @see register_post_type()
     */
    public static function registerPostType(
        string $key, array $args = []
    ): Result
    {
        $methodArgs = array_merge(['postType' => $key], $args);

        // `object` -> `WP_Post_Type` -> Success: Post type registered
        // `object` -> `WP_Error`     -> Fail: Post type not registered
        $result = register_post_type(
            post_type: $key,
            args: $args
        );

        if (is_wp_error($result)) {
            return Result::error(
                'post_type_register_failed',
                'Post type could not be registered. `register_post_type()` returned a `WP_Error` object.'
            )->setFunctionReturn($result)->setFunctionArgs($methodArgs);
        }

        if (!$result instanceof WP_Post_Type) {
            return Result::error(
                'post_type_register_failed',
                'Post type could not be registered. Expected `register_post_type()` to return a `WP_Post_Type` instance, but received an unexpected result.'
            )->setFunctionReturn($result)->setFunctionArgs($methodArgs);
        }

        return Result::success(
            'post_type_register_success',
            'Post type successfully registered.'
        )->setFunctionReturn($result)->setFunctionArgs($args);
    }

    // *************************************************************************

    /**
     * Register the post type.
     *
     * @return Result
     */
    public function register(): Result
    {
        $result = static::registerPostType(
            $this->key, $this->toWpPostTypeArray()
        );

        if ($result->hasFailed()) {
            return $result;
        }

        $this->registered = true;

        return $result;
    }

    // *************************************************************************

    /**
     * Get the post type key.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key ?? '';
    }

    // -------------------------------------------------------------------------

    /**
     * Get the name of the post type shown in the menu.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label ?? '';
    }

    /**
     * Set the name of the post type shown in the menu.
     *
     * @param string $label
     * @return static
     */
    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the labels for this post type.
     *
     * @return array
     */
    public function getLabels(): array
    {
        return $this->labels ?? [];
    }

    /**
     * Set the labels for this post type.
     *
     * @param array $labels
     * @return static
     */
    public function setLabels(array $labels): static
    {
        $this->labels = $labels;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get a short descriptive summary of what the post type is.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    /**
     * Set a short descriptive summary of what the post type is.
     *
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the post type is intended for use publicly.
     *
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->public ?? false;
    }

    /**
     * Set whether the post type is intended for use publicly.
     *
     * @param bool $public
     * @return $this
     */
    public function setPublic(bool $public): static
    {
        $this->public = $public;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the post type is hierarchical like `page`.
     *
     * @return bool
     */
    public function isHierarchical(): bool
    {
        return $this->hierarchical ?? false;
    }

    /**
     * Set whether the post type is hierarchical like `page`.
     *
     * @param bool $hierarchical
     * @return $this
     */
    public function setHierarchical(bool $hierarchical): static
    {
        $this->hierarchical = $hierarchical;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether to exclude posts within post type from front end search.
     *
     * @return bool
     */
    public function isExcludeFromSearch(): bool
    {
        return $this->excludeFromSearch ?? true;
    }

    /**
     * Set whether to exclude posts within post type from front end search.
     *
     * @param bool $excludeFromSearch
     * @return $this
     */
    public function setExcludeFromSearch(bool $excludeFromSearch): static
    {
        $this->excludeFromSearch = $excludeFromSearch;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether queries can be performed on the front end for the post type
     * as part of `parse_request()`.
     *
     * @return bool
     */
    public function isPubliclyQueryable(): bool
    {
        return $this->publiclyQueryable ?? false;
    }

    /**
     * Set whether queries can be performed on the front end for the post type
     * as part of `parse_request()`.
     *
     * @param bool $publiclyQueryable
     * @return $this
     */
    public function setPubliclyQueryable(bool $publiclyQueryable): static
    {
        $this->publiclyQueryable = $publiclyQueryable;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether to generate and allow a UI for managing this post type in
     * the admin.
     *
     * @return bool
     */
    public function isShowUi(): bool
    {
        return $this->showUi ?? false;
    }

    /**
     * Set whether to generate and allow a UI for managing this post type in
     * the admin.
     *
     * @param bool $showUi
     * @return $this
     */
    public function setShowUi(bool $showUi): static
    {
        $this->showUi = $showUi;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether to show the post type in the admin menu.
     *
     * @return bool
     */
    public function isShowInMenu(): bool
    {
        return $this->showInMenu ?? false;
    }

    /**
     * Set whether to show the post type in the admin menu.
     *
     * @param bool $showInMenu
     * @return $this
     */
    public function setShowInMenu(bool $showInMenu): static
    {
        $this->showInMenu = $showInMenu;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether posts from this post type are available for selection in
     * navigation menus.
     *
     * @return bool
     */
    public function isShowInNavMenus(): bool
    {
        return $this->showInNavMenus ?? false;
    }

    /**
     * Set whether posts from this post type are available for selection in
     * navigation menus.
     *
     * @param bool $showInNavMenus
     * @return $this
     */
    public function setShowInNavMenus(bool $showInNavMenus): static
    {
        $this->showInNavMenus = $showInNavMenus;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether this post type is available via the admin bar.
     *
     * @return bool
     */
    public function isShowInAdminBar(): bool
    {
        return $this->showInAdminBar ?? false;
    }

    /**
     * Set whether this post type is available via the admin bar.
     *
     * @param bool $showInAdminBar
     * @return $this
     */
    public function setShowInAdminBar(bool $showInAdminBar): static
    {
        $this->showInAdminBar = $showInAdminBar;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the post type is included in the REST API.
     *
     * @return bool
     */
    public function isShowInRest(): bool
    {
        return $this->showInRest ?? false;
    }

    /**
     * Set whether the post type is included in the REST API.
     *
     * @param bool $showInRest
     * @return $this
     */
    public function setShowInRest(bool $showInRest): static
    {
        $this->showInRest = $showInRest;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the base URL of the REST API route.
     *
     * @return string
     */
    public function getRestBase(): string
    {
        return $this->restBase ?? '';
    }

    /**
     * Set the base URL of the REST API route.
     *
     * @param string $restBase
     * @return $this
     */
    public function setRestBase(string $restBase): static
    {
        $this->restBase = $restBase;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the namespace URL of the REST API route.
     *
     * @return string
     */
    public function getRestNamespace(): string
    {
        return $this->restNamespace ?? '';
    }

    /**
     * Set the namespace URL of the REST API route.
     *
     * @param string $restNamespace
     * @return $this
     */
    public function setRestNamespace(string $restNamespace): static
    {
        $this->restNamespace = $restNamespace;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the REST API controller class name.
     *
     * @return string
     */
    public function getRestControllerClass(): string
    {
        return $this->restControllerClass ?? '';
    }

    /**
     * Set the REST API controller class name.
     *
     * @param string $restControllerClass
     * @return $this
     */
    public function setRestControllerClass(string $restControllerClass): static
    {
        $this->restControllerClass = $restControllerClass;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the autosave REST API controller class name.
     *
     * @return string
     */
    public function getAutosaveRestControllerClass(): string
    {
        return $this->autosaveRestControllerClass ?? '';
    }

    /**
     * Set the autosave REST API controller class name.
     *
     * @param string $autosaveRestControllerClass
     * @return $this
     */
    public function setAutosaveRestControllerClass(string $autosaveRestControllerClass): static
    {
        $this->autosaveRestControllerClass = $autosaveRestControllerClass;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the revisions REST API controller class name.
     *
     * @return string
     */
    public function getRevisionsRestControllerClass(): string
    {
        return $this->revisionsRestControllerClass ?? '';
    }

    /**
     * Set the revisions REST API controller class name.
     *
     * @param string $revisionsRestControllerClass
     * @return $this
     */
    public function setRevisionsRestControllerClass(string $revisionsRestControllerClass): static
    {
        $this->revisionsRestControllerClass = $revisionsRestControllerClass;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether autosave/revisions are registered before/after the post
     * type controller.
     *
     * @return bool
     */
    public function isLateRouteRegistration(): bool
    {
        return $this->lateRouteRegistration ?? false;
    }

    /**
     * Set whether autosave/revisions are registered before/after the post
     * type controller.
     *
     * @param bool $lateRouteRegistration
     * @return $this
     */
    public function setLateRouteRegistration(bool $lateRouteRegistration): static
    {
        $this->lateRouteRegistration = $lateRouteRegistration;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the position in the menu order the post type appears.
     *
     * @return int
     */
    public function getMenuPosition(): int
    {
        return $this->menuPosition ?? 0;
    }

    /**
     * Set the position in the menu order the post type appears.
     *
     * @param int $menuPosition
     * @return $this
     */
    public function setMenuPosition(int $menuPosition): static
    {
        $this->menuPosition = $menuPosition;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the URL or reference to the icon to be used for this menu.
     *
     * @return string
     */
    public function getMenuIcon(): string
    {
        return $this->menuIcon ?? '';
    }

    /**
     * Set the URL or reference to the icon to be used for this menu.
     *
     * @param string $menuIcon
     * @return $this
     */
    public function setMenuIcon(string $menuIcon): static
    {
        $this->menuIcon = $menuIcon;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the string to use to build the read, edit, and delete capabilities.
     *
     * @return string
     */
    public function getCapabilityType(): string
    {
        return $this->capabilityType ?? '';
    }

    /**
     * Set the string to use to build the read, edit, and delete capabilities.
     *
     * @param string $capabilityType
     * @return $this
     */
    public function setCapabilityType(string $capabilityType): static
    {
        $this->capabilityType = $capabilityType;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the array of capabilities for this post type.
     *
     * @return array
     */
    public function getCapabilities(): array
    {
        return $this->capabilities ?? [];
    }

    /**
     * Set the array of capabilities for this post type.
     *
     * @param array $capabilities
     * @return $this
     */
    public function setCapabilities(array $capabilities): static
    {
        $this->capabilities = $capabilities;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get whether to use the internal default meta capability handling.
     *
     * @return bool
     */
    public function getMapMetaCap(): bool
    {
        return $this->mapMetaCap ?? true;
    }

    /**
     * Set whether to use the internal default meta capability handling.
     *
     * @param bool $mapMetaCap
     * @return $this
     */
    public function setMapMetaCap(bool $mapMetaCap): static
    {
        $this->mapMetaCap = $mapMetaCap;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the core feature(s) the post type supports.
     *
     * @return array
     */
    public function getSupports(): array
    {
        return $this->supports ?? [];
    }

    /**
     * Set the core feature(s) the post type supports.
     *
     * @param array $supports
     * @return $this
     */
    public function setSupports(array $supports): static
    {
        $this->supports = $supports;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the callback function that sets up the meta boxes for the edit form.
     *
     * @return string
     */
    public function getRegisterMetaBoxCb(): string
    {
        return $this->registerMetaBoxCb ?? '';
    }

    /**
     * Set the callback function that sets up the meta boxes for the edit form.
     *
     * @param string $registerMetaBoxCb
     * @return $this
     */
    public function setRegisterMetaBoxCb(string $registerMetaBoxCb): static
    {
        $this->registerMetaBoxCb = $registerMetaBoxCb;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get an array of taxonomy identifiers that are registered for the
     * post type.
     *
     * @return array
     */
    public function getTaxonomies(): array
    {
        return $this->taxonomies ?? [];
    }

    /**
     * Set an array of taxonomy identifiers that are registered for the
     * post type.
     *
     * @param array $taxonomies
     * @return $this
     */
    public function setTaxonomies(array $taxonomies): static
    {
        $this->taxonomies = $taxonomies;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get whether there should be post type archives, or if a string, the
     * archive slug being used.
     *
     * @return bool|string
     */
    public function getHasArchive(): bool|string
    {
        return $this->hasArchive ?? false;
    }

    /**
     * Set whether there should be post type archives, or if a string, the
     * archive slug being used.
     *
     * @param string $hasArchive
     * @return $this
     */
    public function setHasArchive(string $hasArchive): static
    {
        $this->hasArchive = $hasArchive;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get rewrite options for this post type.
     *
     * @return array
     */
    public function getRewrite(): array
    {
        return $this->rewrite ?? [];
    }

    /**
     * Set rewrite options for this post type.
     *
     * @param array $rewrite
     * @return $this
     */
    public function setRewrite(array $rewrite): static
    {
        $this->rewrite = $rewrite;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the query_var key for this post type.
     *
     * @return string
     */
    public function getQueryVar(): string
    {
        return $this->queryVar ?? '';
    }

    /**
     * Set the query_var key for this post type.
     *
     * @param string $queryVar
     * @return $this
     */
    public function setQueryVar(string $queryVar): static
    {
        $this->queryVar = $queryVar;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether to allow this post type to be exported.
     *
     * @return bool
     */
    public function isCanExport(): bool
    {
        return $this->canExport ?? true;
    }

    /**
     * Set whether to allow this post type to be exported.
     *
     * @param bool $canExport
     * @return $this
     */
    public function setCanExport(bool $canExport): static
    {
        $this->canExport = $canExport;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether to delete posts of this type when deleting a user.
     *
     * @return bool
     */
    public function isDeleteWithUser(): bool
    {
        return $this->deleteWithUser ?? false;
    }

    /**
     * Set whether to delete posts of this type when deleting a user.
     *
     * @param bool $deleteWithUser
     * @return $this
     */
    public function setDeleteWithUser(bool $deleteWithUser): static
    {
        $this->deleteWithUser = $deleteWithUser;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get an array of blocks to use as the default initial state for an editor
     * session.
     *
     * @return array
     */
    public function getTemplate(): array
    {
        return $this->template ?? [];
    }

    /**
     * Set an array of blocks to use as the default initial state for an editor
     * session.
     *
     * @param array $template
     * @return $this
     */
    public function setTemplate(array $template): static
    {
        $this->template = $template;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the block template should be locked if `$template` is set.
     *
     * @return false|string|null
     */
    public function getTemplateLock(): false|string|null
    {
        return $this->templateLock ?? false;
    }

    /**
     * Set whether the block template should be locked if `$template` is set.
     *
     * @param string $templateLock
     * @return $this
     */
    public function setTemplateLock(string $templateLock): static
    {
        $this->templateLock = $templateLock;

        return $this;
    }

    // *************************************************************************

    /**
     * Load the instance from a key.
     *
     * @param string $key
     */
    protected function loadFromKey(string $key): void
    {
        if (!$wpPostType = get_post_type_object($key)) {
            return;
        }

        $this->loadFromWpPostType($wpPostType);
    }

    /**
     * Load the instance from a `WP_Post_Type` instance.
     *
     * @param WP_Post_Type $wpPostType
     */
    protected function loadFromWpPostType(WP_Post_Type $wpPostType): void
    {
        $this->wpPostType = $wpPostType;

        $this->key = $wpPostType->name;
        $this->label = $wpPostType->labels->name;
        $this->labels = (array) $wpPostType->labels;
        $this->description = $wpPostType->description;
        $this->public = $wpPostType->public;
        $this->hierarchical = $wpPostType->hierarchical;
        $this->excludeFromSearch = $wpPostType->exclude_from_search;
        $this->publiclyQueryable = $wpPostType->publicly_queryable;
        $this->showUi = $wpPostType->show_ui;
        $this->showInMenu = $wpPostType->show_in_menu;
        $this->showInNavMenus = $wpPostType->show_in_nav_menus;
        $this->showInAdminBar = $wpPostType->show_in_admin_bar;
        $this->showInRest = $wpPostType->show_in_rest;
        $this->restBase = $wpPostType->rest_base;
        $this->restNamespace = $wpPostType->rest_namespace;
        $this->restControllerClass = $wpPostType->rest_controller_class;
        $this->autosaveRestControllerClass = $wpPostType->autosave_rest_controller_class;
        $this->revisionsRestControllerClass = $wpPostType->revisions_rest_controller_class;
        $this->lateRouteRegistration = $wpPostType->late_route_registration;
        $this->menuPosition = $wpPostType->menu_position;
        $this->menuIcon = $wpPostType->menu_icon;
        $this->capabilityType = $wpPostType->capability_type;
        $this->capabilities = (array) $wpPostType->cap;
        $this->mapMetaCap = $wpPostType->map_meta_cap;
        $this->supports = array_keys(get_all_post_type_supports($this->key));
        $this->registerMetaBoxCb = $wpPostType->register_meta_box_cb;
        $this->taxonomies = get_object_taxonomies($this->key);
        $this->hasArchive = $wpPostType->has_archive;
        $this->rewrite = $wpPostType->rewrite;
        $this->queryVar = $wpPostType->query_var;
        $this->canExport = $wpPostType->can_export;
        $this->deleteWithUser = $wpPostType->delete_with_user;
        $this->template = $wpPostType->template;
        $this->templateLock = $wpPostType->template_lock;

        $this->registered = true;
    }

    // -------------------------------------------------------------------------

    /**
     * Reload the instance from the database.
     */
    protected function reload(): void
    {
        if (!$this->registered) {
            return;
        }

        $this->loadFromKey($this->key);
    }

    // -------------------------------------------------------------------------

    /**
     * Cast the post type to an array to be used by WordPress functions.
     *
     * Remove keys from the array if the value is null, since that indicates
     * that no value has been set.
     *
     * @param array $except
     * @return array
     */
    protected function toWpPostTypeArray(array $except = []): array
    {
        $data = [
            'label' => $this->label,
            'labels' => $this->labels,
            'description' => $this->description,
            'public' => $this->public,
            'hierarchical' => $this->hierarchical,
            'exclude_from_search' => $this->excludeFromSearch,
            'publicly_queryable' => $this->publiclyQueryable,
            'show_ui' => $this->showUi,
            'show_in_menu' => $this->showInMenu,
            'show_in_nav_menus' => $this->showInNavMenus,
            'show_in_admin_bar' => $this->showInAdminBar,
            'show_in_rest' => $this->showInRest,
            'rest_base' => $this->restBase,
            'rest_namespace' => $this->restNamespace,
            'rest_controller_class' => $this->restControllerClass,
            'autosave_rest_controller_class' => $this->autosaveRestControllerClass,
            'revisions_rest_controller_class' => $this->revisionsRestControllerClass,
            'late_route_registration' => $this->lateRouteRegistration,
            'menu_position' => $this->menuPosition,
            'menu_icon' => $this->menuIcon,
            'capability_type' => $this->capabilityType,
            'capabilities' => $this->capabilities,
            'map_meta_cap' => $this->mapMetaCap,
            'supports' => $this->supports,
            'register_meta_box_cb' => $this->registerMetaBoxCb,
            'taxonomies' => $this->taxonomies,
            'has_archive' => $this->hasArchive,
            'rewrite' => $this->rewrite,
            'query_var' => $this->queryVar,
            'can_export' => $this->canExport,
            'delete_with_user' => $this->deleteWithUser,
            'template' => $this->template,
            'template_lock' => $this->templateLock,
        ];

        return Filter::array($data)->except($except)->withoutNulls()->get();
    }
}