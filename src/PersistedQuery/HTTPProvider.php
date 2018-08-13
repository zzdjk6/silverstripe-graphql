<?php

namespace SilverStripe\GraphQL\PersistedQuery;

use SilverStripe\Core\Config\Configurable;

/**
 * Class HTTPProvider
 * @package SilverStripe\GraphQL\PersistedQuery
 */
class HTTPProvider implements PersistedQueryMappingProvider
{
    use Configurable;

    /**
     * Example:
     * <code>
     * SilverStripe\GraphQL\PersistedQuery\HTTPProvider:
     *   url_with_key:
     *     default: 'http://example.com/mapping.json'
     * </code>
     *
     * Note: The mapping supports multi-schema feature, you can have other schemaKey rather than 'default'
     *
     * @var array
     * @config
     */
    private static $url_with_key = [
        'default' => ''
    ];

    /**
     * return a map from <query> to <id>
     *
     * @param string $schemaKey
     * @return array
     */
    public function getMapping($schemaKey = 'default')
    {
        /** @noinspection PhpUndefinedFieldInspection */
        /** @noinspection StaticInvocationViaThisInspection */
        $urlWithKey = $this->config()->url_with_key;
        if (!isset($urlWithKey[$schemaKey])) {
            return [];
        }

        $url = trim($urlWithKey[$schemaKey]);

        // TODO: replace this with GuzzleHttp
        $contents = trim(file_get_contents($url));
        $result = json_decode($contents);
        if (!is_array($result)) {
            return [];
        }

        return $result;
    }

    /**
     * return a map from <id> to <query>
     *
     * @param string $schemaKey
     * @return array
     */
    public function getReversedMapping($schemaKey = 'default')
    {
        return array_reverse($this->getMapping($schemaKey));
    }
}