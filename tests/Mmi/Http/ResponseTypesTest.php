<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Mmi\Http;

class ResponseTypesTest extends \PHPUnit\Framework\TestCase
{

    public function testGetMessageByCode()
    {
        $this->assertEquals('OK', ResponseTypes::getMessageByCode('200'));
        $this->assertEquals('Not Found', ResponseTypes::getMessageByCode('404'));
        $this->assertEquals('Unauthorized', ResponseTypes::getMessageByCode('401'));
        $this->assertEquals('Internal Server Error', ResponseTypes::getMessageByCode('500'));
    }

    public function testGetExtensionByType()
    {
        $this->assertEquals('jpg', ResponseTypes::getExtensionByType('image/jpeg'));
        $this->assertEquals('png', ResponseTypes::getExtensionByType('image/png'));
        $this->assertEquals('html', ResponseTypes::getExtensionByType('text/html'));
        $this->assertEquals('txt', ResponseTypes::getExtensionByType('text/plain'));
        $this->assertEquals('json', ResponseTypes::getExtensionByType('application/json'));
        $this->assertEquals('js', ResponseTypes::getExtensionByType('application/x-javascript'));
    }

    /**
     * @expectedException \Mmi\Http\HttpException
     */
    public function testSearchType()
    {
        $this->assertEquals('image/jpeg', ResponseTypes::searchType('jpg'));
        $this->assertEquals('image/jpeg', ResponseTypes::searchType('jpeg'));
        $this->assertEquals('image/jpeg', ResponseTypes::searchType('image/jpeg'));
        $this->assertEquals('text/html', ResponseTypes::searchType('text/html'));
        $this->assertEquals('text/html', ResponseTypes::searchType('htm'));
        $this->assertEquals('text/html', ResponseTypes::searchType('html'));
        $this->assertEquals('image/png', ResponseTypes::searchType('png'));
        $this->assertEquals('image/png', ResponseTypes::searchType('image/png'));
        $this->assertEquals('application/x-javascript', ResponseTypes::searchType('js'));
        $this->assertEquals('application/x-javascript', ResponseTypes::searchType('application/x-javascript'));
        //błędny typ (wyjątek)
        ResponseTypes::searchType('hatemel');        
    }

}
