<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use Atk4\Data\Model;
use Atk4\Data\Persistence;
use Atk4\Ui\Persistence\Ui;
use traitsforatkdata\GermanMoneyFormatFieldTrait;
use traitsforatkdata\TestCase;

class GermanMoneyFormatFieldTraitTest extends TestCase
{

    public function testLoadValueToUI()
    {
        $a = [];
        $gmf = $this->getTestModel();
        $gmf->set('money_test', '25.25');
        $gmf->save();

        $pui = new UI();
        $pui->currency = null;
        $res = $pui->typecastSaveField($gmf->getField('money_test'), 25.25);
        self::assertEquals(25.25, $res);
    }

    public function testSaveValueFromUI()
    {
        $a = [];
        $gmf = $this->getTestModel();
        $gmf->set('money_test', '25.25');
        $gmf->save();

        $pui = new UI();
        $res = $pui->typecastLoadField($gmf->getField('money_test'), '25,25');
        self::assertEquals(25.25, $res);
        $res = $pui->typecastLoadField($gmf->getField('money_test'), 25.25);
        self::assertEquals(25.25, $res);
        $res = $pui->typecastLoadField($gmf->getField('money_test'), '025.25');
        self::assertEquals(25.25, $res);
        $res = $pui->typecastLoadField($gmf->getField('money_test'), '025,2');
        self::assertEquals(25.20, $res);
        $res = $pui->typecastLoadField($gmf->getField('money_test'), '25');
        self::assertEquals(25.00, $res);
    }

    protected function getTestModel(): Model
    {
        $modelClass = new class() extends Model {

            use GermanMoneyFormatFieldTrait;

            public $table = 'gmf';

            protected function init(): void
            {
                parent::init();
                $this->addField(
                    'money_test',
                    ['type' => 'money']
                );
                $this->germanPriceForMoneyField($this->getField('money_test'));
            }
        };

        return new $modelClass(new Persistence\Array_());
    }
}