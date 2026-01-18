<?php
    $label = $label ?? '';
    $icon = $icon ?? '';
    $variant = $variant ?? 'primary'; 
    $styles = [
        'success' => 'bg-green-600/10 text-green-600 border-green-600',
        'warning' => 'bg-accent/10 text-accent border-accent',
        'neutral' => 'bg-gray-600/10 text-gray-600 border-gray-600',
        'primary' => 'bg-primary/10 text-primary border-primary',
        'danger'  => 'bg-red-600/10 text-red-600 border-red-600',
    ];
    $cssClass = $styles[$variant] ?? $styles['primary'];
?>
<span class="inline-flex items-center gap-1.5 border rounded-full px-3 py-1 text-xs font-bold <?= $cssClass ?> <?= isset($class) ? $class : '' ?>">
    <?php if(!empty($icon)): ?>
        <i class="<?= $icon ?>"></i>
    <?php endif; ?>
    <?= $label ?>
</span>