<?php


namespace {
    //doc root ядра битрикса
    $docRoot = getenv('TEST_BX_ROOT');
    if (empty($docRoot)) {
        $docRoot = dirname(__DIR__, 4);
    }

    $_SERVER['DOCUMENT_ROOT'] = $docRoot;
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/lib/loader.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/compatibility.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/lib/exception.php');
}

namespace Bitrix\Main\Localization {
    class EO_Culture
    {
    }

    final class Loc
    {
        public static function loadMessages($filePath) {
        }

        public static function getMessage($code, $replace = null, $language = null) {
            return $code;
        }
    }
}

namespace {

    use Bitrix\Main\Application;
    use Bitrix\Main\Context;
    use Bitrix\Main\Context\Culture;

    class AppStub extends Application
    {
        protected function initializeContext(array $params) {
            $culture = new class() extends Culture {
                public function getDateTimeFormat() {
                    return "DD.MM.YYYY HH:MI:SS";
                }

                public function getDateFormat() {
                    return "YYYY-MM-DD";
                }

                public function getNameFormat() {
                    return "#NAME# #LAST_NAME#";
                }
            };

            $this->context = new Context($this);
            $this->context->setCulture($culture);
        }

        public function start() {
        }
    }

    class CTempFile
    {
        public static function GetFileName(string $fileName = '') {
            return __DIR__ . '/' . ltrim($fileName, '/');
        }
    }

    AppStub::getInstance()->initializeExtendedKernel(array());
}