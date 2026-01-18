<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="w-full flex items-center justify-center p-8">
    <?= view('components/ui/card', [
        'class' => 'w-full max-w-md',
        'headerIcon' => 'bi-person-fill',
        'header' => '
            <h2 class="text-xl font-bold text-primary uppercase tracking-wider mb-1">Selamat Datang</h2>
            <p class="text-sm text-primary">Silakan masukkan token pemilihan Anda.</p>
        ',
        'footer' => '
            <p class="text-[11px] text-primary text-center flex items-center justify-center gap-1.5">
                <i class="bi bi-shield-lock-fill text-primary"></i>
                Token bersifat rahasia dan hanya bisa digunakan satu kali.
            </p>
        ',
        'slot' => '
            <form action="' . base_url('auth') . '" method="post" class="space-y-6">
                ' . csrf_field() . '
                
                ' . view('components/ui/input', [
                    'name'        => 'token',
                    'label'       => 'Token Pemilihan',
                    'placeholder' => 'Masukkan Token (Ex: A8K21)',
                    'icon'        => 'bi-key-fill',
                    'required'    => true,
                    'class'       => 'text-lg font-bold tracking-widest uppercase text-center placeholder:text-base placeholder:font-normal placeholder:tracking-normal',
                    'attributes'  => 'autocomplete="off"',
                ]) . '

                ' . view('components/ui/button', [
                    'type'    => 'submit',
                    'label'   => 'MASUK BILIK SUARA',
                    'icon'    => 'bi-box-arrow-in-right',
                    'variant' => 'primary',
                    'size'    => 'lg',
                    'class'   => 'w-full',
                ]) . '
            </form>
        ',
    ]) ?>
</div>

<?= $this->endSection() ?>