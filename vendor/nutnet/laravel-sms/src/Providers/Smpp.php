<?php
/**
 * @author Maksim Khodyrev<maximkou@gmail.com>
 * 17.07.17
 */

namespace Nutnet\LaravelSms\Providers;

use Nutnet\LaravelSms\Contracts\Provider;
use Nutnet\LaravelSms\Providers\Smpp\SmppSender;

/**
 * Class Smpp
 * @package App\Services\Sms\Bridges\Smpp
 */
class Smpp implements Provider
{
    /**
     * @var SmppSender
     */
    private $smpp;

    /**
     * @var array
     */
    private $options;

    /**
     * SmppBridge constructor.
     * @param SmppSender $smppService
     * @param array $options
     */
    public function __construct(SmppSender $smppService, array $options = [])
    {
        $this->smpp = $smppService;
        $this->options = $options;
    }

    /**
     * @inheritdoc
     */
    public function send($phone, $text, array $options = []) : bool
    {
        return $this->smpp->sendOne($phone, $text);
    }

    /**
     * @inheritdoc
     */
    public function sendBatch(array $phones, $message, array $options = []) : bool
    {
        $this->smpp->sendBulk($phones, $message);

        return true;
    }
}
