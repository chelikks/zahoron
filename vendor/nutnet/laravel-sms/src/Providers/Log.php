<?php
/**
 * @author Maksim Khodyrev<maximkou@gmail.com>
 * 18.05.17
 */

namespace Nutnet\LaravelSms\Providers;

use Psr\Log\LoggerInterface as Writer;
use Nutnet\LaravelSms\Contracts\Provider;

/**
 * Class Log
 * @package Nutnet\LaravelSms\Providers
 */
class Log implements Provider
{
    /**
     * @var Writer
     */
    private $logWriter;

    /**
     * Log constructor.
     * @param Writer $logWriter
     * @param array $options
     */
    public function __construct(Writer $logWriter, array $options = [])
    {
        $channels = isset($options['channels']) ? $options['channels'] : [];
        if (!is_array($channels)) {
            $channels = [$channels];
        }

        // support for logging messages into custom channels
        if (!empty($channels)) {
            if (!$this->isSupportsChannels($logWriter)) {
                throw new \DomainException(sprintf(
                    'Writer of type %s doesnt support channels.',
                    get_class($logWriter)
                ));
            }

            $logWriter = $this->makeStackedLogger($logWriter, $channels);
        }

        $this->logWriter = $logWriter;
    }

    /**
     * Send single sms
     * @param $phone
     * @param $text
     * @param $options
     * @return mixed
     */
    public function send($phone, $text, array $options = []) : bool
    {
        $this->getWriter()->debug(sprintf(
            'Sms is sent to %s: "%s"',
            $phone,
            $text
        ));

        return true;
    }

    /**
     * @param array $phones
     * @param $message
     * @param $options
     * @return bool
     */
    public function sendBatch(array $phones, $message, array $options = []) : bool
    {
        foreach ($phones as $phone) {
            $this->send($phone, $message);
        }

        return true;
    }

    /**
     * @return Writer
     */
    public function getWriter()
    {
        return $this->logWriter;
    }

    /**
     * @param Writer $logger
     * @return bool
     */
    private function isSupportsChannels(Writer $logger)
    {
        return method_exists($logger, 'channel') && method_exists($logger, 'stack');
    }

    /**
     * @param Writer $logWriter
     * @param array $channels
     * @return Writer
     */
    private function makeStackedLogger(Writer $logWriter, array $channels)
    {
        if (count($channels) > 1) {
            $logWriter = $logWriter->stack($channels);
        } else {
            $logWriter = $logWriter->channel(reset($channels));
        }

        return $logWriter;
    }
}
