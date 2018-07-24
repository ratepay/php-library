<?php
namespace RatePAY\Service;

class DeviceFingerprint {

    /**
     * @var string
     */
    protected $deviceIdentSId;

    public function __construct($deviceIdentSId) {
        $this->deviceIdentSId = $deviceIdentSId;
    }

    /**
     * Creates a DeviceIdentToken by microtime and given $identifier
     * @param $randomizer
     * @return string
     */
    public static function createDeviceIdentToken($identifier) {
        $microtime = microtime();
        $deviceFingerprintToken = md5($identifier . "_" . $microtime);
        return $deviceFingerprintToken;
    }

    /**
     * Returns an HTML snippet which must be injected in the checkout page
     * @param string $deviceIdentToken - Device Ident Token which must be generated via the generateDeviceIdentToken() function
     * @return string
     */
    public function getDeviceIdentSnippet($deviceIdentToken) {
        $snippet   = sprintf(
            '<script language="JavaScript">var di = %s;</script>',
            json_encode([
                't' => $deviceIdentToken,
                'v' => $this->deviceIdentSId,
                'l' => "Checkout"
            ])
        );

        $snippet .= sprintf(
            '<script type=\"text/javascript\" src=\"//d.ratepay.com/%1$s/di.js\"></script>
             <noscript><link rel=\"stylesheet\" type=\"text/css\" href=\"//d.ratepay.com/di.css?t=%2$t&v=%1$s&l=Checkout\"></noscript>',
            $this->deviceIdentSId,
            $deviceIdentToken
        );

        return $snippet;
    }

}