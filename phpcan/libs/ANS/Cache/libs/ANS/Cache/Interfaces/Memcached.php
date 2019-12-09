<?php
namespace ANS\Cache\Interfaces;

class Memcached implements \ANS\Cache\Icache
{
    public $loaded = false;

    private $server;
    private $reload = false;

    private $settings = array(
        'exception' => false,
        'expire' => 2592000,
        'host' => 'localhost',
        'port' => 11211
    );

    /**
     * public function __construct ([array $settings])
     *
     * return none
     */
    public function __construct ($settings = array())
    {
        if (!extension_loaded('memcached')) {
            if ($settings['exception']) {
                throw new \UnexpectedValueException('PHP-Memcached extension is not loaded');
            } else {
                return false;
            }
        }

        $this->server = new \Memcached;

        if ($settings['host'] && $settings['port']) {
            $connected = $this->server->addServer($settings['host'], $settings['port']);
        } else {
            $connected = $this->server->addServer('localhost', 11211);
        }

        if (empty($connected)) {
            if ($settings['exception']) {
                throw new \UnexpectedValueException('Can not connect to Memcached server');
            } else {
                return false;
            }
        }

        $this->server->setOption(\Memcached::OPT_COMPRESSION, true);

        $this->loaded = true;

        return $this->setSettings($settings);
    }

    /**
    * public function setSettings (array $settings)
    *
    * Set the execution settings
    */
    public function setSettings (array $settings)
    {
        return $this->settings = array_merge($this->settings, $settings);
    }

    /**
    * public function exists ($key)
    *
    * Check variable exists in memory
    *
    * return boolean
    */
    public function exists ($key)
    {
        if (empty($this->loaded) || $this->reload) {
            return false;
        }

        if ($this->server->add($key, null)) {
            $this->server->delete($key);

            return false;
        } else {
            return true;
        }
    }

    /**
    * public function set ($key, $value, [$expire integer])
    *
    * Set a variable into memory
    *
    * return mixed
    */
    public function set ($key, $value, $expire = 0)
    {
        if (empty($this->loaded)) {
            return false;
        }

        $this->server->set($key, $value, ($expire ?: $this->settings['expire']));

        return $value;
    }

    /**
    * public function get ($key)
    *
    * Get a variable from memory
    *
    * return mixed
    */
    public function get ($key)
    {
        if (empty($this->loaded)) {
            return false;
        }

        return $this->server->get($key);
    }

    /**
    * public function delete ($key)
    *
    * Delete a variable from memory
    *
    * return mixed
    */
    public function delete ($key)
    {
        if (empty($this->loaded)) {
            return false;
        }

        return $this->server->delete($key);
    }

    /**
    * public function clear (void)
    *
    * Clear APC cache
    *
    * return mixed
    */
    public function clear ()
    {
        if (empty($this->loaded)) {
            return false;
        }

        return $this->server->flush();
    }

    /**
    * public function expire ($key)
    *
    * Return the time to expire a key
    *
    * return mixed
    */
    public function expire ($key)
    {
        return false;
    }

    /**
    * public function reload (void)
    *
    * Allow to skip a cache read and store it again
    *
    * return mixed
    */
    public function reload ()
    {
        $this->reload = true;
    }
}
