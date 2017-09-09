<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\JsonApi;


use App\Models\User;
use CloudCreativity\JsonApi\Contracts\Authorizer\AuthorizerInterface;
use CloudCreativity\JsonApi\Document\Error;
use CloudCreativity\JsonApi\Exceptions\MutableErrorCollection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseAuthorizer
 *
 * @package App\JsonApi
 */
abstract class BaseAuthorizer implements AuthorizerInterface
{
    /**
     * @var MutableErrorCollection
     */
    protected $errors = null;

    public function __construct()
    {
        $this->errors = new MutableErrorCollection();
    }

    /**
     * @return User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected function currentUser()
    {
        return Auth::user();
    }

    /**
     * @param string|null $title
     * @param string|null $msg
     *
     * @return bool
     */
    public function forbidden(string $title = null, string $msg = null)
    {
        $this->addError(new Error(null, null, null, Response::HTTP_FORBIDDEN, $title, $msg));

        return false;
    }

    /**
     * @return MutableErrorCollection
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param Error $error
     */
    public function addError(Error $error)
    {
        $this->errors->add($error);
    }
}
