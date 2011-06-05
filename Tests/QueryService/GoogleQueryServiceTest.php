<?php

namespace Vich\GeographicalBundle\Tests\QueryService;

class GoogleQueryServiceTest extends \PHPUnit_Framework_TestCase
{
    private $googleQueryService;
    
    public function setUp()
    {
        $this->googleQueryService = $this->getGoogleQueryService();
    }
    
    public function testQueryForCoordinates()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
    
    private function getGoogleQueryService()
    {
        return $this->getMock('Vich\GeographicalBundle\QueryService\GoogleQueryService');
    }
}