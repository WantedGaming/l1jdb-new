<?php
/**
 * enhancement-loader.php
 * 
 * This file now only includes the JavaScript enhancements
 * since the CSS has been merged into the main style.css file.
 */

/**
 * Enhance specific elements with JavaScript
 * These will apply the data attributes automatically to elements
 * that need enhancement without changing your HTML
 */
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Apply data attributes for positive/negative values in stat tables
    document.querySelectorAll('.stat-label').forEach(function(label) {
        const valueCell = label.nextElementSibling;
        if (valueCell) {
            const text = valueCell.textContent.trim();
            if (text.startsWith('+')) {
                valueCell.setAttribute('data-value-type', 'positive');
            } else if (text.startsWith('-')) {
                valueCell.setAttribute('data-value-type', 'negative');
            }
        }
    });
    
    // Highlight important stats
    const importantStats = ['STR', 'DEX', 'HP', 'Damage', 'Hit Modifier', 'Critical', 'Double Damage'];
    document.querySelectorAll('.stat-label').forEach(function(label) {
        const text = label.textContent.trim();
        if (importantStats.some(stat => text.includes(stat))) {
            label.classList.add('important-stat');
        }
    });
    
    // Enhance clickable rows
    document.querySelectorAll('.clickable-row').forEach(function(row) {
        row.addEventListener('mouseover', function() {
            row.querySelectorAll('td').forEach(function(cell) {
                cell.style.transition = 'all 0.2s ease';
            });
        });
    });
});
</script>