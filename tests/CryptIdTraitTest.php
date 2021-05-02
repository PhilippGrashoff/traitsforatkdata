<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use traitsforatkdata\TestCase;
use Atk4\Data\Exception;
use Atk4\Data\Model;
use Atk4\Data\Persistence;
use atk4\schema\Migration;
use traitsforatkdata\CryptIdTrait;
use traitsforatkdata\tests\testclasses\ModelWithCryptIdTrait;


class CryptIdTraitTest extends TestCase
{

    protected $sqlitePersistenceModels = [ModelWithCryptIdTrait::class];

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
        $model = new ModelWithCryptIdTrait($this->getSqliteTestPersistence());
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
        $persistence = $this->getSqliteTestPersistence();
        $model = new ModelWithCryptIdTrait($persistence, ['createSameCryptId' => true]);
        $model->save();
        self::assertEquals('samestring', $model->get('crypt_id'));
        $model2 = new ModelWithCryptIdTrait($persistence, ['createSameCryptId' => true]);
        $model2->save();
        self::assertEquals('samestringabc', $model2->get('crypt_id'));
    }

    public function testFieldSetToReadOnlyIfCryptIdNotEmpty() {
        $model = new ModelWithCryptIdTrait($this->getSqliteTestPersistence());
        $model->save();
        $model->setCryptId('crypt_id');
        self::assertTrue($model->getField('crypt_id')->read_only);
    }
}
