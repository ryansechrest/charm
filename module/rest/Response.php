<?php

namespace Charm\Module\Rest;

/**
 * Class Response
 *
 * @author Ryan Sechrest
 * @package Charm\Module\Rest
 */
class Response
{
    /************************************************************************************/
    // Constants

    /**
     * Status codes
     *
     * @var array
     */
    const STATUS_CODES = [
        200 => [
            'status' => 'success',
            'label' => 'OK',
        ],
        201 => [
            'status' => 'success',
            'label' => 'Created',
        ],
        202 => [
            'status' => 'success',
            'label' => 'Accepted',
        ],
        400 => [
            'status' => 'error',
            'label' => 'Bad Request',
        ],
        401 => [
            'status' => 'error',
            'label' => 'Unauthorized',
        ],
        403 => [
            'status' => 'error',
            'label' => 'Forbidden',
        ],
        404 => [
            'status' => 'error',
            'label' => 'Not Found',
        ],
        406 => [
            'status' => 'error',
            'label' => 'Not Acceptable',
        ],
        500 => [
            'status' => 'error',
            'label' => 'Internal Server Error',
        ],
    ];

    /************************************************************************************/
    // Properties

    /**
     * Status
     *  e.g. 'success', 'error'
     *
     * @var string
     */
    protected string $status = '';

    /**
     * Code
     *  e.g. '200', '400'
     *
     * @var int
     */
    protected int $code = 0;

    /**
     * Label
     *  e.g. 'OK', 'BAD REQUEST'
     *
     * @var string
     */
    protected string $label = '';

    /**
     * Message
     *  e.g. 'Preferences could not be saved.'
     *
     * @var string
     */
    protected string $message = '';

    /**
     * Data
     *
     * @var array
     */
    protected array $data = [];

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Response constructor
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
        if (isset($data['status'])) {
            $this->status = $data['status'];
        }
        if (isset($data['code'])) {
            $this->code = $data['code'];
        }
        if (isset($data['label'])) {
            $this->label = $data['label'];
        }
        if (isset($data['message'])) {
            $this->message = $data['message'];
        }
        if (isset($data['data'])) {
            $this->data = $data['data'];
        }
    }

    /**
     * Autocomplete from code
     */
    public function autocomplete(): void
    {
        if ($this->code === 0) {
            return;
        }
        if (!isset(static::STATUS_CODES[$this->code])) {
            return;
        }
        $status_code = static::STATUS_CODES[$this->code];
        if ($this->status === '') {
            $this->status = $status_code['status'];
        }
        if ($this->label === '') {
            $this->label = $status_code['label'];
        }
        if ($this->message === '') {
            $this->message = $status_code['message'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * New generic response
     *
     * @param array $params
     * @return static
     */
    public static function new(array $params): Response
    {
        return new static($params);
    }

    /**
     * New generic success response
     *
     * @param array $params
     * @return Response
     */
    public static function success(array $params): Response
    {
        $params['status'] = 'success';

        return new static($params);
    }

    /**
     * New generic error response
     *
     * @param array $params
     * @return Response
     */
    public static function error(array $params): Response
    {
        $params['status'] = 'error';

        return new static($params);
    }

    /**
     * New specified code response
     *
     * @param array $params
     * @return Response
     */
    public static function code(array $params): Response
    {
        $response = new static($params);
        $response->autocomplete();

        return $response;
    }

    /**
     * New specified code response and send
     *
     * @param array $params
     */
    public static function send(array $params): void
    {
        $response = static::code($params);
        echo $response->to_json();
        die();
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
        $data['status'] = $this->status;
        $data['code'] = $this->code;
        $data['label'] = $this->label;
        $data['message'] = $this->message;
        $data['data'] = $this->data;

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
     * Get code
     *
     * @return int
     */
    public function get_code(): int
    {
        return $this->code;
    }

    /**
     * Set code
     *
     * @param int $code
     */
    public function set_code(int $code): void
    {
        $this->code = $code;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get label
     *
     * @return string
     */
    public function get_label(): string
    {
        return $this->label;
    }

    /**
     * Set label
     *
     * @param string $label
     */
    public function set_label(string $label): void
    {
        $this->label = $label;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get status
     *
     * @return string
     */
    public function get_status(): string
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function set_status(string $status): void
    {
        $this->status = $status;
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
     * Get data
     *
     * @return array
     */
    public function get_data(): array
    {
        return $this->data;
    }

    /**
     * Set data
     *
     * @param array $data
     */
    public function set_data(array $data): void
    {
        $this->data = $data;
    }
}