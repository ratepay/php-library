<?php
/**
 * Copyright (c) Ratepay GmbH.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Service;

class DeviceFingerprint
{
    /**
     * @var string
     */
    protected $deviceIdentSId;

    public function __construct($deviceIdentSId)
    {
        $this->deviceIdentSId = $deviceIdentSId;
    }

    /**
     * Creates a DeviceIdentToken by microtime and given $identifier.
     *
     * @param $randomizer
     *
     * @return string
     */
    public static function createDeviceIdentToken($identifier)
    {
        $microtime = microtime();
        $deviceFingerprintToken = md5($identifier . '_' . $microtime);

        return $deviceFingerprintToken;
    }

    /**
     * Returns an HTML snippet which must be injected in the checkout page.
     *
     * @param string $deviceIdentToken - Device Ident Token which must be generated via the generateDeviceIdentToken() function
     *
     * @return string
     */
    public function getDeviceIdentSnippet($deviceIdentToken)
    {
        $params = [
            't' => $deviceIdentToken,
            'v' => $this->deviceIdentSId,
            'l' => 'Checkout',
        ];
        ob_start(); ?>
        <script type="text/javascript">var di=<?php echo json_encode($params); ?>;</script>
        <script type="text/javascript" src="//d.ratepay.com/<?php echo $this->deviceIdentSId; ?>/di.js"></script>
        <noscript>
            <link rel="stylesheet" type="text/css" href="//d.ratepay.com/di.css?<?php echo http_build_query($params); ?>" />
        </noscript>
        <?php
        return ob_get_clean();
    }
}
