<?php

namespace App\JsonApi\Feeds;


use App\JsonApi\DefaultValidator;
use App\Models\Feed;
use CloudCreativity\JsonApi\Contracts\Validators\RelationshipsValidatorInterface;

class Validators extends DefaultValidator
{

    protected $allowedSortParameters = ['order', 'name'];

    /**
     * @var string
     */
    protected $resourceType = 'feeds';

    /**
     * @param Feed $record
     *
     * @return array
     */
    protected function rules($record = null)
    : array
    {
        $rules = [];

        $required = $record ? 'sometimes|required' : 'required';

        if ($record) {
            $rules = [
                'feed-url'  => $required . '|url',
                'folder_id' => 'exists:folders,id'
            ];
        }

        return $rules;
    }
}
