<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FarmerTest extends TestCase
{
    public function test(): void
    {
        $bunny = new \FarmGame\Model\Farmer();
        $bunny->();

        $this->assertEquals($bunny->getFeedingInterval()-1, $bunny->getAppetite());
    }

    public function testFeed(): void
    {
        $bunny = new \FarmGame\Model\Farmer();
        $bunny->();
        $bunny->feed();

        $this->assertEquals($bunny->getFeedingInterval(), $bunny->getAppetite());
    }
}
