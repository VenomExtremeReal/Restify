<?php
class I18n {
    private static $language = 'pt';
    private static $translations = [];
    
    public static function init($lang = 'pt') {
        self::$language = $lang;
        self::loadTranslations();
    }
    
    private static function loadTranslations() {
        $file = __DIR__ . "/../lang/" . self::$language . ".php";
        if (file_exists($file)) {
            self::$translations = include $file;
        }
    }
    
    public static function t($key, $params = []) {
        $text = self::$translations[$key] ?? $key;
        foreach ($params as $k => $v) {
            $text = str_replace('{' . $k . '}', $v, $text);
        }
        return $text;
    }
    
    public static function setLanguage($lang) {
        self::$language = $lang;
        self::loadTranslations();
    }
}

function t($key, $params = []) {
    return I18n::t($key, $params);
}
?>