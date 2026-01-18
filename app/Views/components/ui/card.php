<?php
    $header      = $header ?? null;
    $headerIcon  = $headerIcon ?? null;
    $footer      = $footer ?? null;
    $customClass = $class ?? '';
    $animation   = $animation ?? 'zoom-in';
    $delay       = $delay ?? 0;
?>

<div class="bg-surface rounded-2xl border border-primary overflow-hidden <?= $customClass ?>" 
     <?php if($animation): ?>data-aos="<?= $animation ?>" data-aos-delay="<?= $delay ?>"<?php endif; ?>>
    
    <?php if($header): ?>
    <div class="p-4 border-b border-primary bg-input text-center">
        <?php if($headerIcon): ?>
        <div class="w-16 h-16 bg-primary/10 border border-primary rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="<?= $headerIcon ?> text-2xl text-primary"></i>
        </div>
        <?php endif; ?>
        <?= $header ?>
    </div>
    <?php endif; ?>
    
    <div class="p-6">
        <?= $slot ?? '' ?>
    </div>
    
    <?php if($footer): ?>
    <div class="bg-input p-4 border-t border-primary">
        <?= $footer ?>
    </div>
    <?php endif; ?>
</div>
