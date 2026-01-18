<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="w-full min-h-dvh flex items-center justify-center p-8">
    <?= view('components/ui/card', [
        'class' => 'w-full max-w-md',
        'headerIcon' => 'bi-check-circle-fill',
        'header' => '
            <h2 class="text-xl font-bold text-primary uppercase tracking-wider mb-1">Terima Kasih!</h2>
            <p class="text-sm text-primary/60">Suara Anda telah berhasil direkam</p>
        ',
        'footer' => '
            <p class="text-[11px] text-primary text-center flex items-center justify-center gap-1.5">
                <i class="bi bi-clock-fill text-primary"></i>
                Anda akan otomatis logout dalam <span id="countdown" class="font-bold">5</span> detik
            </p>
        ',
        'slot' => '
            <div class="text-center space-y-6">
                <div class="w-20 h-20 bg-green-600/10 border border-green-600 rounded-full flex items-center justify-center mx-auto">
                    <i class="bi bi-hand-thumbs-up-fill text-3xl text-green-600"></i>
                </div>
                
                <div>
                    <p class="text-primary mb-2">Partisipasi Anda sangat berarti untuk kemajuan sekolah kita.</p>
                    <p class="text-sm text-primary/60">Terima kasih telah menggunakan hak suara Anda dengan bijak.</p>
                </div>
                
                ' . view('components/ui/button', [
                    'href'    => base_url('/'),
                    'label'   => 'KEMBALI KE BERANDA',
                    'icon'    => 'bi-house-fill',
                    'variant' => 'primary',
                    'class'   => 'w-full',
                ]) . '
            </div>
        ',
    ]) ?>
</div>

<script>
    let seconds = 5;
    const countdownEl = document.getElementById('countdown');
    
    const interval = setInterval(function() {
        seconds--;
        countdownEl.textContent = seconds;
        
        if (seconds <= 0) {
            clearInterval(interval);
            window.location.href = "<?= base_url('/') ?>";
        }
    }, 1000);
</script>

<?= $this->endSection() ?>