<?php
// Setting Model - Gestion des paramètres du système
class Setting extends Model {
    protected $table = 'settings';
    
    // Obtenir une valeur de paramètre par clé
    public static function get($key, $default = null) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['setting_value'] : $default;
    }
    
    // Définir une valeur de paramètre
    public static function set($key, $value) {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        return $stmt->execute([$key, $value, $value]);
    }
    
    // Obtenir le pourcentage de réduction
    public static function getReductionPourcentage() {
        return floatval(self::get('reduction_pourcentage', 20));
    }
    
    // Définir le pourcentage de réduction
    public static function setReductionPourcentage($value) {
        return self::set('reduction_pourcentage', $value);
    }
}
