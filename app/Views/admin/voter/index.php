<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<?php 
    $totalVoters = count($voters);
    $votedCount = count(array_filter($voters, fn($v) => $v->status === 'voted'));
    $notVotedCount = $totalVoters - $votedCount;
?>

<div class="w-full p-4 lg:p-8">
    <!-- Page Header -->
    <?= view('components/page_header', [
        'title'    => 'Manajemen Pemilih',
        'subtitle' => 'Daftar Pemilih Tetap (DPT) dan token voting',
    ]) ?>

    <!-- Flash Messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="mb-6 bg-green-600/10 border border-green-600 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="bi bi-check-circle-fill"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    
    <?php if(session()->getFlashdata('error')): ?>
        <div class="mb-6 bg-red-600/10 border border-red-600 text-red-600 px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    
    <?php if(session()->getFlashdata('errors')): ?>
        <div class="mb-6 bg-red-600/10 border border-red-600 text-red-600 px-4 py-3 rounded-xl">
            <div class="flex items-center gap-2 mb-2">
                <i class="bi bi-exclamation-circle-fill"></i>
                <strong>Terjadi kesalahan:</strong>
            </div>
            <ul class="list-disc list-inside text-sm">
                <?php foreach(session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div data-aos="fade-up" data-aos-delay="100">
            <?= view('components/stat_card', [
                'title'   => 'Total Pemilih',
                'value'   => $totalVoters,
                'icon'    => 'bi-people-fill',
                'variant' => 'primary',
                'trend'   => '<i class="bi bi-person-vcard-fill me-1"></i> Terdaftar',
            ]) ?>
        </div>
        <div data-aos="fade-up" data-aos-delay="200">
            <?= view('components/stat_card', [
                'title'   => 'Sudah Memilih',
                'value'   => $votedCount,
                'icon'    => 'bi-check-circle-fill',
                'variant' => 'success',
                'trend'   => '<i class="bi bi-bar-chart-fill me-1"></i> ' . ($totalVoters > 0 ? round(($votedCount / $totalVoters) * 100, 1) : 0) . '% partisipasi',
            ]) ?>
        </div>
        <div data-aos="fade-up" data-aos-delay="300">
            <?= view('components/stat_card', [
                'title'   => 'Belum Memilih',
                'value'   => $notVotedCount,
                'icon'    => 'bi-hourglass-split',
                'variant' => 'warning',
                'trend'   => '<i class="bi bi-clock-fill me-1"></i> Menunggu voting',
            ]) ?>
        </div>
    </div>

    <!-- Voters Table -->
    <div class="bg-surface rounded-2xl border border-primary overflow-hidden" data-aos="fade-up" data-aos-delay="400">
        <div class="p-4 border-b border-primary bg-input flex justify-between items-center">
            <h3 class="font-bold text-primary text-sm uppercase tracking-wider">
                <i class="bi bi-person-lines-fill me-2"></i>Daftar Pemilih
            </h3>
            <div class="flex items-center gap-2">
                <?= view('components/ui/button', [
                    'label'   => 'Tambah',
                    'variant' => 'outline',
                    'size'    => 'sm',
                    'icon'    => 'bi bi-person-plus-fill',
                    'onclick' => 'openAddModal()',
                ]) ?>
                <?= view('components/ui/button', [
                    'label'   => 'Generate',
                    'variant' => 'primary',
                    'size'    => 'sm',
                    'icon'    => 'bi bi-people-fill',
                    'onclick' => 'openBulkModal()',
                ]) ?>
            </div>
        </div>
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-collapse whitespace-nowrap min-w-[700px]">
                <thead class="bg-input border-b border-primary">
                    <tr>
                        <th class="py-3 px-4 lg:py-4 lg:px-6 text-xs font-bold uppercase tracking-wider text-primary">#</th>
                        <th class="py-3 px-4 lg:py-4 lg:px-6 text-xs font-bold uppercase tracking-wider text-primary">Nama</th>
                        <th class="py-3 px-4 lg:py-4 lg:px-6 text-xs font-bold uppercase tracking-wider text-primary">Kelas</th>
                        <th class="py-3 px-4 lg:py-4 lg:px-6 text-xs font-bold uppercase tracking-wider text-primary">Token</th>
                        <th class="py-3 px-4 lg:py-4 lg:px-6 text-xs font-bold uppercase tracking-wider text-primary text-center">Status</th>
                        <th class="py-3 px-4 lg:py-4 lg:px-6 text-xs font-bold uppercase tracking-wider text-primary text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary/10">
                    <?php if(empty($voters)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-primary">
                                <i class="bi bi-inbox text-4xl block mb-2"></i>
                                Belum ada data pemilih. Klik "Generate Bulk" untuk membuat token voting.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($voters as $index => $voter): ?>
                        <tr class="hover:bg-input transition-colors duration-200 group">
                            <td class="py-4 px-6 align-middle text-primary"><?= $index + 1 ?></td>
                            <td class="py-4 px-6 align-middle">
                                <h6 class="font-bold text-primary text-sm transition-colors"><?= esc($voter->name) ?></h6>
                            </td>
                            <td class="py-4 px-6 align-middle">
                                <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary border border-primary rounded-full px-3 py-1 text-xs font-bold">
                                    <i class="bi bi-mortarboard-fill"></i> <?= esc($voter->class_group) ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 align-middle">
                                <code class="px-3 py-1.5 bg-primary/10 text-primary border border-primary rounded-lg font-mono text-sm font-bold"><?= esc($voter->token) ?></code>
                            </td>
                            <td class="py-4 px-6 align-middle text-center">
                                <?php if($voter->status === 'voted'): ?>
                                    <?= view('components/ui/badge', [
                                        'label'   => 'Sudah',
                                        'icon'    => 'bi-check-circle-fill',
                                        'variant' => 'success',
                                    ]) ?>
                                <?php else: ?>
                                    <?= view('components/ui/badge', [
                                        'label'   => 'Belum',
                                        'icon'    => 'bi-hourglass-split',
                                        'variant' => 'warning',
                                    ]) ?>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-6 align-middle text-right">
                                <div class="flex justify-end gap-2">
                                    <?php if($voter->status === 'voted'): ?>
                                        <?= view('components/ui/button', [
                                            'label'      => '',
                                            'href'       => base_url('admin/voter/reset/' . $voter->id),
                                            'onclick'    => null,
                                            'variant'    => 'accent-outline',
                                            'size'       => 'icon',
                                            'icon'       => 'bi bi-arrow-counterclockwise',
                                            'attributes' => 'title="Reset Status"',
                                        ]) ?>
                                    <?php endif; ?>
                                    <?= view('components/ui/button', [
                                        'label'      => '',
                                        'href'       => null,
                                        'variant'    => 'danger-outline',
                                        'size'       => 'icon',
                                        'icon'       => 'bi bi-trash',
                                        'onclick'    => "confirmDelete({$voter->id}, '" . esc($voter->name) . "')",
                                        'attributes' => 'title="Hapus"',
                                    ]) ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if(!empty($voters)): ?>
        <div class="bg-input p-4 border-t border-primary flex justify-between items-center">
            <small class="text-primary font-medium">Menampilkan <?= $totalVoters ?> pemilih</small>
            <?= view('components/ui/button', [
                'label'   => 'Hapus Semua Data',
                'variant' => 'ghost',
                'size'    => 'sm',
                'icon'    => 'bi bi-trash',
                'onclick' => 'confirmClearAll()',
                'class'   => 'text-red-600 hover:text-red-700',
            ]) ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Single Voter Modal -->
<div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-surface rounded-2xl border border-primary w-full max-w-md mx-4 overflow-hidden">
        <div class="p-4 border-b border-primary bg-input flex justify-between items-center">
            <h3 class="font-bold text-primary text-sm uppercase tracking-wider">
                <i class="bi bi-person-plus-fill me-2"></i>Tambah Pemilih
            </h3>
            <?= view('components/ui/button', [
                'label'   => '',
                'variant' => 'outline',
                'size'    => 'icon',
                'icon'    => 'bi bi-x-lg',
                'onclick' => 'closeAddModal()',
            ]) ?>
        </div>
        <form action="<?= base_url('admin/voter/store') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Nama Lengkap</label>
                <input type="text" name="name" required
                       class="w-full bg-input border border-primary text-primary text-sm rounded-xl py-3 px-4 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all"
                       placeholder="Masukkan nama lengkap">
            </div>
            <div>
                <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Kelas</label>
                <input type="text" name="class_group" required
                       class="w-full bg-input border border-primary text-primary text-sm rounded-xl py-3 px-4 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all"
                       placeholder="Contoh: XII MIPA 1">
            </div>
            <div class="flex gap-3 justify-end pt-4 border-t border-primary">
                <?= view('components/ui/button', [
                    'label'   => 'Batal',
                    'variant' => 'outline',
                    'size'    => 'md',
                    'onclick' => 'closeAddModal()',
                ]) ?>
                <?= view('components/ui/button', [
                    'label'   => 'Simpan',
                    'variant' => 'accent',
                    'size'    => 'md',
                    'type'    => 'submit',
                    'icon'    => 'bi bi-check-circle-fill',
                ]) ?>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Generate Modal -->
<div id="bulkModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-surface rounded-2xl border border-primary w-full max-w-md mx-4 overflow-hidden">
        <div class="p-4 border-b border-primary bg-input flex justify-between items-center">
            <h3 class="font-bold text-primary text-sm uppercase tracking-wider">
                <i class="bi bi-people-fill me-2"></i>Generate Token Massal
            </h3>
            <?= view('components/ui/button', [
                'label'   => '',
                'variant' => 'outline',
                'size'    => 'icon',
                'icon'    => 'bi bi-x-lg',
                'onclick' => 'closeBulkModal()',
            ]) ?>
        </div>
        <form action="<?= base_url('admin/voter/generate') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Jumlah Token</label>
                <input type="number" name="amount" required min="1" max="100" value="10"
                       class="w-full bg-input border border-primary text-primary text-sm rounded-xl py-3 px-4 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Kelas</label>
                <input type="text" name="class_group" required
                       class="w-full bg-input border border-primary text-primary text-sm rounded-xl py-3 px-4 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all"
                       placeholder="Contoh: XII MIPA 1">
            </div>
            <div class="flex gap-3 justify-end pt-4 border-t border-primary">
                <?= view('components/ui/button', [
                    'label'   => 'Batal',
                    'variant' => 'outline',
                    'size'    => 'md',
                    'onclick' => 'closeBulkModal()',
                ]) ?>
                <?= view('components/ui/button', [
                    'label'   => 'Generate',
                    'variant' => 'accent',
                    'size'    => 'md',
                    'type'    => 'submit',
                    'icon'    => 'bi bi-lightning-fill',
                ]) ?>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-surface rounded-2xl border border-primary w-full max-w-md mx-4 overflow-hidden">
        <div class="p-4 border-b border-primary bg-input flex justify-between items-center">
            <h3 class="font-bold text-primary text-sm uppercase tracking-wider">
                <i class="bi bi-exclamation-triangle-fill me-2 text-red-600"></i>Konfirmasi Hapus
            </h3>
            <button type="button" onclick="closeDeleteModal()" class="inline-flex items-center justify-center font-bold rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 gap-2 w-8 h-8 p-0 text-xs bg-transparent border border-primary text-primary hover:bg-primary hover:text-surface hover:-translate-y-1">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="p-6 text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-red-600/10 border border-red-600 rounded-full flex items-center justify-center">
                <i class="bi bi-trash-fill text-red-600 text-2xl"></i>
            </div>
            <p class="text-primary mb-6">Apakah Anda yakin ingin menghapus pemilih <span id="deleteName" class="font-bold"></span>?</p>
            <div class="flex gap-3 justify-center">
                <button type="button" onclick="closeDeleteModal()" class="inline-flex items-center justify-center font-bold rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 gap-2 px-6 py-2.5 text-sm bg-transparent border border-primary text-primary hover:bg-primary hover:text-surface hover:-translate-y-1">
                    Batal
                </button>
                <a id="deleteLink" href="#">
                    <button type="button" class="inline-flex items-center justify-center font-bold rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 gap-2 px-6 py-2.5 text-sm bg-red-600 border border-red-600 text-white hover:bg-red-700 hover:border-red-700 hover:-translate-y-1">
                        <i class="bi bi-trash-fill"></i>
                        Ya, Hapus
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Clear All Confirmation Modal -->
<div id="clearAllModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-surface rounded-2xl border border-primary w-full max-w-md mx-4 overflow-hidden">
        <div class="p-4 border-b border-primary bg-input flex justify-between items-center">
            <h3 class="font-bold text-primary text-sm uppercase tracking-wider">
                <i class="bi bi-exclamation-triangle-fill me-2 text-red-600"></i>Hapus Semua
            </h3>
            <?= view('components/ui/button', [
                'label'   => '',
                'variant' => 'outline',
                'size'    => 'icon',
                'icon'    => 'bi bi-x-lg',
                'onclick' => 'closeClearAllModal()',
            ]) ?>
        </div>
        <div class="p-6 text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-red-600/10 border border-red-600 rounded-full flex items-center justify-center">
                <i class="bi bi-exclamation-triangle-fill text-red-600 text-2xl"></i>
            </div>
            <p class="text-primary mb-2 font-bold">Hapus SEMUA Data?</p>
            <p class="text-primary text-sm mb-6">Tindakan ini akan menghapus seluruh data pemilih dan tidak dapat dibatalkan!</p>
            <div class="flex gap-3 justify-center">
                <?= view('components/ui/button', [
                    'label'   => 'Batal',
                    'variant' => 'outline',
                    'size'    => 'md',
                    'onclick' => 'closeClearAllModal()',
                ]) ?>
                <?= view('components/ui/button', [
                    'label'   => 'Ya, Hapus Semua',
                    'href'    => base_url('admin/voter/clear'),
                    'variant' => 'danger',
                    'size'    => 'md',
                    'icon'    => 'bi bi-trash-fill',
                ]) ?>
            </div>
        </div>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
        document.getElementById('addModal').classList.add('flex');
    }
    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
        document.getElementById('addModal').classList.remove('flex');
    }
    
    function openBulkModal() {
        document.getElementById('bulkModal').classList.remove('hidden');
        document.getElementById('bulkModal').classList.add('flex');
    }
    function closeBulkModal() {
        document.getElementById('bulkModal').classList.add('hidden');
        document.getElementById('bulkModal').classList.remove('flex');
    }
    
    function confirmDelete(id, name) {
        document.getElementById('deleteName').textContent = name;
        document.getElementById('deleteLink').href = '<?= base_url('admin/voter/delete/') ?>' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
    
    function confirmClearAll() {
        document.getElementById('clearAllModal').classList.remove('hidden');
        document.getElementById('clearAllModal').classList.add('flex');
    }
    function closeClearAllModal() {
        document.getElementById('clearAllModal').classList.add('hidden');
        document.getElementById('clearAllModal').classList.remove('flex');
    }
    
    ['addModal', 'bulkModal', 'deleteModal', 'clearAllModal'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                this.classList.remove('flex');
            }
        });
    });
</script>

<?= $this->endSection() ?>
