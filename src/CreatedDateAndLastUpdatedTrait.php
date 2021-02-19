<?php declare(strict_types=1);

namespace traitsforatkdata;

use atk4\data\Model;


trait CreatedDateAndLastUpdatedTrait
{

    protected function addCreatedDateAndLastUpdateFields()
    {
        // Adds created_date and created_by field to model
        $this->addFields(
            [
                [
                    'created_date',
                    'type' => 'datetime',
                    'persist_timezone' => 'Europe/Berlin',
                    'system' => true
                ],
                [
                    'last_updated',
                    'type' => 'datetime',
                    'persist_timezone' => 'Europe/Berlin',
                    'system' => true
                ],
            ]
        );
    }

    /**
     * Important that this is done in before update hook. Otherwise models are saved to persistence
     * even if there was no change to save.
     */
    protected function addCreatedDateAndLastUpdatedHook()
    {
        $this->onHook(
            Model::HOOK_BEFORE_INSERT,
            function (self $model, array &$data) {
                $data['created_date'] = new \DateTime();
                $model->set('created_date', $data['created_date']);
            }
        );
        $this->onHook(
            Model::HOOK_BEFORE_UPDATE,
            function (self $model, array &$data) {
                $data['last_updated'] = new \DateTime();
                $model->set('last_updated', $data['last_updated']);
            }
        );
    }
}