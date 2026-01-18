<?php
/**
 * Button Component
 * 
 * CRITICAL FIX: Variable scope leakage in loops
 * 
 * The issue: When view() is called multiple times in a page, PHP variables 
 * from previous calls persist in scope. Even though we pass new values,
 * the old values may still be used if our logic is incorrect.
 * 
 * Solution: For all variables, we accept whatever value is passed in the 
 * current call - including empty strings. Empty string is a VALID value
 * that means "explicitly set to empty".
 */

// Core required variables - use isset to check if passed
$label   = isset($label) ? $label : 'Button';
$variant = isset($variant) ? $variant : 'primary';
$size    = isset($size) ? $size : 'md';
$type    = isset($type) ? $type : 'button';
$iconPos = isset($iconPos) ? $iconPos : 'start';

// href - only valid if non-empty string
$href = (isset($href) && is_string($href) && $href !== '' && $href !== null) ? $href : null;

// onclick - only valid if non-empty string  
$onclick = (isset($onclick) && is_string($onclick) && $onclick !== '' && $onclick !== null) ? $onclick : null;

// icon - only valid if non-empty string
$icon = (isset($icon) && is_string($icon) && $icon !== '' && $icon !== null) ? $icon : null;

// CRITICAL FIX: class and attributes
// These are the main culprits of leakage
// If passed (even as empty string), use the passed value
// Empty string is explicitly "no extra classes" or "no extra attributes"
$customClass = '';
if (isset($className)) {
    $customClass = $className;
} elseif (isset($class)) {
    // Use $class value AS-IS (even if empty string)
    $customClass = $class;
}

// Same for attributes
$attrs = '';
if (isset($attributes)) {
    $attrs = $attributes;
}

$baseClass = "inline-flex items-center justify-center font-bold rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 gap-2 ";

// Size variants
$sizes = [
    'xs' => 'px-3 py-1.5 text-xs',
    'sm' => 'px-4 py-2 text-sm',
    'md' => 'px-6 py-2.5 text-sm',
    'lg' => 'px-8 py-3 text-base',
    'icon' => 'w-8 h-8 p-0 text-xs',
    'icon-sm' => 'w-10 h-10 p-0 text-sm',
];

// Color/style variants
$variants = [
    'primary' => 'bg-primary border border-primary text-surface hover:bg-accent hover:border-accent hover:-translate-y-1',
    'outline' => 'bg-transparent border border-primary text-primary hover:bg-primary hover:text-surface hover:-translate-y-1',
    'white' => 'bg-white border border-white text-accent hover:bg-transparent hover:text-white hover:-translate-y-1',
    'outline-white' => 'bg-transparent border border-white text-white hover:bg-white hover:text-primary hover:-translate-y-1',
    'ghost' => 'bg-transparent border-none text-primary p-0 hover:text-accent hover:underline',
    'success' => 'bg-green-600 border border-green-600 text-white hover:bg-green-700 hover:border-green-700 hover:-translate-y-1',
    'success-outline' => 'bg-transparent border border-green-600 text-green-600 hover:bg-green-600 hover:text-white hover:-translate-y-1',
    'danger' => 'bg-red-600 border border-red-600 text-white hover:bg-red-700 hover:border-red-700 hover:-translate-y-1',
    'danger-outline' => 'bg-transparent border border-red-600 text-red-600 hover:bg-red-600 hover:text-white hover:-translate-y-1',
    'accent' => 'bg-accent border border-accent text-surface hover:bg-primary hover:border-primary hover:-translate-y-1',
    'accent-outline' => 'bg-transparent border border-accent text-accent hover:bg-accent hover:text-surface hover:-translate-y-1',
];

$sizeStyle = $sizes[$size] ?? $sizes['md'];
$variantStyle = $variants[$variant] ?? $variants['primary'];
$finalClass = trim($baseClass . $sizeStyle . ' ' . $variantStyle . ' ' . $customClass);

// Render as link if href is valid and not a submit button
$isLink = !empty($href) && $type !== 'submit';
?>
<?php if($isLink): ?>
<a href="<?= $href ?>" <?= $attrs ?> class="<?= $finalClass ?>">
    <?php if(!empty($icon) && $iconPos === 'start'): ?>
        <i class="<?= $icon ?>"></i>
    <?php endif; ?>
    <?= $label ?>
    <?php if(!empty($icon) && $iconPos === 'end'): ?>
        <i class="<?= $icon ?>"></i>
    <?php endif; ?>
</a>
<?php else: ?>
<button type="<?= $type ?>" <?php if($onclick): ?>onclick="<?= $onclick ?>"<?php endif; ?> <?= $attrs ?> class="<?= $finalClass ?>">
    <?php if(!empty($icon) && $iconPos === 'start'): ?>
        <i class="<?= $icon ?>"></i>
    <?php endif; ?>
    <?= $label ?>
    <?php if(!empty($icon) && $iconPos === 'end'): ?>
        <i class="<?= $icon ?>"></i>
    <?php endif; ?>
</button>
<?php endif; ?>