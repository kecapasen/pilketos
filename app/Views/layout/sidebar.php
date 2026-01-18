<?php
    $uri = service('uri');
    $currentPath = uri_string();
    
    if (!function_exists('isMenuActive')) {
        function isMenuActive($currentPath, $targetPath) {
            if ($targetPath === 'admin/dashboard') {
                return $currentPath === 'admin/dashboard';
            }
            return str_contains($currentPath, $targetPath);
        }
    }
    
    if (!function_exists('getMenuClasses')) {
        function getMenuClasses($isActive) {
            $base = "relative flex items-center gap-3 rounded-xl px-4 py-3 font-semibold transition-all duration-200 ease-in-out group ";
            if ($isActive) {
                return $base . "bg-primary text-surface";
            } else {
                return $base . "text-primary hover:bg-primary/10 hover:translate-x-1";
            }
        }
    }
    
    $menus = [
        [
            'label'   => 'Utama',
            'items'   => [
                ['icon' => 'bi-speedometer2', 'label' => 'Dashboard', 'href' => 'admin/dashboard'],
            ]
        ],
        [
            'label'   => 'Data Master',
            'items'   => [
                ['icon' => 'bi-people-fill', 'label' => 'Kandidat', 'href' => 'admin/candidates'],
                ['icon' => 'bi-person-vcard-fill', 'label' => 'Pemilih', 'href' => 'admin/voters'],
            ]
        ],
        [
            'label'   => 'Hasil',
            'items'   => [
                ['icon' => 'bi-bar-chart-fill', 'label' => 'Hasil Voting', 'href' => 'admin/results'],
            ]
        ],
    ];
?>

<nav id="sidebar" class="flex h-full w-64 flex-col border-r border-primary bg-surface transition-all duration-300 ease-in-out">
    <!-- Header -->
    <div class="flex items-center border-b border-primary px-6 py-6 mb-2">
        <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-3 text-decoration-none">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10 text-primary border-primary border">
                <i class="bi bi-award-fill text-lg"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-lg font-bold tracking-tight text-primary">
                    E-<span class="text-accent">Voting</span>
                </span>
                <span class="text-[0.65rem] font-bold tracking-[1px] text-primary uppercase">Admin Panel</span>
            </div>
        </a>
    </div>
    
    <!-- Menu -->
    <div class="grow overflow-y-hidden hover:overflow-y-auto px-4 py-2">
        <?php foreach ($menus as $group): ?>
        <small class="mb-2 block px-4 text-[0.65rem] font-bold tracking-wider text-primary uppercase"><?= $group['label'] ?></small>
        <ul class="mb-6 flex flex-col gap-1">
            <?php foreach ($group['items'] as $item): ?>
            <?php $active = isMenuActive($currentPath, $item['href']); ?>
            <li>
                <a href="<?= base_url($item['href']) ?>" class="<?= getMenuClasses($active) ?>">
                    <?php if($active): ?>
                        <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 rounded-r-md bg-accent"></span>
                    <?php endif; ?>
                    <i class="bi <?= $item['icon'] ?> text-lg w-6 text-center"></i>
                    <span><?= $item['label'] ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endforeach; ?>
    </div>
    
    <!-- User Profile & Logout -->
    <div class="border-t border-primary p-4">
        <div class="rounded-xl border border-primary bg-primary/10 p-3 mb-3">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center">
                    <i class="bi bi-person-fill text-surface text-lg"></i>
                </div>
                <div class="flex flex-col overflow-hidden">
                    <strong class="truncate text-sm font-bold text-primary"><?= esc(session()->get('admin_name') ?? 'Admin') ?></strong>
                    <small class="truncate text-xs text-primary/60">Administrator</small>
                </div>
            </div>
        </div>
        <a href="<?= base_url('admin/logout') ?>" class="flex items-center justify-center gap-2 w-full rounded-xl px-4 py-2.5 font-bold text-red-600 bg-red-600/10 border border-red-600 hover:bg-red-600 hover:text-white transition-all">
            <i class="bi bi-box-arrow-left"></i>
            <span>Logout</span>
        </a>
    </div>
</nav>