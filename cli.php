<?php

namespace Charm;

if (!isset($argv)) {
    die('Whatcha doin? Ƹ̵̡Ӝ̵̨̄Ʒ');
}

/**
 * Class CLI
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class CLI
{
    /**
     * Version
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /************************************************************************************/

    /**
     * Commands
     *
     * @var array
     */
    private $commands = [];

    /**
     * Logs
     *
     * @var array
     */
    private $logs = [];

    /************************************************************************************/

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->commands = [
            'a' => 'activate',
            'u' => 'update',
            'd' => 'deactivate',
            'h' => 'help',
        ];
        $this->logs = [];
    }

    /************************************************************************************/

    /**
     * Initialize Charm
     *
     * @return CLI
     */
    public static function init(): CLI
    {
        return new CLI();
    }

    /************************************************************************************/

    /**
     * Run command
     *
     * @param array $args
     * @return bool
     */
    public function run(array $args): bool
    {
        if (!isset($args[1])) {
            $this->log($this->red('Please provide a command:'));
            $this->log();
            $this->help();
            return false;
        }
        $command = $args[1];
        if (!in_array($command, $this->commands)
            && !array_key_exists($command, $this->commands)
        ) {
            $this->log($this->red('Please provide a valid command:'));
            $this->log();
            $this->help();
            return false;
        }
        // Replace command abbreviation with full name
        if (isset($this->commands[$command])) {
            $command = $this->commands[$command];
        }
        if (!method_exists($this, $command)) {
            $this->log($this->red('Command does not exist.'));
            $this->log();
            $this->help();
            return false;
        }
        call_user_func([$this, $command]);

        return true;
    }

    /**
     * Activate Charm
     *
     * @return bool
     */
    private function activate(): bool
    {
        $this->log_subheader('Activate');
        if (!$this->copy_plugin_file()) {
            return false;
        }

        return true;
    }

    /**
     * Update Charm
     *
     * @return bool
     */
    private function update(): bool
    {
        $this->log_subheader('Update');
        if (!$this->run_git_pull()) {
            return false;
        }
        if (!$this->copy_plugin_file()) {
            return false;
        }

        return true;
    }

    /**
     * Deactivate Charm
     *
     * @return bool
     */
    private function deactivate(): bool
    {
        $this->log_subheader('Deactivate');
        if (!$this->remove_plugin_file()) {
            return false;
        }

        return true;
    }

    /**
     * List available commands
     */
    private function help(): void
    {
        $this->log($this->white('[a]ctivate') . ' ..................... Activate Charm plugin');
        $this->log($this->white('[u]pdate') . ' .............. Update Charm to latest version');
        $this->log($this->white('[d]eactivate') . ' ................. Deactivate Charm plugin');
        $this->log($this->white('[h]elp') . ' ......................... View list of commands');
        $this->log();
    }

    /************************************************************************************/

    /**
     * Run git pull
     *
     * @return bool
     */
    private function run_git_pull(): bool
    {
        $this->log_action(
            'Move into ' . $this->mark('wp-content/mu-plugins/charm') . ' directory'
        );
        if (!chdir(dirname(__FILE__))) {
            $this->log_error('Directory does not exist.');
            return false;
        }
        $this->log_action(
            'Run ' . $this->mark('git pull origin master') . ' to get latest version'
        );
        $result = shell_exec('git pull origin master');
        if (strpos($result, 'Already up to date.') !== false) {
            $this->log_info('Charm is up to date.');
            return false;
        }
        $this->log_success('Charm updated.');

        return true;
    }

    /**
     * Copy plugin file
     *
     * @return bool
     */
    private function copy_plugin_file(): bool
    {
        $source_file = dirname(__FILE__) . '/plugin.php';
        $destination_file = dirname(dirname(__FILE__)) . '/_charm.php';
        $this->log_action(
            'Copy ' . $this->mark('charm/plugin.php') . ' to ' . $this->mark('mu-plugins/_charm.php')
        );
        if (!file_exists($source_file)) {
            $this->log_error('File does not exist.');
            return false;
        }
        if (!copy($source_file, $destination_file)) {
            $this->log_error('File could not be copied.');
            return false;
        }
        $this->log_success('File copied.');

        return true;
    }

    /**
     * Remove plugin file
     *
     * @return bool
     */
    private function remove_plugin_file(): bool
    {
        $file = dirname(dirname(__FILE__)) . '/_charm.php';
        $this->log_action(
            'Remove ' . $this->mark('mu-plugins/_charm.php')
        );
        if (!file_exists($file)) {
            $this->log_error('File does not exist.');
            return false;
        }
        if (!unlink($file)) {
            $this->log_error('File could not be removed.');
            return false;
        }
        $this->log_success('File removed.');

        return true;
    }

    /************************************************************************************/

    /**
     * Start Charm process
     */
    public function start(): void
    {
        $this->log_header();
    }

    /**
     * Finish Charm process
     */
    public function finish(): void
    {
        $this->log_footer();
        $this->display_log();
    }

    /************************************************************************************/

    /**
     * Log Charm header
     */
    private function log_header(): void
    {
        $this->log();
        $this->log($this->yellow('Charm v' . self::VERSION));
        $this->log('====================================== wpcharm.com Ƹ̵̡Ӝ̵̨̄Ʒ');
        $this->log();
    }

    /**
     * Log subheader for action(s)
     *
     * @param string $text
     */
    private function log_subheader(string $text): void
    {
        $this->log('# ' . $text);
        $line = '---';
        for ($i = 1; $i <= strlen($text); $i++) {
            $line .= '-';
        }
        $this->log($line);
        $this->log();
    }

    /**
     * Log Charm footer
     */
    private function log_footer(): void
    {
        $this->log('================================= by Ryan Sechrest Ƹ̵̡Ӝ̵̨̄Ʒ');
        $this->log('Thank you for using Charm!');
        $this->log();
    }

    /**
     * Log line of text
     *
     * @param string $text
     */
    private function log(string $text = ''): void
    {
        $this->logs[] = $text;
    }

    /**
     * Log line of action
     *
     * @param string $text
     */
    private function log_action(string $text): void
    {
        $this->log('🔸️️️️ ' . $text . '...');
    }

    /**
     * Log line of info
     *
     * @param string $text
     */
    private function log_info(string $text): void
    {
        $this->log('   ' . $this->yellow('Info: ' . $text));
        $this->log();
    }

    /**
     * Log line of success
     *
     * @param string $text
     */
    private function log_success(string $text): void
    {
        $this->log('   ' . $this->green('Success: ' . $text));
        $this->log();
    }

    /**
     * Log line of error
     *
     * @param string $text
     */
    private function log_error(string $text): void
    {
        $this->log('   ' . $this->red('Error: ' . $text));
        $this->log();
    }

    /**
     * Display log on screen
     */
    private function display_log(): void
    {
        $this->logs = array_map(function($log) {
            return ' ' . $log;
        }, $this->logs);
        $this->log();
        echo implode("\n", $this->logs);
    }

    /************************************************************************************/

    /**
     * Mark text in chosen color
     *
     * @param string $text
     * @return string
     */
    private function mark(string $text): string
    {
        return $this->white($text);
    }

    /**
     * Make text color red
     *
     * @param string $text
     * @return string
     */
    private function red(string $text): string
    {
        return "\e[1;31m" . $text . "\e[0m";
    }

    /**
     * Make text color green
     *
     * @param string $text
     * @return string
     */
    private function green(string $text): string
    {
        return "\e[1;32m" . $text . "\e[0m";
    }

    /**
     * Make text color yellow
     *
     * @param string $text
     * @return string
     */
    private function yellow(string $text): string
    {
        return "\e[1;33m" . $text . "\e[0m";
    }

    /**
     * Make text color blue
     *
     * @param string $text
     * @return string
     */
    private function blue(string $text): string
    {
        return "\e[1;34m" . $text . "\e[0m";
    }

    /**
     * Make text color magenta
     *
     * @param string $text
     * @return string
     */
    private function magenta(string $text): string
    {
        return "\e[1;35m" . $text . "\e[0m";
    }

    /**
     * Make text color cyan
     *
     * @param string $text
     * @return string
     */
    private function cyan(string $text): string
    {
        return "\e[1;36m" . $text . "\e[0m";
    }

    /**
     * Make text color white
     *
     * @param string $text
     * @return string
     */
    private function white(string $text): string
    {
        return "\e[1;37m" . $text . "\e[0m";
    }
}

$cli = CLI::init();
$cli->start();
$cli->run($argv);
$cli->finish();