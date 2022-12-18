<?php

declare(strict_types=1);

namespace Mlevent\Ivd;

use Mlevent\Ivd\IvdException;

class Request
{
    /**
     * @var array response
     */
    protected array $response = [];

    /**
     * @var array headers
     */
    protected array $headers = [
        'Content-Type' => 'application/json',
        'User-Agent'   => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
    ];
    
    /**
     * request
     *
     * @param string     $url
     * @param array|null $parameters
     * @param boolean    $post
     */
    public function __construct(string $url, ?array $parameters = null, bool $post = true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
        $response = json_decode(curl_exec($ch), true);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$response || isset($response['error']) || !empty($response['data']['hata'])) {
            throw new IvdException('İstek başarısız oldu.', $parameters, $response, $httpcode);
        }
        $this->response = $response;
    }

    /**
     * get
     */
    public function get(?string $element = null)
    {
        return is_null($element) 
            ? $this->response
            : $this->response[$element];
    }

    /**
     * object
     */
    public function object(?string $element = null)
    {
        $response = json_decode(json_encode($this->response, JSON_FORCE_OBJECT), false);
        
        return is_null($element) 
            ? $response
            : $response->$element;
    }
}