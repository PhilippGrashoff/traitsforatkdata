<?php

declare(strict_types=1);

namespace traitsforatkdata;

use Atk4\Data\Exception;

/**
 * Sometimes it makes sense to store all records of a model once for the whole request to avoid a lot of DB requests.
 * This is the case with models that are hardly ever changed but often accessed.
 * Keep in mind that creating/updating/deleting a model record during a request will render the cached data invalid!
 */
trait CachedModelTrait
{

    protected $cachedModels = [];

    public function getCachedModel(string $modelName): array
    {
        if (!class_exists($modelName)) {
            throw new Exception('Class ' . $modelName . ' does not exist in ' . __FUNCTION__);
        }

        //if isset already, return that
        if (isset($this->cachedModels[$modelName])) {
            return $this->cachedModels[$modelName];
        }

        //App usually carries db property, while models use persistence
        $model = new $modelName(isset($this->db) ? $this->db : $this->persistence);
        $a = [];
        foreach ($model as $record) {
            $a[$record->get($record->id_field)] = clone $record;
        }

        $this->cachedModels[$modelName] = $a;
        return $this->cachedModels[$modelName];
    }

    public function unsetCachedModel(string $modelName): void
    {
        if (array_key_exists($modelName, $this->cachedModels)) {
            unset($this->cachedModels[$modelName]);
        }
    }
}