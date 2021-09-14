<?php

namespace Tests;

use dbizapps\Utilities\Locale;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Event.
 */
class LocaleTest extends TestCase
{

    public function testCreateLocale()
    {
        $locale = new Locale;

        $this->assertTrue( count($locale->all()) == 722 );
    }


    public function testCreateCustomLocale()
    {
        $locale = new Locale([['code'=>'af','display'=>'Afrikaans','native'=>'Afrikaans','common'=>'']]);

        $this->assertTrue( count($locale->all()) == 1 );
    }


    public function testCreateInvalidCustomLocale()
    {
        try {
            $locale = new Locale(['code'=>'af','display'=>'Afrikaans','native'=>'Afrikaans','common'=>'']);
        }

        catch ( \Exception $e ) {
            $this->assertTrue( $e->getMessage() == 'Locales are expected as array of arrays format.');
        }
    }


    public function testSearchLocaleByCode()
    {
        $locale = new Locale;

        $search = $locale->search('code', 'ak');

        $this->assertTrue( !is_null($search) );
        $this->assertTrue( $search['display'] == 'Akan' );
    }

    public function testSearchLocaleByDisplay()
    {
        $locale = new Locale;

        $search = $locale->search('display', 'Akan');

        $this->assertTrue( !is_null($search) );
        $this->assertTrue( $search['code'] == 'ak' );
    }


    public function testSearchLocaleByCommon()
    {
        $locale = new Locale;

        $search = $locale->search('common', '');

        $this->assertTrue( count($search) > 1 );
    }

}
