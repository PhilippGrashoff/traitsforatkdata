<?php

declare(strict_types=1);

namespace traitsforatkdata\tests\testclasses;

use Atk4\Data\Model;
use traitsforatkdata\CryptIdTrait;

class ModelWithCryptIdTrait extends Model
{
    use CryptIdTrait;

    public $table = 'sometable';

    public $addition = '';

    protected $createSameCryptId = false;


    protected function init(): void
    {
        parent::init();
        $this->addField('crypt_id');
        $this->onHook(
            Model::HOOK_BEFORE_SAVE,
            function (self $model, $isUpdate) {
                $model->setCryptId('crypt_id');
            }
        );
    }

    protected function generateCryptId(): string
    {
        if ($this->createSameCryptId) {
            $return = 'samestring' . $this->addition;
            $this->addition = 'abc';

            return $return;
        } else {
            $return = '';
            for ($i = 1; $i <= 12; $i++) {
                $return .= $this->getRandomChar();
            }

            return $return;
        }
    }
}