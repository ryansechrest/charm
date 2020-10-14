<?php

namespace Charm\Helper;

use wpdb;

/**
 * Class Database
 *
 * @author Ryan Sechrest
 * @package Charm\Helper
 */
class Database
{
    /************************************************************************************/
    // Properties

    /**
     * WordPress database
     *
     * @var wpdb
     */
    private $wpdb = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Database constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
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
        if (isset($data['wpdb'])) {
            $this->wpdb = $data['wpdb'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize database
     *
     * @return static
     */
    public static function init(): Database
    {
        global $wpdb;

        return new Database([
            'wpdb' => $wpdb
        ]);
    }

    /************************************************************************************/
    // Action methods

    /**
     * Create table in database
     *
     * @param string $table
     * @param array $lines
     * @return bool
     */
    public function create_table(string $table, array $lines): bool
    {
        $sql = 'CREATE TABLE ' . $this->prefix($table) . ' (';
        $sql .= implode(', ', $lines);
        $sql .= ') ' . $this->wpdb->get_charset_collate() . ';';
        if ($this->query($sql) === true) {
            return true;
        }

        return false;
    }

    /**
     * Insert data into table
     *
     * @param string $table
     * @param array $columns
     * @return bool
     */
    public function insert_into(string $table, array $columns): bool
    {
        $sql = 'INSERT INTO ' . $this->prefix($table) . ' (';
        $sql .= implode(', ', array_keys($columns));
        $sql .= ') VALUES (';
        $sql .= implode(', ', array_map(function($value) {
            return '"' . $value . '"';
        }, array_values($columns)));
        $sql .= ');';
        if ($this->query($sql) === 1) {
            return true;
        }

        return false;
    }

    /**
     * Check if table exists
     *
     * @param string $table
     * @return bool
     */
    public function table_exists(string $table): bool
    {
        $sql = 'SHOW TABLES LIKE "' . $this->prefix($table) . '";';
        if ($this->query($sql) === 1) {
            return true;
        }

        return false;
    }

    /**
     * Query WordPress database
     *
     * @param string $sql
     * @return bool|int
     */
    public function query(string $sql)
    {
        return $this->wpdb->query($sql);
    }

    /************************************************************************************/
    // Helper methods

    /**
     * Prefix table name
     *   e.g. 'posts' -> 'wp_posts'
     *
     * @param string $table
     * @return string
     */
    public function prefix(string $table): string
    {
        return $this->wpdb->prefix . $table;
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get (or set) WordPress database
     *
     * @param wpdb|null $wpdb
     * @return wpdb
     */
    public function wpdb(wpdb $wpdb = null): wpdb
    {
        if ($wpdb !== null) {
            $this->wpdb = $wpdb;
        }

        return $this->wpdb;
    }
}