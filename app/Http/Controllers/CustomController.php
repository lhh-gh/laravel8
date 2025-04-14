<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: SillyCat
 * Date: 2025-04-14
 * Time: 18:25
 */

namespace App\Http\Controllers;

use Validator;

class CustomController extends Controller
{
    protected $_appVersion = '';
    protected $_deviceType = '';
    protected $_deviceName = '';
    protected $_sysVersion = '';

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $deviceAgent = $request->header('device-agent');
            if (!empty($deviceAgent)) {
                $deviceArr = explode("_", $deviceAgent);
                if (count($deviceArr) == 4) {
                    $this->_appVersion = $deviceArr[0];
                    $this->_deviceType = $deviceArr[1];
                    $request->merge(["deviceType" => $this->_deviceType]);
                    $this->_deviceName = $deviceArr[2];
                    $this->_sysVersion = $deviceArr[3];
                }
            }

            return $next($request);
        });
    }
}


