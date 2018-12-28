<?php


namespace Tests\Feature;


use Illuminate\Http\Response;
use Tests\ApiRequest;

class UserResourceTest extends ApiRequest
{
    const RESOURCE_TYPE = 'users';
    
    public function testFilterMe()
    {
        $this->withUser();
        
        $response = $this->getJsonApi($this->apiUrl . '?filter[me]=true')
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();
        
    }
    
    
}