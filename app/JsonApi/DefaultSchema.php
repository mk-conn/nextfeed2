<?php

namespace App\JsonApi;


use Carbon\Carbon;
use CloudCreativity\LaravelJsonApi\Utils\Str;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Neomerx\JsonApi\Contracts\Schema\SchemaFactoryInterface;
use Neomerx\JsonApi\Schema\SchemaProvider;
use RuntimeException;

/**
 * Class BaseSchema
 *
 * @package App\Api\JsonApi\v1
 */
class DefaultSchema extends SchemaProvider
{
    /**
     * @var array
     */
    protected $attributes = [];
    /**
     * @var bool
     */
    protected $hyphenated = true;

    /**
     * The date format to use.
     *
     * @var string
     */
    protected $dateFormat = Carbon::W3C;

    /**
     * @var array
     */
    protected $defaultAttributes = [
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    /**
     * BaseSchema constructor.
     *
     * @param SchemaFactoryInterface $factory
     */
    public function __construct(SchemaFactoryInterface $factory)
    {
        parent::__construct($factory);

        $this->attributes = array_merge((array) $this->attributes, $this->defaultAttributes);

    }

    /**
     * @param $resource
     *      the domain record being serialized.
     *
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }


    /**
     * @param $resource
     *      the domain record being serialized.
     *
     * @return array
     */
    public function getAttributes($resource)
    {
        $attributes = $this->attributes;

        return $this->getModelAttributes($resource);

//        if (property_exists($resource, 'created_at')) {
//            $attributes[ 'created-at' ] = $resource->created_at->toAtomString();
//        }
//        if (property_exists($resource, 'updated_at')) {
//            $attributes[ 'updated-at' ] = $resource->updated_at->toAtomString();
//        }

        return $attributes;
    }

    /**
     * Get attributes for the provided model.
     *
     * @param Model $model
     *
     * @return array
     */
    protected function getModelAttributes(Model $model)
    {
        $attributes = [];

        foreach ($this->attributeKeys($model) as $modelKey => $field) {
            if (is_numeric($modelKey)) {
                $modelKey = $field;
                $field = $this->fieldForAttribute($field);
            }

            $attributes[ $field ] = $this->extractAttribute($model, $modelKey, $field);
        }

        return $attributes;
    }

    /**
     * Get the attributes to serialize for the provided model.
     *
     * @param Model $model
     *
     * @return array
     */
    protected function attributeKeys(Model $model)
    {
        if (is_array($this->attributes)) {
            return $this->attributes;
        }

        return $model->getVisible();
    }

    /**
     * Convert a model key into a resource field name.
     *
     * @param $modelKey
     *
     * @return string
     */
    protected function fieldForAttribute($modelKey)
    {
        return $this->hyphenated ? Str::dasherize($modelKey) : $modelKey;
    }

    /**
     * @param Model $model
     * @param       $modelKey
     * @param       $field
     *
     * @return string
     */
    protected function extractAttribute(Model $model, $modelKey, $field)
    {
        $value = $model->{$modelKey};

        return $this->serializeAttribute($value, $model, $modelKey, $field);
    }

    /**
     * @param       $value
     * @param Model $model
     * @param       $modelKey
     * @param       $field
     *
     * @return string
     */
    protected function serializeAttribute($value, Model $model, $modelKey, $field)
    {
        $method = 'serialize' . Str::classify($field) . 'Field';

        if (method_exists($this, $method)) {
            return $this->{$method}($value, $model);
        }

        if ($value instanceof DateTime) {
            $value = $this->serializeDateTime($value, $model);
        }

        return $value;
    }

    /**
     * @param DateTime $value
     * @param Model    $model
     *
     * @return string
     */
    protected function serializeDateTime(DateTime $value, Model $model)
    {
        return $value->format($this->getDateFormat());
    }


    /**
     * @return string
     */
    protected function getDateFormat()
    {
        return $this->dateFormat ?: Carbon::W3C;
    }

    /**
     * Create a model identity using the model class and a provided id.
     *
     * @param                 $modelClass
     * @param string|int|null $id
     * @param string|null     $keyName
     *      the key to set as the id - defaults to `Model::getRouteKeyName()`
     *
     * @return Model|null
     */
    protected function createModelIdentity(
        $modelClass,
        $id,
        $keyName = null
    )
    {
        if (is_null($id)) {
            return null;
        }

        $model = new $modelClass();

        if (!$model instanceof Model) {
            throw new RuntimeException(sprintf('Expecting a model class, got %s.', $modelClass));
        }

        $model->setAttribute($keyName ?: $model->getRouteKeyName(), $id);

        return $model;
    }
}
