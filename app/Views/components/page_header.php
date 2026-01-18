<?php
    $title      = $title ?? 'Dashboard';
    $subtitle   = $subtitle ?? '';
    $showButton = $showButton ?? false;
    $btnLabel   = $btnLabel ?? 'Action';
    $btnIcon    = $btnIcon ?? null;
    $btnLink    = $btnLink ?? '#';
    $delay      = $delay ?? 0;
?>

<div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-10" data-aos="fade-down" data-aos-delay="<?= $delay ?>">
    <div>
        <h2 class="text-2xl md:text-3xl font-bold text-primary mb-1"><?= $title ?></h2>
        <?php if($subtitle): ?>
            <p class="text-primary mb-0"><?= $subtitle ?></p>
        <?php endif; ?>
    </div>
    <?php if($showButton): ?>
        <div>
            <?= view('components/ui/button', [
                'label'    => $btnLabel,
                'icon'     => $btnIcon,
                'href'     => $btnLink,
                'variant'  => 'outline',
            ]) ?>
        </div>
    <?php endif; ?>
</div>