<?php

namespace Charm\Module\Rest;

/**
 * Class Endpoint
 *
 * @author Ryan Sechrest
 * @package Charm\Module\Rest
 */
class Endpoint
{
    /************************************************************************************/
    // Properties

    /**
     * Methods
     *
     * @var array
     */
    protected $methods = [];

    /**
     * Params
     *
     * By default, routes receive all arguments passed in from the request. These are
     * merged into a single set of parameters, then added to the Request object, which is
     * passed in as the first parameter to your endpoint.
     *
     * Normally, you’ll get every parameter brought in unaltered. However, you can
     * register your arguments when registering your route, which allows you to run
     * sanitization and validation on these.
     *
     * @var Param[]
     */
    protected $params = [];

    /**
     * Callback
     *
     * After your callback is called, the return value is then converted to JSON, and
     * returned to the client. This allows you to return basically any form of data. In
     * our example above, we’re returning either a string or null, which are automatically
     * handled by the API and converted to JSON.
     *
     * Like any other WordPress function, you can also return a WP_Error instance. This
     * error information will be passed along to the client, along with a 500 Internal
     * Service Error status code. You can further customise your error by setting the
     * status option in the WP_Error instance data to a code, such as 400 for bad
     * input data.
     *
     * @var callable
     */
    protected $callback = null;

    /**
     * Permission callback
     *
     * You must also register a permissions callback for the endpoint. This is a function
     * that checks if the user can perform the action (reading, updating, etc) before the
     * real callback is called. This allows the API to tell the client what actions they
     * can perform on a given URL without needing to attempt the request first.
     *
     * This callback can be registered as permission_callback, again in the endpoint
     * options next to your callback option. This callback should return a boolean or a
     * WP_Error instance. If this function returns true, the response will be processed.
     * If it returns false, a default error message will be returned and the request will
     * not proceed with processing. If it returns a WP_Error, that error will be returned
     * to the client.
     *
     * @var callable
     */
    protected $permission_callback = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Endpoint constructor
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
        if (isset($data['methods'])) {
            $this->methods = $data['methods'];
        }
        if (isset($data['params'])) {
            $this->params = $data['params'];
        }
        if (isset($data['callback'])) {
            $this->callback = $data['callback'];
        }
        if (isset($data['permission_callback'])) {
            $this->permission_callback = $data['permission_callback'];
        }
    }

    /************************************************************************************/
    // Cast methods

    /**
     * Cast properties to array
     *
     * @return array
     */
    public function to_array(): array
    {
        $data = [];
        if (count($this->methods) > 0) {
            $data['methods'] = $this->methods;
        }
        if (count($this->params) > 0) {
            foreach ($this->params as $param) {
                $data['params'][] = $param->to_array();
            }
        }
        if ($this->callback !== null) {
            $data['callback'] = $this->callback;
        }
        if ($this->permission_callback !== null) {
            $data['permission_callback'] = $this->permission_callback;
        }

        return $data;
    }

    /**
     * Cast properties to array for WordPress
     *
     * @return array
     */
    public function to_array_for_wp(): array
    {
        $data = [];
        if (count($this->methods) > 0) {
            $data['methods'] = $this->methods;
        }
        if (count($this->params) > 0) {
            foreach ($this->params as $param) {
                $key = $param->get_name();
                if (method_exists($param, 'to_array_for_wp')) {
                    $data['params'][$key] = $param->to_array_for_wp();
                    continue;
                }
                $data['params'][$key] = $param->to_array();
            }
        }
        if ($this->callback !== null) {
            $data['callback'] = $this->callback;
        }
        if ($this->permission_callback !== null) {
            $data['permission_callback'] = $this->permission_callback;
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
    // Get and set methods

    /**
     * Get methods
     *
     * @return array
     */
    public function get_methods(): array
    {
        return $this->methods;
    }

    /**
     * Add method
     *
     * @param string $method
     */
    public function add_method(string $method): void
    {
        $this->methods[] = $method;
    }

    /**
     * Set methods
     *
     * @param array $methods
     */
    public function set_methods(array $methods): void
    {
        $this->methods = $methods;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get params
     *
     * @return array
     */
    public function get_params(): array
    {
        return $this->params;
    }

    /**
     * Set params
     *
     * @param array $params
     */
    public function set_params(array $params): void
    {
        $this->params = $params;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get callback
     *
     * @return callable
     */
    public function get_callback(): ?callable
    {
        return $this->callback;
    }

    /**
     * Set callback
     *
     * @param callable $callback
     */
    public function set_callback(callable $callback): void
    {
        $this->callback = $callback;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get permission callback
     *
     * @return callable
     */
    public function get_permission_callback(): ?callable
    {
        return $this->permission_callback;
    }

    /**
     * Set permission callback
     *
     * @param callable $permission_callback
     */
    public function set_permission_callback(callable $permission_callback): void
    {
        $this->permission_callback = $permission_callback;
    }
}