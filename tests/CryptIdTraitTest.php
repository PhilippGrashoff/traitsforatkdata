<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use atk4\core\AtkPhpunit\TestCase;
use atk4\data\Exception;
use atk4\data\Model;
use atk4\data\Persistence\Array_;
use traitsforatkdata\CryptIdTrait;


class CryptIdTraitTest extends TestCase
{

    public function testExceptionOverwriteGenerate()
    {
        $model = $this->getTestModel();
        $this->expectException(Exception::class);
        $this->callProtected($model, '_generateCryptId');
    }

    public function testsetCryptId()
    {
        $model = $this->getTestModel();
        $model->setCryptId('crypt_id');
        $this->assertEquals(strlen($model->get('crypt_id')), 64);
    }

    /**
     * test if cryptId is recalculated if existing one is found. Wrote stupid
     * test model for that :)
     */
    public function testCryptIdRegeneratedIfSameCryptIdAlreadyExists()
    {
        $t = new CryptIdSecondaryModel(self::$app->db);
        $t->setCryptId('value');
        $t->save();
        $this->assertEquals('a', $t->get('value'));

        $t2 = new CryptIdSecondaryModel(self::$app->db);
        $t2->useA = false;
        $t2->setCryptId('value');
        $this->assertNotEquals('a', $t2->get('value'));
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

        return new $modelClass(new Array_());
    }
}
