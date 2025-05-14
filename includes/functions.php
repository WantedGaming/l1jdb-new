<?php
// Function to sanitize input data
function sanitizeInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

// Function to get categories for navigation
function getCategories() {
    return [
        ['id' => 'pages/weapon/weapon', 'name' => 'Weapons', 'icon' => 'weapon.png', 'sql' => ['weapon.sql', 'weapon_skill.sql', 'weapon_skill_model.sql', 'weapons_skill_spell_def.sql']],
        ['id' => 'pages/armor/armor', 'name' => 'Armor', 'icon' => 'armor.png', 'sql' => ['armor.sql', 'armor_set.sql']],
        ['id' => 'pages/items/items', 'name' => 'Items', 'icon' => 'item.png', 'sql' => ['etcitem.sql']],
        ['id' => 'pages/monsters/monsters', 'name' => 'Monsters', 'icon' => 'monster.png', 'sql' => ['npc.sql', 'mobskill.sql', 'mobgroup.sql']],
        ['id' => 'pages/maps/maps', 'name' => 'Maps', 'icon' => 'map.png', 'sql' => ['mapids.sql']],
        ['id' => 'pages/dolls/dolls', 'name' => 'Dolls', 'icon' => 'doll.png', 'sql' => ['npc.sql', 'magicdoll_info.sql', 'magicdoll_potential.sql']],
        ['id' => 'pages/npcs/npcs', 'name' => 'NPCs', 'icon' => 'npc.png', 'sql' => ['npc.sql']],
        ['id' => 'pages/skills/skills', 'name' => 'Skills', 'icon' => 'skill.png', 'sql' => ['skills.sql', 'skills_hanlder.sql', 'skills_info.sql', 'skills_passive.sql']],
        ['id' => 'pages/polymorph/polymorph', 'name' => 'Polymorph', 'icon' => 'poly.png', 'sql' => ['polymorphs.sql']]
    ];
}

// Function to get category image
function getCategoryImage($category) {
    // Extract just the last part of the path
    $parts = explode('/', $category);
    $lastPart = end($parts);
    
    // Map specific categories if needed
    $imageMap = [
        'weapon' => 'weapons'  // Map 'weapon' to 'weapons.png'
    ];
    
    $imageName = isset($imageMap[$lastPart]) ? $imageMap[$lastPart] : $lastPart;
    
    return "assets/img/placeholders/{$imageName}.png";
}

// Function to get category description
function getCategoryDescription($category) {
    $descriptions = [
        'weapons' => 'Browse all weapons including stats, skills, and attributes.',
        'armor' => 'View all armor pieces, sets, and their protective values.',
        'items' => 'Explore consumables, quest items, and other miscellaneous objects.',
        'monsters' => 'Discover monsters, their stats, drops, and spawning locations.',
        'maps' => 'Navigate through the game world with detailed map information.',
        'dolls' => 'Learn about magical dolls and their special abilities.',
        'npcs' => 'Meet the non-player characters that populate the game world.',
        'skills' => 'Study the various skills and spells available to characters.',
        'polymorph' => 'Transform into different creatures with polymorph abilities.'
    ];
    
    return $descriptions[$category] ?? 'No description available.';
}

// Get base URL for assets
function getBaseUrl() {
    $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    $base_dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    return $base_url . $base_dir;
}

// Clean item name by removing special prefixes
function cleanItemName($name) {
    // List of prefixes to remove
    $prefixes = [
        '\aH', '\aF', '\f4', '\a', '\f', '\H', '\F'
    ];
    
    // Remove each prefix from the name
    foreach ($prefixes as $prefix) {
        $name = str_replace($prefix, '', $name);
    }
    
    // Trim any extra whitespace
    $name = trim($name);
    
    return $name;
}

/**
 * Normalize text from all-caps to Title Case
 * Replaces underscores with spaces and converts to proper case
 * 
 * @param string $text The text to normalize
 * @return string The normalized text
 */
function normalizeText($text) {
    // Replace underscores with spaces
    $text = str_replace('_', ' ', $text);
    
    // Convert to title case (capitalize first letter of each word)
    $text = ucwords(strtolower($text));
    
    // Special case handling
    $text = str_replace('Tohand', 'Two-Handed', $text);
    
    return trim($text);
}

/**
 * Normalize material names from database format
 * Removes Korean text in parentheses, replaces underscores, and normalizes case
 * 
 * @param string $material The material name to normalize
 * @return string The normalized material name
 */
function normalizeMaterial($material) {
    // Remove Korean text in parentheses
    $material = preg_replace('/\([^)]*\)/', '', $material);
    
    // Replace underscores with spaces
    $material = str_replace('_', ' ', $material);
    
    // Remove "NONE(-)" and just return "None"
    $material = str_replace('NONE(-)', 'None', $material);
    
    // Convert to title case
    $material = ucwords(strtolower($material));
    
    return trim($material);
}

/**
 * Generate a back URL that preserves query parameters
 * Used for "Back to List" links in detail pages
 * 
 * @param string $default_url The default URL to return to if no referer is available
 * @return string The URL with query parameters preserved
 */
function getBackUrl($default_url) {
    $back_url = $default_url;
    
    // Check if there are query parameters we need to preserve
    if (isset($_SERVER['HTTP_REFERER'])) {
        $referer = $_SERVER['HTTP_REFERER'];
        $query_string = parse_url($referer, PHP_URL_QUERY);
        if (!empty($query_string)) {
            $back_url .= "?" . $query_string;
        }
    }
    
    return $back_url;
}