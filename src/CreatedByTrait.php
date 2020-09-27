<?php declare(strict_types=1);

namespace traitsforatkdata;

use atk4\data\Model;


trait CreatedByTrait {

    protected function addCreatedByFields(): void {
        // Adds created_date and created_by field to model
        $this->addFields(
            [
                [
                    'created_by',
                    'type' => 'string',
                    'system' => true
                ],
                [
                    'created_by_name',
                    'type' => 'string',
                    'system' => true
                ],
            ]
        );
    }

    protected function addCreatedByHook(string $fieldName = 'name'): void {
        $this->onHook(
            Model::HOOK_BEFORE_SAVE,
            function (Model $model, $isUpdate) use ($fieldName) {
                if ($isUpdate) {
                    return;
                }

                if(
                    isset($this->app->auth->user)
                    && $this->app->auth->user->loaded()
                ) {
                    $model->set('created_by', $this->app->auth->user->get($this->app->auth->user->id_field));
                    if($this->app->auth->user->hasField($fieldName)) {
                        $model->set('created_by_name', $this->app->auth->user->get($fieldName));
                    }
                }
            }
        );
    }
}