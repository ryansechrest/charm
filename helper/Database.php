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
     * @return object|null
     */
    public function select_where_id(string $table, int $id): ?object
    {
        $records = $this->select_where($table, [
            'id = ' . $id,
            'LIMIT 1',
        ]);
        if (count($records) === 1) {
            return $records[0];
        }

        return null;
    }

    /**
     * Select from table where
     *
     * @param string $table
     * @param array $conditions
     * @return array
     */
    public function select_where(string $table, array $conditions): array
    {
        return $this->select([], [
            $this->sql_from([$table]),
            $this->sql_where($conditions),
        ]);
    }

    /**
     * Select from table
     *
     * @param array $fields
     * @param array $lines
     * @return array
     */
    public function select(array $fields, array $lines): array
    {
        $sql = $this->sql_spaces([
            $this->sql_select($fields),
            $this->sql_spaces($lines),
        ]);
        if ($this->query($sql) > 0) {
            return $this->wpdb->last_result;
        };

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
        $sql .= $this->sql_commas(
            array_map(function($value) {
                return '"' . $value . '"';
            }, array_values($columns))
        );
        $sql .= ');';
        if ($this->query($sql) > 0) {
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
     * @return bool
     */
    public function update_where_id(string $table, array $columns, int $id)
    {
        return $this->update($table, $columns, ['id' => $id]);
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
        $sets = [];
        foreach ($columns as $key => $value) {
            $sets[] = $key . ' = ' . '"' . $value . '"';
        }
        $sql .= $this->sql_commas($sets) . ' ';
        $sql .= $this->sql_where($conditions);
        if ($this->query($sql) > 0) {
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
     * @return bool
     */
    public function delete_where_id(int $id, string $table): bool
    {
        return $this->delete_from($table, ['id' => $id]);
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
            $this->sql_where($conditions)
        ]) . ';';
        if ($this->query($sql) > 0) {
            return true;
        }

        return false;
    }

    /*----------------------------------------------------------------------------------*/

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
            $sql = implode(', ', $fields);
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
                $from[] = $this->prefix($key) . ' AS ' . $table;
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
                $where[] = $key . ' = "' . $condition . '"';
            } else {
                $where[] = $condition;
            }
        }

        return 'WHERE ' . $this->sql_spaces($where);
    }

    /**
     * Combine lines with commas
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