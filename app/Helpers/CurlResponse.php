<?php


namespace App\Helpers;


class CurlResponse
{
    /**
     * The body of the response without the headers block
     *
     * @var string
     **/
    public $body = '';

    /**
     * An associative array containing the response's headers
     *
     * @var array
     **/
    public $headers = array();

    /**
     * Accepts the result of a curl request as a string
     *
     * <code>
     * $response = new CurlResponse(curl_exec($curl_handle));
     * echo $response->body;
     * echo $response->headers['Status'];
     * </code>
     *
     * @param string $response
     **/
    function __construct($response) {
        # Headers regex
        $pattern = '#HTTP/\d\.\d.*?$.*?\r\n\r\n#ims';

        # Extract headers from response
        preg_match_all($pattern, $response, $matches);
        $headers_string = array_pop($matches[0]);
        $headers = explode("\r\n", str_replace("\r\n\r\n", '', $headers_string));

        # Remove headers from the response body
        $this->body = str_replace($headers_string, '', $response);

        # Extract the version and status from the first header
        $version_and_status = array_shift($headers);
        preg_match('#HTTP/(\d\.\d)\s(\d\d\d)\s(.*)#', $version_and_status, $matches);
        $this->headers['Http-Version'] = !isset($matches[1]) ? "null" : $matches[1];
        $this->headers['Status-Code'] = !isset($matches[2]) ? "null" : $matches[2];
        $this->headers['Status'] = !isset($matches[2]) || !isset($matches[3]) ? "null" : $matches[2].' '.$matches[3];

        # Convert headers into an associative array
        foreach ($headers as $header) {
            preg_match('#(.*?)\:\s(.*)#', $header, $matches);
            if(isset($matches)&&isset($matches[1])&&isset($matches[2])) {
                $this->headers[$matches[1]] = $matches[2];
            }
        }

    }

    /**
     * Returns the response body
     *
     * <code>
     * $curl = new Curl;
     * $response = $curl->get('google.com');
     * echo $response;  # => echo $response->body;
     * </code>
     *
     * @return string
     **/
    function __toString() {
        return $this->body;
    }
}