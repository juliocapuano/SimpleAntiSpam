<?php

namespace juliocapuano\SimpleAntiSpam;

use PHPUnit\Framework\TestCase;

class ValidateTextTest extends TestCase
{
    /**
     * @test
     * @dataProvider urlDetectionDataProvider
     */
    public function testIsUrlInTextScenarios($text, $expectedResult)
    {
        $this->assertSame($expectedResult, SimpleAntiSpam::isUrlInText($text));
    }

    public static function urlDetectionDataProvider()
    {
        return [
            // Existing cases
            'text without url' => ['Hi, I wanted to inquire about getting a residentia', false],
            'text with one url' => ['url in text http://google.com', true],

            // New cases
            'empty string' => ['', false],
            'text with multiple distinct urls' => ['Check http://example.com and https://another.org', true],
            'url at the beginning' => ['http://start.com is a website', true],
            'url at the end' => ['A website is https://end.net', true],
            'domain name without scheme' => ['Visit example.com for more info', false],
            'domain name with www without scheme' => ['Visit www.example.com for more info', false],
            'url with query parameters' => ['Go to http://example.com/page?id=123&user=test', true],
            'url with port' => ['Access http://example.com:8080/path', true],
            'https_url with query parameters' => ['Secure site https://example.com/search?query=php+stuff', true],
            'url with path and fragment' => ['Link http://example.com/docs#section-one', true],
            'text with only a url' => ['https://only-a-url.com', true],
            'text with mixed case scheme HTTP' => ['Visit HTTP://example.com', true],
            'text with mixed case scheme HTTPS' => ['Visit HTTPS://example.com', true],
            'text with numbers in domain' => ['Go to http://123example456.com', true],
            'text with hyphen in domain' => ['Check out http://my-example-site.com', true],
            'text with url containing uncommon tld' => ['Link to http://example.photo', true],
            'text with multiple urls together' => ['http://one.com http://two.com', true],
            'text with url and surrounding punctuation' => ['(http://example.com).', true],
            'text with url and no space before' => ['MyLink:http://example.com', true], // Current regex matches this
            'text with url and no space after' => ['http://example.com,is the link', true], // Current regex matches this
        ];
    }
}
