<?php
    $title   = $title ?? 'Statistik';
    $value   = $value ?? '0';
    $icon    = $icon ?? 'bi-bar-chart';
    $variant = $variant ?? 'primary';
    $trend   = $trend ?? null;
    $bgMap = [
        'primary' => 'bg-primary/10 text-primary border-primary border', 
        'warning' => 'bg-accent/10 text-accent border-accent border',    
        'danger'  => 'bg-red-50 text-red-500 border-red-500 border',      
        'success' => 'bg-green-50 text-green-600 border-green-500 border'   
    ];
    $iconStyle = $bgMap[$variant] ?? $bgMap['primary'];
?>
<div class="bg-surface rounded-2xl p-6 h-full border border-primary hover:-translate-y-2 transition-all duration-300">
    <div class="flex items-start justify-between mb-4">
        <div>
            <h6 class="text-primary uppercase font-bold text-xs tracking-wider mb-1">
                <?= $title ?>
            </h6>
            <h2 class="text-5xl font-bold text-primary leading-tight">
                <?= $value ?>
            </h2>
        </div>
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl <?= $iconStyle ?>">
            <i class="<?= $icon ?>"></i>
        </div>
    </div>
    <?php if($trend): ?>
        <div class="mt-4 pt-4 border-t border-primary">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface border border-primary text-sm text-primary">
                <?= $trend ?>
            </div>
        </div>
    <?php endif; ?>
</div>