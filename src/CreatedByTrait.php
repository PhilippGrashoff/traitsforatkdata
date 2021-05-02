<?php declare(strict_types=1);

namespace traitsforatkdata;

use Atk4\Data\Model;


trait CreatedByTrait
{

    protected function addCreatedByFieldAndHook(): void
    {
        // Adds created_date and created_by field to model
        $this->addField(
            'created_by',
            [
                'type' => 'string',
                'system' => true
            ],
        );

        $this->onHook(
            Model::HOOK_BEFORE_SAVE,
            function (self $model, $isUpdate) {
                if ($isUpdate) {
                    return;
                }

                if (
                    isset($this->app->auth->user)
                    && $this->app->auth->user->loaded()
                ) {
                    $model->set('created_by', $this->app->auth->user->get($this->app->auth->user->id_field));
                }
            }
        );
    }
}