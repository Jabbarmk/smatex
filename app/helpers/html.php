<?php
/**
 * Sanitize rich text (from a contenteditable field) before storing/printing.
 * Keeps only basic formatting tags (bold, italic, underline, lists, paragraphs)
 * and strips all attributes so pasted content can't carry scripts/styles.
 */
function sanitizeRichText($html) {
    if ($html === null) return null;
    $html = trim($html);
    if ($html === '') return null;

    $allowedTags = '<b><strong><i><em><u><ul><ol><li><p><br><div>';
    $html = strip_tags($html, $allowedTags);
    $html = preg_replace('/<([a-z0-9]+)[^>]*>/i', '<$1>', $html);

    return $html;
}
