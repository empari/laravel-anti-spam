<?php
namespace Empari\Laravel\AntiSpam\Services;

use GuzzleHttp\Client as Guzzle;
use Empari\Laravel\AntiSpam\Exceptions\FailedToCheckSpamException;
use Empari\Laravel\AntiSpam\Exceptions\FailedToMarkAsHamException;
use Empari\Laravel\AntiSpam\Exceptions\FailedToMarkAsSpamException;
use Empari\Laravel\AntiSpam\Exceptions\InvalidApiKeyException;
use Illuminate\Support\Facades\Log;

class AkismetSpamService implements SpamServiceInterface
{
    protected $client;
    protected $endpoint = 'https://%s.rest.akismet.com/1.1/%s';

    public function __construct(Guzzle $client)
    {
        $this->client = $client;
        if ($this->checkApiKey() === false) {
            throw new InvalidApiKeyException();
        }
    }

    public function isSpam(array $parameters, array $additional = [])
    {
        $request = $this->makeRequest('comment-check', $this->mapParameters($parameters, $additional));
        $response = $request->getBody()->getContents();
        if (!in_array($response, ['true', 'false'])) {
            throw new FailedToCheckSpamException();
        }

        Log::info('Checked if email is spam', $parameters);
        return $response === 'true';
    }

    public function markAsSpam(array $parameters, array $additional = [])
    {
        $request = $this->makeRequest('submit-spam', $this->mapParameters($parameters, $additional));
        if ($request->getBody()->getContents() !== 'Thanks for making the web a better place.') {
            throw new FailedToMarkAsSpamException();
        }
        return true;
    }

    public function markAsHam(array $parameters, array $additional = [])
    {
        $request = $this->makeRequest('submit-ham', $this->mapParameters($parameters, $additional));
        if ($request->getBody()->getContents() !== 'Thanks for making the web a better place.') {
            throw new FailedToMarkAsHamException();
        }
        return true;
    }

    protected function checkApiKey()
    {
        $request = $this->makeRequest('verify-key', [
            'key' => config('services.akismet.secret'),
        ]);
        return $request->getBody()->getContents() === 'valid';
    }

    protected function mapParameters($parameters, $additional = [])
    {
        $parameterMap = config('services.akismet.parameter_map');
        $mappedParameters = array_map(function ($key, $value) use ($parameterMap) {
            if (isset($parameterMap[$key])) {
                return [$parameterMap[$key] => $value];
            }
        }, array_keys($parameters), $parameters);
        return array_merge(array_collapse($mappedParameters), $additional);
    }

    protected function makeRequest($type, $parameters)
    {
        return $this->client->request('POST', sprintf($this->endpoint, config('services.akismet.secret'), $type), [
            'form_params' => $this->mergeDefaultFormParams($parameters)
        ]);
    }

    protected function mergeDefaultFormParams($parameters) {
        return array_merge([
            'blog' => config('app.url'),
            'blog_lang' => app()->getLocale(),
            'user_ip' => request()->ip(),
        ], $parameters);
    }
}