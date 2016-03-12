<?php
namespace pmill\Plesk\MailService;

use pmill\Plesk\ApiRequestException;
use pmill\Plesk\BaseRequest;
use pmill\Plesk\Helper\MailPreferences;
use pmill\Plesk\HttpRequestContract;
use SimpleXMLElement;

class UpdatePreferences extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
    <mail>
        <set_prefs>
            <filter>
                  <site-id>{SITE-ID}</site-id>
             </filter>
             <prefs>
                 {PREFS}
             </prefs>
        </set_prefs>
     </mail>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'site-id' => null,
        'nonexistent-user' => null,
        'mailservice' => null,
    ];

    /**
     * @param array $config
     * @param array $params
     * @param HttpRequestContract|null $http
     */
    public function __construct(array $config, array $params = [], HttpRequestContract $http = null)
    {
        $helper = new MailPreferences();
        $params['prefs'] = $helper->generate($params);

        parent::__construct($config, $params, $http);
    }

    /**
     * @param SimpleXMLElement $xml
     * @return bool
     * @throws ApiRequestException
     */
    protected function processResponse($xml)
    {
        if ((string) $xml->{'mail'}->{'set_prefs'}->result->status === 'error') {
            throw new ApiRequestException($xml->{'mail'}->{'set_prefs'}->result);
        }

        return true;
    }
}
