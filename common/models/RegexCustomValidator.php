<?php

namespace common\models;

use Yii;

class RegexCustomValidator
{
    public $message_name;
    public $pattern_name;

    public $message_username;
    public $pattern_username;

    public $message_phone;
    public $pattern_phone;

    public $message_identification;
    public $pattern_identification;

    public $message_iban_code;
    public $pattern_iban_code;

    /**
     * RegexCustomValidator constructor.
     */
    public function __construct()
    {
        $this->message_name = Yii::t('backend','Este campo solo acepta caracteres alfanuméricos o los caracteres . : , ( ) _ / - @ # &');
        $this->pattern_name = '/^[A-Za-zÁÉÍÓÚÑÜüáéíóúñ0-9,|:|.|\/|\-|\(|\)|\s|@|#|&|_+]*$/';

        $this->message_username = Yii::t('backend','Este campo solo acepta caracteres alfanuméricos seguido de los caracteres . _ no consecutivos');
        $this->pattern_username = '/^[a-zA-Z0-9]+([_\.]?[a-zA-Z0-9])*$/';

        $this->message_identification = Yii::t('backend','Cédula Jurídica o Física no tiene un formato válido');
        $this->pattern_identification = '/^([1-9](\-|\s)\d{3}(\-|\s)\d{6})|(\d{1}(\-|\s)\d{4}(\-|\s)\d{4})$/';

        $this->message_phone = Yii::t('backend','Número de teléfono no tiene un formato válido');
        $this->pattern_phone = '/^(?:[\+]{1})?(?:\([0-9]{1,2}\) ?)?(?:[0-9] ?-?){6,14}[0-9]$/';

        $this->message_iban_code = Yii::t('backend','Número de cuenta no es código IBAN válido');
        $this->pattern_iban_code = '/^[A-Z]{2}[0-9]{20}$/';
    }

    /**
     * @return string
     */
    public function getMessageName()
    {
        return $this->message_name;
    }

    /**
     * @param string $message_name
     */
    public function setMessageName($message_name)
    {
        $this->message_name = $message_name;
    }

    /**
     * @return string
     */
    public function getPatternName()
    {
        return $this->pattern_name;
    }

    /**
     * @param string $pattern_name
     */
    public function setPatternName($pattern_name)
    {
        $this->pattern_name = $pattern_name;
    }

    /**
     * @return string
     */
    public function getMessageUsername()
    {
        return $this->message_username;
    }

    /**
     * @param string $message_username
     */
    public function setMessageUsername($message_username)
    {
        $this->message_username = $message_username;
    }

    /**
     * @return string
     */
    public function getPatternUsername()
    {
        return $this->pattern_username;
    }

    /**
     * @param string $pattern_username
     */
    public function setPatternUsername($pattern_username)
    {
        $this->pattern_username = $pattern_username;
    }

    /**
     * @return string
     */
    public function getMessagePhone()
    {
        return $this->message_phone;
    }

    /**
     * @param string $message_phone
     */
    public function setMessagePhone($message_phone)
    {
        $this->message_phone = $message_phone;
    }

    /**
     * @return string
     */
    public function getPatternPhone()
    {
        return $this->pattern_phone;
    }

    /**
     * @param string $pattern_phone
     */
    public function setPatternPhone($pattern_phone)
    {
        $this->pattern_phone = $pattern_phone;
    }

    /**
     * @return string
     */
    public function getMessageIdentification()
    {
        return $this->message_identification;
    }

    /**
     * @param string $message_identification
     */
    public function setMessageIdentification($message_identification)
    {
        $this->message_identification = $message_identification;
    }

    /**
     * @return string
     */
    public function getPatternIdentification()
    {
        return $this->pattern_identification;
    }

    /**
     * @param string $pattern_identification
     */
    public function setPatternIdentification($pattern_identification)
    {
        $this->pattern_identification = $pattern_identification;
    }

    /**
     * @return string
     */
    public function getMessageIbanCode()
    {
        return $this->message_iban_code;
    }

    /**
     * @param string $message_iban_code
     */
    public function setMessageIbanCode($message_iban_code)
    {
        $this->message_iban_code = $message_iban_code;
    }

    /**
     * @return string
     */
    public function getPatternIbanCode()
    {
        return $this->pattern_iban_code;
    }

    /**
     * @param string $pattern_iban_code
     */
    public function setPatternIbanCode($pattern_iban_code)
    {
        $this->pattern_iban_code = $pattern_iban_code;
    }



}