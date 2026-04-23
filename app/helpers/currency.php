<?php
/**
 * Currency formatting helper.
 * Reads format settings from the database (cached per-request).
 */

function getCurrencySettings() {
    static $cache = null;
    if ($cache !== null) return $cache;

    require_once __DIR__ . '/../models/SettingsModel.php';
    $settingsModel = new SettingsModel();
    $all = $settingsModel->getAllSettings();

    $cache = [
        'symbol'             => $all['currency_symbol'] ?? 'AED',
        'position'           => $all['currency_position'] ?? 'before',      // before | after
        'decimal_separator'  => $all['decimal_separator'] ?? '.',           // . | ,
        'thousands_separator'=> $all['thousands_separator'] ?? ',',         // , | . | space | none
        'decimal_places'     => (int)($all['decimal_places'] ?? 2),         // 0 | 2 | 3
    ];

    return $cache;
}

/**
 * Format a numeric amount using the system currency settings.
 *
 * @param  float|int|string $amount
 * @param  bool   $showSymbol  Whether to include the currency symbol (default true)
 * @return string
 */
function formatMoney($amount, $showSymbol = true) {
    $c = getCurrencySettings();

    $thousands = $c['thousands_separator'];
    if ($thousands === 'space') $thousands = ' ';
    if ($thousands === 'none')  $thousands = '';

    $formatted = number_format(
        (float) $amount,
        $c['decimal_places'],
        $c['decimal_separator'],
        $thousands
    );

    if (!$showSymbol) return $formatted;

    if ($c['position'] === 'after') {
        return $formatted . ' ' . $c['symbol'];
    }
    return $c['symbol'] . $formatted;
}

/**
 * Return currency settings as a JSON string for JavaScript usage.
 */
function currencySettingsJson() {
    $c = getCurrencySettings();
    return json_encode($c);
}
