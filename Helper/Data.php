<?php
/**
 * Data
 *
 * @copyright Copyright © 2018 PY Yick. All rights reserved.
 * @author    py.yick@cleargo.com
 */

namespace Cleargo\Integrationframeworks\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function mapBrandCodeSapDivisionCode($brand_code){
        $map = [
            "" => "",
            "BIO" => "32",
            "GAB" => "3A",
            "GRN" => "17",
            "HR" => "33",
            "KIE" => "38",
            "LRP" => "41",
            "LAN" => "31",
            "LOP" => "17",
            "MTX" => "22",
            "MBL" => "17",
            "SHU" => "34",
            "SKC" => "42",
            "STL" => "3H",
            "VIC" => "40",
            "YSL" => "3E",
            "ZEG" => "3I",
            "GAX" => "3A",
            "GAY" => "3A",
            "GRX" => "17",
            "KIX" => "38",
            "LOZ" => "17",
            "LOY" => "17",
            "LOX" => "17",
            "LOW" => "17",
            "LOV" => "17",
            "LPX" => "20",
            "MBX" => "17",
            "RLX" => "37",
            "SLM" => "3H",
            "LPP" => "20",
            "HER" => "33",
            "SUA" => "34",
            "SKS" => "42",
            "DSL" => "3B",
            "KER" => "21",
            "MMM" => "3K",
            "VRF" => "3D",
            "PLY" => "20",
            "CLA" => "3L",
            "UD" => "3P",
            "AC" => "3Q",
            "R&G" => "3F",
            "NYXPMU" => "17",
        ];
        return $map[$brand_code];
    }
}