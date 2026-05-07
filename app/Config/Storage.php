<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Storage extends BaseConfig
{
    /**
     * Constructor
     * 
     * Load configuration from environment variables
     */
    public function __construct()
    {
        parent::__construct();

        $this->accessKey = env('storage.accessKey', '');
        $this->secretKey = env('storage.secretKey', '');
        $this->appName = env('storage.appName', '');
        $this->bucketName = env('storage.bucketName', '');
    }

    /**
     * Use SSL
     * 
     * Run this over HTTP or HTTPS. HTTPS (SSL) is more secure but can cause problems
     * on incorrectly configured servers.
     * 
     * @var bool
     */
    public $useSsl = true;

    /**
     * Verify Peer
     * 
     * Enable verification of the HTTPS (SSL) certificate against the local CA
     * certificate store.
     * 
     * @var bool
     */
    public $verifyPeer = false;

    /**
     * Access Key
     * 
     * Your Access Key.
     * 
     * @var string
     */
    public $accessKey = '';

    /**
     * Secret Key
     * 
     * Your Secret Key.
     * 
     * @var string
     */
    public $secretKey = '';

    /**
     * App Name
     * 
     * Your App Name.
     * 
     * @var string
     */
    public $appName = '';

    /**
     * Bucket Name
     * 
     * Your Bucket Name (sebelumnya Tray Name).
     * 
     * @var string
     */
    public $bucketName = '';

    /**
     * Storage API Base URL
     * 
     * Base URL for UINSI Storage API endpoint.
     * Default: https://storage.uinsi.ac.id/v1
     * 
     * @var string
     */
    public $storageUrl = 'https://storage.uinsi.ac.id/v1';

    /**
     * Max Object Size
     * 
     * Max size in Bytes the default is 10 MB.
     * Use these to calculate: https://whatsabyte.com/
     * 
     * @var int
     */
    public $maxObjectSize = 10485760; // 10 MB

    /**
     * Allowed Object Extensions
     * 
     * List all your needed object extensions in here.
     * Default: ['jpg','jpeg','png','pdf']
     * 
     * @var array
     */
    public $allowedExt = ['jpg', 'jpeg', 'png', 'pdf'];

    /**
     * Expires Time
     * 
     * Set expires time for private object via URL.
     * Default: 28800 (8 hours)
     * 
     * @var int
     */
    public $expires = 10800; // 3 hours
}
