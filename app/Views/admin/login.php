<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="w-full flex items-center justify-center h-full p-8">
    <?= view('components/ui/card', [
        'class' => 'w-full max-w-md',
        'headerIcon' => 'bi-shield-lock-fill',
        'header' => '
            <h2 class="text-xl font-bold text-primary uppercase tracking-wider mb-1">Admin Login</h2>
            <p class="text-sm text-primary">Silakan masuk untuk mengelola sistem.</p>
        ',
        'footer' => '
            <p class="text-[11px] text-primary text-center flex items-center justify-center gap-1.5">
                <i class="bi bi-info-circle-fill text-primary"></i>
                Hanya admin yang berwenang yang dapat mengakses panel ini.
            </p>
        ',
        'slot' => '
            ' . (session()->getFlashdata('msg') ? '
                <div class="bg-red-50 border border-red-200 rounded-xl p-3 mb-4">
                    <p class="text-red-600 text-sm font-medium flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        ' . session()->getFlashdata('msg') . '
                    </p>
                </div>
            ' : '') . '
            <form action="' . base_url('admin/auth') . '" method="post" class="space-y-4">
                ' . csrf_field() . '
                
                ' . view('components/ui/input', [
                    'name'        => 'username',
                    'label'       => 'Username',
                    'placeholder' => 'Masukkan username',
                    'icon'        => 'bi-person-fill',
                    'required'    => true,
                ]) . '

                <div>
                    <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <i class="bi bi-key-fill absolute left-4 top-1/2 -translate-y-1/2 text-primary/50 text-lg z-10 pointer-events-none"></i>
                        <input type="password" name="password" required
                            placeholder="Masukkan password"
                            class="w-full bg-input border border-primary text-primary text-sm rounded-xl py-3 pl-11 pr-4 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all placeholder:text-gray-400">
                    </div>
                </div>

                ' . view('components/ui/button', [
                    'type'    => 'submit',
                    'label'   => 'MASUK',
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
