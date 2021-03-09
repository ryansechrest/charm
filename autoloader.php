<?php

namespace Charm;

if (!defined('ABSPATH')) {
    die('Whatcha doin? Ƹ̵̡Ӝ̵̨̄Ʒ');
}

// WordPress Conductors
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/conductor/ObjectTaxonomy.php';

// WordPress Cron
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/cron/Event.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/cron/Schedule.php';

// WordPress Modules
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/module/MenuLocation.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/module/PostType.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/module/RestRoute.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/module/Role.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/module/Taxonomy.php';

// WordPress Entities
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/Meta.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/NavMenuItem.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/Option.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/Post.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/Term.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/NavMenu.php'; // Needs Term
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/User.php';

/****************************************************************************************/

// Conductor
require_once WPMU_PLUGIN_DIR . '/charm/conductor/ObjectTaxonomy.php';

// Data Types
require_once WPMU_PLUGIN_DIR . '/charm/data-type/DateTime.php';

// Features
require_once WPMU_PLUGIN_DIR . '/charm/feature/Meta.php';
require_once WPMU_PLUGIN_DIR . '/charm/feature/Taxonomy.php';

// Helpers
require_once WPMU_PLUGIN_DIR . '/charm/helper/Cast.php';
require_once WPMU_PLUGIN_DIR . '/charm/helper/Convert.php';
require_once WPMU_PLUGIN_DIR . '/charm/helper/Database.php';
require_once WPMU_PLUGIN_DIR . '/charm/helper/Location.php';

// Cron
require_once WPMU_PLUGIN_DIR . '/charm/cron/Event.php';
require_once WPMU_PLUGIN_DIR . '/charm/cron/Schedule.php';
require_once WPMU_PLUGIN_DIR . '/charm/cron/Cron.php';

// Modules
require_once WPMU_PLUGIN_DIR . '/charm/module/MenuLocation.php';
require_once WPMU_PLUGIN_DIR . '/charm/module/PostType.php';
require_once WPMU_PLUGIN_DIR . '/charm/module/Role.php';
require_once WPMU_PLUGIN_DIR . '/charm/module/Taxonomy.php';

// Modules: REST
require_once WPMU_PLUGIN_DIR . '/charm/module/rest/param/Param.php';
require_once WPMU_PLUGIN_DIR . '/charm/module/rest/param/ArrayParam.php';
require_once WPMU_PLUGIN_DIR . '/charm/module/rest/param/BooleanParam.php';
require_once WPMU_PLUGIN_DIR . '/charm/module/rest/param/IntegerParam.php';
require_once WPMU_PLUGIN_DIR . '/charm/module/rest/param/NullParam.php';
require_once WPMU_PLUGIN_DIR . '/charm/module/rest/param/NumberParam.php';
require_once WPMU_PLUGIN_DIR . '/charm/module/rest/param/ObjectParam.php';
require_once WPMU_PLUGIN_DIR . '/charm/module/rest/param/StringParam.php';
require_once WPMU_PLUGIN_DIR . '/charm/module/rest/Endpoint.php';
require_once WPMU_PLUGIN_DIR . '/charm/module/rest/Response.php';
require_once WPMU_PLUGIN_DIR . '/charm/module/rest/Route.php';

// Skeletons
require_once WPMU_PLUGIN_DIR . '/charm/skeleton/MenuLocation.php';
require_once WPMU_PLUGIN_DIR . '/charm/skeleton/PostType.php';
require_once WPMU_PLUGIN_DIR . '/charm/skeleton/RestRoute.php';
require_once WPMU_PLUGIN_DIR . '/charm/skeleton/Role.php';
require_once WPMU_PLUGIN_DIR . '/charm/skeleton/Taxonomy.php';

/****************************************************************************************/

// Application Meta Entities
require_once WPMU_PLUGIN_DIR . '/charm/entity/Meta.php';
require_once WPMU_PLUGIN_DIR . '/charm/entity/CommentMeta.php';
require_once WPMU_PLUGIN_DIR . '/charm/entity/PostMeta.php';
require_once WPMU_PLUGIN_DIR . '/charm/entity/TermMeta.php';
require_once WPMU_PLUGIN_DIR . '/charm/entity/UserMeta.php';

// Application Entities
require_once WPMU_PLUGIN_DIR . '/charm/entity/Log.php';
require_once WPMU_PLUGIN_DIR . '/charm/entity/Post.php';
require_once WPMU_PLUGIN_DIR . '/charm/entity/NavMenuItem.php';
require_once WPMU_PLUGIN_DIR . '/charm/entity/Option.php';
require_once WPMU_PLUGIN_DIR . '/charm/entity/Page.php';
require_once WPMU_PLUGIN_DIR . '/charm/entity/Term.php';
require_once WPMU_PLUGIN_DIR . '/charm/entity/NavMenu.php';
require_once WPMU_PLUGIN_DIR . '/charm/entity/User.php';

/****************************************************************************************/

require_once WPMU_PLUGIN_DIR . '/charm/Charm.php';