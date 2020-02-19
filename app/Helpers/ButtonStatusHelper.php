<?php

namespace App\Helpers;


class ButtonStatusHelper {


    static function getButtonStatusText($status)
    {

        switch ($status) {
            case 0:
                $buttonText = 'Waiting for payment';
                break;
            case 1:
                $buttonText = 'Deposit paid';
                break;
            case 2:
                $buttonText = 'Full price paid';
                break;
            case 3:
                $buttonText = 'Cancelled';
                break;
            default:
                $buttonText = 'Waiting for payment';
                break;
        }

        return $buttonText;

    }


    static function getButtonStatusClass($status)
    {
        switch ($status) {
            case 0:
                $className = 'info';
                break;
            case 1:
                $className = 'warning';
                break;
            case 2:
                $className = 'success';
                break;
            case 3:
                $className = 'danger';
                break;
            default:
                $className = 'info';
                break;
        }

        return $className;
    }


}
