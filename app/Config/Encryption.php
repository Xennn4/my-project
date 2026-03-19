<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Encryption extends BaseConfig
{
    public string $driver = 'OpenSSL';
    public string $key = ''; // Leave empty if using .env
    public string $digest = 'SHA512';
}