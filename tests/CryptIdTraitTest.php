<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use atk4\core\AtkPhpunit\TestCase;
use atk4\data\Exception;
use atk4\data\Model;
use atk4\data\Persistence;
use atk4\schema\Migration;
use traitsforatkdata\CryptIdTrait;


class CryptIdTraitTest extends TestCase
{

    public function testExceptionOverwriteGenerate()
    {
        $modelClass = new class() extends Model {
            use CryptIdTrait;
            public $table = 'sometable';

        };
        $model = new $modelClass(new Persistence\Array_());
        self::expectException(Exception::class);
        $this->callProtected($model, 'generateCryptId');
    }

    public function testsetCryptId()
    {
        $model = $this->getTestModel();
        $model->setCryptId('crypt_id');
        self::assertSame(
            12,
            strlen($model->get('crypt_id'))
        );
    }

    /**
     * test if cryptId is recalculated if existing one is found. Wrote stupid
     * test model for that :)
     */
    public function testCryptIdRegeneratedIfSameCryptIdAlreadyExists()
    {
        //use SQL Persistence here as it supports conditions
        $persistence = Persistence::connect('sqlite::memory:');
        $model = $this->getTestModelSameCryptId($persistence);
        Migration::of($model)->drop()->create();
        $model->save();
        self::assertEquals('samestring', $model->get('crypt_id'));
        $model2 = $this->getTestModelSameCryptId($persistence);
        $model2->save();
        self::assertEquals('samestringabc', $model2->get('crypt_id'));
    }

    public function testFieldSetToReadOnlyIfCryptIdNotEmpty() {

    }


    /**
     *
     */
    protected function getTestModel() {
        $modelClass = new class() extends Model {

            use CryptIdTrait;

            public $table = 'sometable';

            public $addition = 'abc';

            public function init(): void
            {
                parent::init();
                $this->addField('crypt_id');
            }

            protected function generateCryptId(): string
            {
                $return = '';
                for($i = 1; $i <= 12; $i ++) {
                    $return .= $this->getRandomChar();
                }

                return $return;
            }
        };

        return new $modelClass(new Persistence\Array_());
    }


    /**
     *
     */
    protected function getTestModelSameCryptId(Persistence $persistence) {
        $modelClass = new class() extends Model {

            use CryptIdTrait;

            public $table = 'sometable';

            public $addition = '';

            public function init(): void
            {
                parent::init();
                $this->addField('crypt_id');
                $this->onHook(
                    Model::HOOK_BEFORE_SAVE,
                    function ($model, $isUpdate) {
                        $this->setCryptId('crypt_id');
                    }
                );
            }

            protected function generateCryptId(): string
            {
                $return = 'samestring' . $this->addition;
                $this->addition = 'abc';

                return $return;
            }
        };

        return new $modelClass($persistence);
    }
}
