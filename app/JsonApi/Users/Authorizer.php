<?php

namespace App\JsonApi\Users;

use App\JsonApi\DefaultAuthorizer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class Authorizer extends DefaultAuthorizer
{
    
    /**
     * Authorize a resource index request.
     *
     * @param string  $type
     *      the domain record type.
     * @param Request $request
     *      the inbound request.
     *
     * @return void
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function index($type, $request)
    {
        $this->can('me', $type, $request);
    }
    
    /**
     * @param string                   $type
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function create($type, $request)
    {
        return $request->user() === null;
    }
    
    /**
     * Authorize a resource read request.
     *
     * @param object  $record
     *      the domain record.
     * @param Request $request
     *      the inbound request.
     *
     * @return void
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function read($record, $request)
    {
        $this->can('view', $record);
    }
    
    /**
     * Authorize a resource update request.
     *
     * @param object  $record
     *      the domain record.
     * @param Request $request
     *      the inbound request.
     *
     * @return void
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function update($record, $request)
    {
        $this->can('update', $record);
    }
    
    /**
     * Authorize a resource read request.
     *
     * @param object  $record
     *      the domain record.
     * @param Request $request
     *      the inbound request.
     *
     * @return void
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function delete($record, $request)
    {
        // TODO: Implement delete() method.
    }
    
}
