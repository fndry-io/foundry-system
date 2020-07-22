<?php

namespace Foundry\System\Testing;

use Foundry\Core\Testing\FoundryResponse;

abstract class TestCase extends \Foundry\Core\Testing\TestCase
{
    /**
     * Boot the system testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();

        if (isset($uses[SeedsFoundrySystem::class])) {
            $this->seedFoundrySystem();
        }

        return $uses;
    }

    /**
     * Overrides the base json call and wraps the response with a testable FoundryResponse object
     *
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param array $headers
     *
     * @return FoundryResponse
     */
    public function json($method, $uri, array $data = [], array $headers = [])
    {
        $headers = array_merge([
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept'           => 'application/json'
        ], $headers);
        return new FoundryResponse(parent::json($method, $uri, $data, $headers));
    }

}
