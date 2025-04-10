<?php

namespace App\Modules\Core\Services;

use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;


abstract class Service
{
    protected $model;
    protected $errors;
    protected $rules;
    protected $fields;


    public function __construct($model)
    {
        $this->model = $model;
        $this->errors = new MessageBag();
    }


    public function validate($data, $ruleKey): void
    {
        $this->errors = new MessageBag();
        $validator = Validator::make($data, $this->rules[$ruleKey]);
        if ($validator->fails()) {
            $this->errors = $validator->errors();
        }
    }


    public function getErrors(): MessageBag
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return $this->errors->isNotEmpty();
    }
}
