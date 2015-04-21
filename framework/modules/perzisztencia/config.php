<?php

/**
 * Class Config
 */
class Config
{
    /**
     * @var
     */
    private $config;

    /**
     * @param string $path
     */
    public function __construct($path = 'config/config_file.php')
    {
        $this->config = include $path;
    }

    /**
     * @param $key
     * @return bool
     */
    public function exists($key)
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * @param $key
     * @return bool
     */
    public function get($key)
    {
        if ($this->exists($key)) {
            return $this->config['key'];
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->config;
    }
}