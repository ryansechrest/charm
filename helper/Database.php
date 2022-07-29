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
     * @var wpdb|null
     */
    private ?wpdb $wpdb = null;

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
     * Create table
     *
     * @param string $table
     * @param array $lines
     * @return bool
     */
    public function create_table(string $table, array $lines): bool
    {
        $sql = 'CREATE TABLE ' . $this->prefix($table) . ' (';
        $sql .= $this->sql_commas($lines);
        $sql .= ') ' . $this->wpdb->get_charset_collate() . ';';
        if ($this->query($sql) === true) {
            return true;
        }

        return false;
    }

    /**
     * Truncate table
     *
     * @param string $table
     * @return bool
     */
    public function truncate_table(string $table): bool
    {
        $sql = 'TRUNCATE ' . $this->prefix($table) . ';';
        if ($this->query($sql) === true) {
            return true;
        }

        return false;
    }

    /**
     * Drop table
     *
     * @param string $table
     * @return bool
     */
    public function drop_table(string $table): bool
    {
        $sql = 'DROP TABLE ' . $this->prefix($table) . ';';
        if ($this->query($sql) === true) {
            return true;
        }

        return false;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Select from table where id
     *
     * @param string $table
     * @param int $id
     * @param string $key
     * @return object|null
     */
    public function select_where_id(string $table, int $id, string $key = 'id'): ?object
    {
        $records = $this->select_where(
            [], [$table], [$key => $id], 1
        );
        if (count($records) === 1) {
            return $records[0];
        }

        return null;
    }

    /**
     * Select from table where
     *
     * @param array $fields
     * @param array $table
     * @param array $conditions
     * @param string $order_by
     * @param int $limit
     * @return array
     */
    public function select_where(
        array $fields,
        array $table,
        array $conditions = [],
        string $order_by = '',
        int $limit = 0
    ): array
    {
        $query = [
            $this->sql_select($fields),
            $this->sql_from($table),
        ];
        $params = [];
        if (count($conditions) > 0) {
            $query[] = $this->sql_where($this->sql_types($conditions));
            $params = array_values($conditions);
        }
        if ($order_by !== '') {
            $query[] = 'ORDER BY ' . $order_by;
        }
        if ($limit > 0) {
            $query[] = 'LIMIT %d';
            $params[] = $limit;
        }
        $sql = $this->sql_spaces($query) . ';';
        if ($this->query($sql, $params) > 0) {
            return $this->wpdb->last_result;
        }

        return [];
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Insert into table
     *
     * @param string $table
     * @param array $columns
     * @return int (ID=success; 0=fail)
     */
    public function insert_into(string $table, array $columns): int
    {
        $sql = 'INSERT INTO ' . $this->prefix($table) . ' (';
        $sql .= $this->sql_commas(array_keys($columns));
        $sql .= ') VALUES (';
        $sql .= $this->sql_commas($this->sql_types($columns));
        $sql .= ');';
        if ($this->query($sql, $columns) > 0) {
            return $this->wpdb->insert_id;
        }

        return 0;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Update in table where id
     *
     * @param string $table
     * @param array $columns
     * @param int $id
     * @param string $key
     * @return bool
     */
    public function update_where_id(
        string $table, array $columns, int $id, string $key = 'id'
    ): bool
    {
        return $this->update($table, $columns, [$key => $id]);
    }

    /**
     * Update in table
     *
     * @param string $table
     * @param array $columns
     * @param array $conditions
     * @return bool
     */
    public function update(string $table, array $columns, array $conditions): bool
    {
        $sql = 'UPDATE ' . $this->prefix($table) . ' SET ';
        $sql .= $this->sql_commas($this->sql_equal($this->sql_types($columns))) . ' ';
        $sql .= $this->sql_where($this->sql_types($conditions));
        $sql .= ';';
        $params = array_merge(
            array_values($columns), array_values($conditions)
        );
        if ($this->query($sql, $params) > 0) {
            return true;
        }

        return false;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Delete from table where id
     *
     * @param int $id
     * @param string $table
     * @param string $key
     * @return bool
     */
    public function delete_where_id(int $id, string $table, string $key = 'id'): bool
    {
        return $this->delete_from($table, [$key => $id]);
    }

    /**
     * Delete from table
     *
     * @param string $table
     * @param array $conditions
     * @return bool
     */
    public function delete_from(string $table, array $conditions): bool
    {
        $sql = $this->sql_spaces([
            'DELETE',
            $this->sql_from([$table]),
            $this->sql_where($this->sql_types($conditions))
        ]) . ';';
        if ($this->query($sql, $conditions) > 0) {
            return true;
        }

        return false;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Query WordPress database
     *
     * @param string $sql
     * @param array $data
     * @return bool|int
     */
    public function query(string $sql, array $data = []): bool|int
    {
        if (count($data) > 0) {
            $sql = $this->wpdb->prepare($sql, $data);
        }

        return $this->wpdb->query($sql);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Build SELECT statement
     *
     * @param array $fields
     * @return string
     */
    protected function sql_select(array $fields = []): string
    {
        if (count($fields) !== 0) {
            $sql = $this->sql_commas($fields);
        } else {
            $sql = '*';
        }

        return 'SELECT ' . $sql;
    }

    /**
     * Build FROM statement
     *
     * @param array $tables
     * @return string
     */
    protected function sql_from(array $tables): string
    {
        $from = [];
        foreach ($tables as $key => $table) {
            if (!is_int($key)) {
                $from[] = $this->prefix($key) . ' ' . $table;
            } else {
                $from[] = $this->prefix($table);
            }
        }

        return 'FROM ' . $this->sql_commas($from);
    }

    /**
     * Build WHERE statement
     *
     * @param array $conditions
     * @return string
     */
    protected function sql_where(array $conditions): string
    {
        $where = [];
        foreach ($conditions as $key => $condition) {
            if (!is_int($key)) {
                $where[] = $key . ' = ' . $condition;
            } else {
                $where[] = $condition;
            }
        }

        return 'WHERE ' . $this->sql_spaces($where);
    }

    /**
     * Replace values with types for preparing statement
     *   Before: ['id' => 12345, 'name' => 'Hello World']
     *   After:  ['id' => '%d', 'name' => '%s']
     *
     * @param array $lines
     * @return array
     */
    protected function sql_types(array $lines): array
    {
        return array_map(function($line) {
            if (is_int($line)) {
                return '%d';
            } elseif (is_float($line)) {
                return '%f';
            }

            return '%s';
        }, $lines);
    }

    /**
     * Combine keys and values with equal sign
     *   Before: ['hello' => 'world', 'foo' = 'bar']
     *   After:  ['hello = world', 'foo = bar']
     *
     * @param array $lines
     * @return array
     */
    protected function sql_equal(array $lines): array
    {
        $new_lines = [];
        foreach ($lines as $key => $value) {
            $new_lines[] = $key . ' = ' . $value;
        }

        return $new_lines;
    }

    /**
     * Combine lines with commas
     *   Before: ['hello', 'world']
     *   After:  'hello, world'
     *
     * @param array $lines
     * @return string
     */
    protected function sql_commas(array $lines): string
    {
        return implode(', ', $lines);
    }

    /**
     * Combine lines with spaces
     *   Before: ['hello', 'world']
     *   After:  'hello world'
     *
     * @param array $lines
     * @return string
     */
    protected function sql_spaces(array $lines): string
    {
        return implode(' ', $lines);
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