<?php

namespace Lia\Addons\JWTAuth\Providers\Storage;

interface StorageInterface
{
    /**
     * @param string $key
     * @param int $minutes
     * @return void
     */
    public function add($key, $value, $minutes);

    /**
     * @param string $key
     * @return bool
     */
    public function has($key);

    /**
     * @param string $key
     * @return bool
     */
    public function destroy($key);

    /**
     * @return void
     */
    public function flush();
}
