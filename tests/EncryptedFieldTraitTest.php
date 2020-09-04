<?php declare(strict_types=1);

namespace traitsforatkdata\tests;


use atk4\ui\Persistence\Ui;
use atk4\core\AtkPhpunit\TestCase;
use traitsforatkdata\EncryptedFieldTrait;
use atk4\data\Persistence;
use atk4\data\Model;
use atk4\data\Exception;

class EncryptedFieldTraitTest extends TestCase {

    public function testFieldValueSameAfterLoading() {
        $model = $this->getTestModel();
        $model->set('value', 'Duggu');
        $model->save();
        $id = $model->get('id');
        $model->unload();
        $model->load($id);
        self::assertEquals($model->get('value'), 'Duggu');
    }

    /**
     * hack: set value with crypted class, load with uncrypted class
     */
    public function testValueStoredEncrypted() {
        $persistence = new Persistence\Array_();
        $model = $this->getTestModel($persistence);
        $model->set('value', 'Duggu');
        $model->save();

        $model2 = $this->getTestModelWithoutEncryptedValueField($persistence);
        $model2->load($model->get('id'));
        self::assertNotEquals($model2->get('value'), 'Duggu');
        self::assertTrue(strlen($model2->get('value')) > 50);
    }

    public function testPersistenceUIReturnsValue() {
        $model = $this->getTestModel();
        $model->set('value', 'Duggu');
        $model->save();

        $ui = new Ui();
        $res = $ui->typecastSaveField($model->getField('value'), $model->get('value'));
        self::assertEquals('Duggu', $res);
    }

    public function testExceptionOnDecryptFail() {
        $persistence = new Persistence\Array_();
        $model = $this->getTestModelWithoutEncryptedValueField($persistence);
        $model->set('value', 'Duggu');
        $model->save();

        $model2 = $this->getTestModel($persistence);
        self::expectException(Exception::class);
        $model2->load($model->get('id'));
    }

    public function testExceptionOnDecryptFailTwo() {
        $persistence = new Persistence\Array_();
        $model = $this->getTestModel($persistence);
        $model->set('value', 'Duggu');
        $model->save();

        $model2 = $this->getTestModelWithoutEncryptedValueField($persistence);
        $model2->load($model->get('id'));
        $v = $model2->get('value');
        $v[0] = 'a';
        $model2->set('value', $v);
        $model2->save();

        self::expectException(Exception::class);
        $model->reload();
    }

    protected function getTestModel(Persistence $persistence = null): Model {
        $modelClass = new class() extends Model {

            use EncryptedFieldTrait;

            public function init(): void {
                parent::init();
                $this->addField('value');
                $this->encryptField($this->getField('value'), '1234567890abcefd1234567890abcefd');
            }
        };

        return new $modelClass($persistence ? : new Persistence\Array_());
    }

    protected function getTestModelWithoutEncryptedValueField(Persistence $persistence = null): Model {
        $modelClass = new class() extends Model {

            use EncryptedFieldTrait;

            public function init(): void {
                parent::init();
                $this->addField('value');
            }
        };

        return new $modelClass($persistence ? : new Persistence\Array_());
    }
}
