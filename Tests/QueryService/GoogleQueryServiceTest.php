<?php

namespace Vich\GeographicalBundle\Tests\QueryService;

class GoogleQueryServiceTest extends \PHPUnit_Framework_TestCase
{
    private $googleQueryService;
    
    public function setUp()
    {
        $this->googleQueryService = $this->getMockGoogleQueryService();
    }
    
    public function testQueryForCoordinates()
    {
        
    }
    
    private function getMockGoogleQueryService()
    {
        return $this->getMock('Vich\GeographicalBundle\QueryService\GoogleQueryService');
    }
}