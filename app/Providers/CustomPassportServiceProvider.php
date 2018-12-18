<?php

namespace App\Providers;

use App\Providers\Grant\PasswordGrant;
use Laravel\Passport\Bridge;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider;

/**
 * Class CustomPassportServiceProvider
 *
 * This class just exists to make a custom PasswortGrant
 *
 * @package App\Providers
 */
class CustomPassportServiceProvider extends PassportServiceProvider
{
    
    /**
     * Create and configure a Password grant instance.
     *
     * @return \League\OAuth2\Server\Grant\PasswordGrant
     */
    protected function makePasswordGrant()
    {
        $grant = new PasswordGrant(
            $this->app->make(Bridge\UserRepository::class),
            $this->app->make(Bridge\RefreshTokenRepository::class)
        );
        
        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());
        
        return $grant;
    }
    
}
