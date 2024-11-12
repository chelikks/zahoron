<?php
/**
 * @author Maksim Khodyrev<maximkou@gmail.com>
 * 17.07.17
 */

namespace Nutnet\LaravelSms\Contracts;

/**
 * Interface Provider
 * @package Nutnet\LaravelSms\Contracts
 */
interface Provider
{
    /**
     * @param $phone
     * @param $message
     * @param array $options
     * @return bool
     */
    public function send($phone, $message, array $options = []) : bool;

    /**
     * @param array $phones
     * @param $message
     * @param array $options
     * @return bool
     */
    public function sendBatch(array $phones, $message, array $options = []) : bool;
}
