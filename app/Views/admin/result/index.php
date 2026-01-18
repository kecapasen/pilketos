<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<?php
    $colorMap = [
        'primary' => 'bg-primary/10 text-primary border-primary',
        'warning' => 'bg-accent/10 text-accent border-accent',
        'success' => 'bg-green-600/10 text-green-600 border-green-600',
        'danger'  => 'bg-red-600/10 text-red-600 border-red-600',
    ];
    $variants = ['primary', 'warning', 'success', 'danger'];
?>

<div class="w-full p-4 lg:p-8">
    <!-- Page Header -->
    <?= view('components/page_header', [
        'title'    => 'Hasil Voting',
        'subtitle' => 'Rekapitulasi hasil pemilihan Ketua OSIS',
    ]) ?>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div data-aos="fade-up" data-aos-delay="100">
            <?= view('components/stat_card', [
                'title'   => 'Total Pemilih',
                'value'   => $total_voters,
                'icon'    => 'bi-people-fill',
                'variant' => 'primary',
                'trend'   => '<i class="bi bi-person-vcard-fill me-1"></i> Terdaftar',
            ]) ?>
        </div>
        <div data-aos="fade-up" data-aos-delay="200">
            <?= view('components/stat_card', [
                'title'   => 'Sudah Memilih',
                'value'   => $already_voted,
                'icon'    => 'bi-check-circle-fill',
                'variant' => 'success',
                'trend'   => '<i class="bi bi-bar-chart-fill me-1"></i> ' . $vote_percentage . '% partisipasi',
            ]) ?>
        </div>
        <div data-aos="fade-up" data-aos-delay="300">
            <?= view('components/stat_card', [
                'title'   => 'Belum Memilih',
                'value'   => $not_voted,
                'icon'    => 'bi-hourglass-split',
                'variant' => 'warning',
                'trend'   => '<i class="bi bi-clock-fill me-1"></i> Menunggu voting',
            ]) ?>
        </div>
    </div>

    <?php if($winner): ?>
    <!-- Winner Announcement -->
    <div class="bg-gradient-to-r from-accent/20 via-accent/10 to-accent/20 rounded-2xl border-2 border-accent p-6 mb-6" data-aos="fade-up" data-aos-delay="350">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="relative">
                <div class="absolute -inset-2 bg-accent/30 rounded-full blur-lg animate-pulse"></div>
                <img src="<?= base_url('uploads/candidates/' . $winner->image) ?>" 
                     alt="<?= esc($winner->chairman_name) ?>"
                     class="relative w-24 h-24 md:w-32 md:h-32 object-cover object-top rounded-full border-4 border-accent shadow-xl">
                <div class="absolute -top-2 -right-2 w-10 h-10 bg-accent rounded-full flex items-center justify-center shadow-lg">
                    <i class="bi bi-trophy-fill text-white text-lg"></i>
                </div>
            </div>
            <div class="text-center md:text-left flex-1">
                <span class="inline-flex items-center gap-1.5 bg-accent text-white rounded-full px-3 py-1 text-xs font-bold mb-2">
                    <i class="bi bi-star-fill"></i> PERAIH SUARA TERBANYAK
                </span>
                <h2 class="text-2xl md:text-3xl font-bold text-primary mb-1"><?= esc($winner->chairman_name) ?></h2>
                <p class="text-primary mb-2">Wakil: <?= esc($winner->vice_chairman_name) ?></p>
                <div class="flex items-center justify-center md:justify-start gap-4">
                    <span class="text-4xl font-bold text-accent"><?= $winner_votes ?></span>
                    <span class="text-primary">suara</span>
                </div>
            </div>
            <div class="flex gap-2">
                <?= view('components/ui/button', [
                    'label'   => 'Cetak Hasil',
                    'variant' => 'accent',
                    'size'    => 'md',
                    'icon'    => 'bi bi-printer-fill',
                    'onclick' => 'window.print()',
                ]) ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Vote Results Table -->
    <div class="bg-surface rounded-2xl border border-primary overflow-hidden" data-aos="fade-up" data-aos-delay="400">
        <div class="p-4 border-b border-primary bg-input flex justify-between items-center">
            <h3 class="font-bold text-primary text-sm uppercase tracking-wider">
                <i class="bi bi-bar-chart-fill me-2"></i>Perolehan Suara
            </h3>
            <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary border border-primary rounded-full px-3 py-1 text-xs font-bold">
                <i class="bi bi-check2-all"></i> <?= $already_voted ?> suara masuk
            </span>
        </div>
        
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-collapse whitespace-nowrap min-w-[700px]">
                <thead class="bg-input border-b border-primary">
                    <tr>
                        <th class="py-3 px-4 lg:py-4 lg:px-6 text-xs font-bold uppercase tracking-wider text-primary">No</th>
                        <th class="py-3 px-4 lg:py-4 lg:px-6 text-xs font-bold uppercase tracking-wider text-primary">Kandidat</th>
                        <th class="py-3 px-4 lg:py-4 lg:px-6 text-xs font-bold uppercase tracking-wider text-primary">Perolehan Suara</th>
                        <th class="py-3 px-4 lg:py-4 lg:px-6 text-xs font-bold uppercase tracking-wider text-primary text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary/10">
                    <?php if(empty($vote_results)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-primary">
                                <i class="bi bi-inbox text-4xl block mb-2"></i>
                                Belum ada data kandidat
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($vote_results as $index => $result): 
                            $variant = $variants[$index % count($variants)];
                            $colorStyle = $colorMap[$variant];
                            $isWinner = $winner && $result->id == $winner->id;
                        ?>
                        <tr class="hover:bg-input transition-colors duration-200 <?= $isWinner ? 'bg-accent/5' : '' ?>">
                            <td class="py-4 px-6 align-middle">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold border <?= $colorStyle ?>">
                                    <?= $result->order_number ?>
                                </div>
                            </td>
                            <td class="py-4 px-6 align-middle">
                                <div class="flex items-center gap-4">
                                    <img src="<?= base_url('uploads/candidates/' . $result->image) ?>" 
                                         alt="<?= esc($result->chairman_name) ?>"
                                         class="w-12 h-12 object-cover object-top rounded-xl border border-primary">
                                    <div>
                                        <h6 class="font-bold text-primary text-sm flex items-center gap-2">
                                            <?= esc($result->chairman_name) ?>
                                            <?php if($isWinner): ?>
                                                <i class="bi bi-trophy-fill text-accent"></i>
                                            <?php endif; ?>
                                        </h6>
                                        <span class="text-xs text-primary">Paslon <?= $result->order_number ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 align-middle min-w-[300px]">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1">
                                        <div class="w-full bg-primary/10 border border-primary rounded-full h-4 overflow-hidden">
                                            <div class="h-4 rounded-full transition-all duration-500 <?= $isWinner ? 'bg-accent' : 'bg-primary' ?>" 
                                                 style="width: <?= $result->percentage ?>%"></div>
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold <?= $isWinner ? 'text-accent' : 'text-primary' ?> min-w-[50px]">
                                        <?= $result->percentage ?>%
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-6 align-middle text-right">
                                <span class="text-2xl font-bold <?= $isWinner ? 'text-accent' : 'text-primary' ?>">
                                    <?= $result->total_votes ?>
                                </span>
                                <span class="text-xs text-primary block">suara</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="bg-input p-4 border-t border-primary flex justify-between items-center">
            <small class="text-primary font-medium">
                <i class="bi bi-info-circle me-1"></i> Data diambil dari database
            </small>
            <?= view('components/ui/button', [
                'label'   => 'Cetak Laporan',
                'variant' => 'outline',
                'size'    => 'sm',
                'icon'    => 'bi bi-printer',
                'onclick' => 'window.print()',
            ]) ?>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        body * { visibility: hidden; }
        .w-full.p-8, .w-full.p-8 * { visibility: visible; }
        .w-full.p-8 { position: absolute; left: 0; top: 0; width: 100%; padding: 20px !important; }
        aside, nav, button, .bi-printer, [onclick] { display: none !important; }
        .bg-surface { box-shadow: none !important; border: 1px solid #ddd !important; }
    }
</style>

<?= $this->endSection() ?>
