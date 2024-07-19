<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Auth extends BaseConfig
{
    // Specify the login method: 'username', 'email', or 'both'
    public $loginMethod = 'both';
}
