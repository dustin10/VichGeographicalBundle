<?php

namespace Vich\GeographicalBundle\Tests\Listener;

class GeographicalListenerTest extends \PHPUnit_Framework_TestCase
{
    protected $geoEntity;
    
    protected function setUp()
    {
        $this->geoEntity = $this->getGeoEntity();
    }
    
    public function testPrePersist()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
    
    public function testPreUpdate()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
    
    public function testQueryCoordinates()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
    
    private function getGeoEntity()
    {
        return $this->getMock('Vich\GeographicalBundle\Tests\DummyGeoEntity');
    }
}