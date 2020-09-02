<?php declare(strict_types=1);

namespace PMRAtk\tests\phpunit\Data\Traits;


use PMRAtk\Data\Traits\UniqueFieldTrait;
use PMRAtk\tests\phpunit\Data\BaseModelA;
use PMRAtk\tests\phpunit\TestCase;

/**
 *
 */
class Test extends BaseModelA {
    use UniqueFieldTrait;
}


/**
 *
 */
class UniqueFieldTraitTest extends TestCase {

    /*
     *
     */
    public function testExceptionOnEmptyValue() {
        $t = new Test(self::$app->db);
        $this->expectException(\atk4\data\Exception::class);
        $t->isFieldUnique('name');
    }


    /*
     * see if true/false is returned correctly
     */
    public function testReturn() {
        $t = new Test(self::$app->db);
        $t->set('name', 'ABC');
        $this->assertTrue($t->isFieldUnique('name'));
        $t->save();

        $t2 = $t->newInstance();
        $t2->set('name', 'DEF');
        $this->assertTrue($t2->isFieldUnique('name'));
        $t2->set('name', 'ABC');
        $this->assertFalse($t2->isFieldUnique('name'));

    }

}
