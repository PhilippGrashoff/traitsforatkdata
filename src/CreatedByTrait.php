<?php declare(strict_types=1);

namespace traitsforatkdata;

use Atk4\Data\Model;
trait CreatedByTrait
{
    protected function addCreatedByFieldAndHook(): void
    {
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
                    isset($this->persistence->getApp()->auth->user)
                    && $this->persistence->getApp()->auth->user->isLoaded()
                ) {
                    $model->set('created_by', $this->persistence->getApp()->auth->user->getId());
                }
            }
        );
    }
}