<?php

namespace Tests;

use dbizapps\Utilities\Timezone;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Event.
 */
class TimezoneTest extends TestCase
{

    public function testCreateTimezone()
    {
        $timezone = new Timezone;

        $this->assertTrue( count($timezone->all()) == 425 );
    }


    public function testCreateCustomTimezone()
    {
        $timezone = new Timezone([['code' => 'GH', 'country' => 'Ghana', 'zone'  => 'Africa/Accra', 'zone_offset' => 'Africa/Accra (+00:00)', 'utc_offset' => '+00:00', 'utc_offset_min' => '0', 'utc_dst_offset' => '+00:00', 'utc_dst_offset_min' => '0']]);

        $this->assertTrue( count($timezone->all()) == 1 );
    }


    public function testCreateInvalidCustomTimezone()
    {
        try {
            $timezone = new Timezone(['code' => 'GH', 'country' => 'Ghana', 'zone'  => 'Africa/Accra', 'zone_offset' => 'Africa/Accra (+00:00)', 'utc_offset' => '+00:00', 'utc_offset_min' => '0', 'utc_dst_offset' => '+00:00', 'utc_dst_offset_min' => '0']);
        }

        catch ( \Exception $e ) {
            $this->assertTrue( $e->getMessage() == 'Timezones are expected as array of arrays format.');
        }
    }


    public function testSearchTimezoneByCode()
    {
        $timezone = new Timezone;

        $search = $timezone->search('code', 'GM');

        $this->assertTrue( !is_null($search) );
        $this->assertTrue( $search['country'] == 'Gambia' );
    }

    public function testSearchTimezoneByDisplay()
    {
        $timezone = new Timezone;

        $search = $timezone->search('country', 'Gambia');

        $this->assertTrue( !is_null($search) );
        $this->assertTrue( $search['code'] == 'GM' );
    }


    public function testSearchTimezoneByCommon()
    {
        $timezone = new Timezone;

        $search = $timezone->search('country', 'Canada');

        $this->assertTrue( count($search) > 1 );
    }

}
