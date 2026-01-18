<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pusher extends BaseConfig
{
    /**
     * Pusher App ID
     * Get this from your Pusher dashboard
     */
    public string $appId = '2103410';

    /**
     * Pusher Key
     * Get this from your Pusher dashboard
     */
    public string $key = '52dcea28c06314d045ff';

    /**
     * Pusher Secret
     * Get this from your Pusher dashboard
     */
    public string $secret = 'bbb4ce17e55729fbd2c4';

    /**
     * Pusher Cluster
     * e.g., 'ap1', 'eu', 'us2', etc.
     */
    public string $cluster = 'ap1';

    /**
     * Use TLS (recommended)
     */
    public bool $useTLS = true;
}
