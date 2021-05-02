<?php

declare(strict_types=1);

namespace traitsforatkdata;

use Atk4\Data\Exception;
use Atk4\Data\Model;

trait ExtraModelFunctionsTrait {

    protected function _exceptionIfThisNotLoaded(): void
    {
        if (!$this->loaded()) {
            throw new Exception(
                '$this needs to be loaded in ' . debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']
            );
        }
    }

    /**
     * makes sure that a hasOne reference is loaded, if not throws exception.
     * Workaround for https://github.com/atk4/data/issues/335
     */
    public function loadedHasOneRef(string $ref_name): Model
    {
        $model = $this->ref($ref_name);
        if (!$model->loaded()) {
            throw new Exception(
                'HasOne Reference Model ' . $ref_name . ' with id ' . $this->get($ref_name) . ' could not be loaded'
            );
        }

        return $model;
    }

    /**
     * pass multiple field names. If any is dirty it returns true
     */
    public function isAtLeastOneFieldDirty(array $fieldNames): bool
    {
        foreach ($fieldNames as $fieldName) {
            if ($this->isDirty($fieldName)) {
                return true;
            }
        }

        return false;
    }
}