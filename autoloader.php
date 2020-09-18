<?php

namespace Charm;

if (!defined('ABSPATH')) {
    die('Whatcha doin? Ƹ̵̡Ӝ̵̨̄Ʒ');
}

// WordPress Cron
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/cron/Event.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/cron/Schedule.php';

// WordPress Modules
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/module/PostType.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/module/Role.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/module/Taxonomy.php';

// WordPress Entities
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/Meta.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/Post.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/Taxonomy.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/Term.php';
require_once WPMU_PLUGIN_DIR . '/charm/wordpress/User.php';

/****************************************************************************************/

// Data Types
require_once WPMU_PLUGIN_DIR . '/charm/data-type/DateTime.php';

// Features
require_once WPMU_PLUGIN_DIR . '/charm/feature/Meta.php';

// Helpers
require_once WPMU_PLUGIN_DIR . '/charm/helper/Converter.php';

// Cron
require_once WPMU_PLUGIN_DIR . '/charm/cron/Event.php';
require_once WPMU_PLUGIN_DIR . '/charm/cron/Schedule.php';
require_once WPMU_PLUGIN_DIR . '/charm/cron/Cron.php';

// Modules
require_once WPMU_PLUGIN_DIR . '/charm/module/PostType.php';
require_once WPMU_PLUGIN_DIR . '/charm/module/Role.php';
require_once WPMU_PLUGIN_DIR . '/charm/module/Taxonomy.php';

// Skeletons
require_once WPMU_PLUGIN_DIR . '/charm/skeleton/PostType.php';
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
require_once WPMU_PLUGIN_DIR . '/charm/entity/Post.php';
require_once WPMU_PLUGIN_DIR . '/charm/entity/Page.php';
require_once WPMU_PLUGIN_DIR . '/charm/entity/Taxonomy.php';
require_once WPMU_PLUGIN_DIR . '/charm/entity/Term.php';
require_once WPMU_PLUGIN_DIR . '/charm/entity/User.php';

/****************************************************************************************/

require_once WPMU_PLUGIN_DIR . '/charm/Charm.php';