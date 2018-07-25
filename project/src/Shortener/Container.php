<?php

namespace Shortener;

/**
 * Class Config
 *
 * @package Shortener
 */
class Container implements \ArrayAccess
{
    /**
     * @var array
     */
    private $values = [];

    /**
     *
     * @var array
     */
    private $raw = [];


    /**
     * Config constructor.
     *
     * @param array $values
     */
    public function __construct(array $values = array())
    {
        foreach ($values as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    /**
     * Set value
     *
     * @param string $key
     * @param mixed $value
     */
    public function offsetSet($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * Get value
     *
     * @param string $key
     * @return mixed
     * @throws Exception
     */
    public function offsetGet($key)
    {
        if (!isset($this->values[$key])) {
            throw new Exception("Key '{$key}' doesn't exists ");
        }

        if (isset($this->raw[$key]) || !is_object($this->values[$key])) {
            return $this->values[$key];
        }

        $raw = $this->values[$key];
        $val = $this->values[$key] = $raw($this);
        $this->raw[$key] = $raw;

        return $val;
    }

    /**
     * Check if $key is exists
     *
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key) : bool
    {
        return isset($this->values[$key]);
    }

    /**
     * Unset $key
     *
     * @param mixed $id
     */
    public function offsetUnset($id)
    {
        if (!isset($this->values[$id])) {
            return;
        }

        unset($this->values[$id], $this->raw[$id]);
    }
}
