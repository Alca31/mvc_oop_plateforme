<?php
class DataHandling{
    // Méthode pour valider une chaîne de texte avec une longueur minimale et maximale
    public static function validateStringLength(string $input, int $minLength=0, int|NULL $maxLength=NULL): bool {
        $length = strlen($input);
        if ($maxLength !== NULL) {
           return ($length >= $minLength && $length <= $maxLength);
        }
        return ($length >= $minLength);
    }

    // Méthode pour valider un nombre flottant avec une valeur minimale
    public static function validateFloatMin(float $input, float $minValue): bool {
        return ($input >= $minValue);
    }

    // Méthode pour fournir une URL d'image par défaut si aucune n'est fournie
    public static function getDefaultImageUrl(?string $imageUrl, string $defaultImgUrl): string {
        return $imageUrl ?? $defaultImgUrl;
    }
}


?>