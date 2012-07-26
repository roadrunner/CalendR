<?php
namespace CalendR\Test\Event;

use CalendR\Event\Manager;
use CalendR\Event\Event;
use CalendR\Period\Day;
use CalendR\Period\Month;
use CalendR\Event\Provider\Basic;

/**
 * Test class for Manager.
 * Generated by PHPUnit on 2012-01-20 at 19:25:21.
 */
class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Manager
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $basic1 = new Basic;
        $basic2 = new Basic;
        $this->object = new Manager(array('basic-1' => $basic1, 'basic-2' => $basic2));

        $basic1->add(new Event('event-1', new \DateTime('2012-01-01'), new \DateTime('2012-01-03')));
        $basic2->add(new Event('event-2', new \DateTime('2012-01-04'), new \DateTime('2012-01-05')));
    }

    public function testFind()
    {
        $this->assertSame(0, count($this->object->find(new Day(new \DateTime()))));
        $this->assertSame(1, count($this->object->find(new Day(new \DateTime('2012-01-01')))));
        $this->assertSame(1, count($this->object->find(new Day(new \DateTime('2012-01-04')))));

        $this->assertSame(2, count($this->object->find(new Month(new \DateTime('2012-01-01')))));

        $this->assertSame(1, count($this->object->find(
            new Month(new \DateTime('2012-01-01')),
            array('providers' => 'basic-1')
        )));
        $this->assertSame(1, count($this->object->find(
            new Month(new \DateTime('2012-01-01')),
            array('providers' => array('basic-2'))
        )));
        $this->assertSame(2, count($this->object->find(
            new Month(new \DateTime('2012-01-01')),
            array('providers' => array('basic-1', 'basic-2'))
        )));
        $this->assertSame(2, count($this->object->find(
            new Month(new \DateTime('2012-01-01')),
            array('providers' => array())
        )));
    }

    public function testCollectionInstatiator()
    {
        $this->assertInstanceOf(
            'CalendR\\Event\\Collection\\Basic',
            $this->object->find(new Month(new \DateTime('2012-01-01')))
        );

        $this->object->setCollectionInstantiator(function() {
            return new \CalendR\Event\Collection\Indexed;
        });

        $this->assertInstanceOf(
            'CalendR\\Event\\Collection\\Indexed',
            $this->object->find(new Month(new \DateTime('2012-01-01')))
        );
    }
}
