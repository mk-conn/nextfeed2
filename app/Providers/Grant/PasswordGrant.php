<?php


namespace App\Providers\Grant;


use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\RequestEvent;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PasswordGrant
 *
 * This class exists just not to validate the client secret (which makes no sense, when a javascript client has to do so...)
 *
 * @package App\Providers\Grant
 */
class PasswordGrant extends \League\OAuth2\Server\Grant\PasswordGrant
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \League\OAuth2\Server\Entities\ClientEntityInterface
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     */
    protected function validateClient(ServerRequestInterface $request)
    {
        list($basicAuthUser, $basicAuthPassword) = $this->getBasicAuthCredentials($request);
        
        $validateClientSecret = false;
        $clientId = $this->getRequestParameter('client_id', $request, $basicAuthUser);
        if (is_null($clientId)) {
            throw OAuthServerException::invalidRequest('client_id');
        }
        
        // If the client is confidential require the client secret
        $clientSecret = $this->getRequestParameter('client_secret', $request, $basicAuthPassword);
        
        $client = $this->clientRepository->getClientEntity(
            $clientId,
            $this->getIdentifier(),
            $clientSecret,
            $validateClientSecret
        );
        
        if ($client instanceof ClientEntityInterface === false) {
            $this->getEmitter()
                 ->emit(new RequestEvent(RequestEvent::CLIENT_AUTHENTICATION_FAILED, $request));
            throw OAuthServerException::invalidClient();
        }
        
        $redirectUri = $this->getRequestParameter('redirect_uri', $request, null);
        
        if ($redirectUri !== null) {
            $this->validateRedirectUri($redirectUri, $client, $request);
        }
        
        return $client;
    }
    
    
}