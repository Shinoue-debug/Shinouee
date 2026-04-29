<?php

/**
 * Vérifie qu'un champ n'est pas vide après nettoyage.
 *
 * @param string $valeur
 * @return bool
 */
function champ_requis(string $valeur): bool {
    return trim($valeur) !== '';
}

/**
 * Nettoie une valeur pour l'afficher sans risque dans du HTML.
 *
 * @param string $valeur
 * @return string
 */
function nettoyer(string $valeur): string {
    return htmlspecialchars(trim($valeur), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

