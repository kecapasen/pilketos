<?php
    $name        = $name ?? '';
    $type        = $type ?? 'text';
    $label       = $label ?? null;
    $placeholder = $placeholder ?? '';
    $value       = $value ?? '';
    $required    = $required ?? false;
    $icon        = $icon ?? null;
    $customClass = $class ?? '';
    $attributes  = $attributes ?? '';
    
    $baseClass = "w-full bg-input border border-primary text-primary text-sm rounded-xl py-3 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all placeholder:text-gray-400";
    $paddingClass = $icon ? "pl-11 pr-4" : "px-4";
?>

<?php if($label): ?>
<div>
    <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-2"><?= $label ?></label>
<?php endif; ?>

    <div class="relative">
        <?php if($icon): ?>
            <i class="<?= $icon ?> absolute left-4 top-1/2 -translate-y-1/2 text-primary/50 text-lg z-10 pointer-events-none"></i>
        <?php endif; ?>
        <input 
            type="<?= $type ?>" 
            name="<?= $name ?>" 
            value="<?= $value ?>"
            placeholder="<?= $placeholder ?>"
            <?= $required ? 'required' : '' ?>
            <?= $attributes ?>
            class="<?= $baseClass ?> <?= $paddingClass ?> <?= $customClass ?>">
    </div>

<?php if($label): ?>
</div>
<?php endif; ?>
