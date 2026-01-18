<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="w-full p-4 lg:p-8">
    <!-- Page Header -->
    <?= view('components/page_header', [
        'title'    => 'Kelola Kandidat',
        'subtitle' => 'Daftar pasangan calon ketua dan wakil ketua OSIS',
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

    <!-- Candidates Table -->
    <div class="bg-surface rounded-2xl border border-primary overflow-hidden">
        <div class="p-4 border-b border-primary bg-input flex justify-between items-center">
            <h3 class="font-bold text-primary text-sm uppercase tracking-wider">
                <i class="bi bi-people-fill me-2"></i>Daftar Kandidat
            </h3>
            <?= view('components/ui/button', [
                'label'   => 'Tambah Kandidat',
                'href'    => base_url('admin/candidate/create'),
                'variant' => 'primary',
                'size'    => 'sm',
                'icon'    => 'bi bi-plus-circle-fill',
            ]) ?>
        </div>
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-collapse whitespace-nowrap min-w-[600px]">
                <thead class="bg-input border-b border-primary">
                    <tr>
                        <th class="py-3 px-4 lg:py-4 lg:px-6 text-xs font-bold uppercase tracking-wider text-primary">No</th>
                        <th class="py-3 px-8 lg:py-4 lg:px-6 text-xs font-bold uppercase tracking-wider text-primary">Foto</th>
                        <th class="py-3 px-4 lg:py-4 lg:px-6 text-xs font-bold uppercase tracking-wider text-primary">Ketua & Wakil</th>
                        <th class="py-3 px-4 lg:py-4 lg:px-6 text-xs font-bold uppercase tracking-wider text-primary">Visi</th>
                        <th class="py-3 px-4 lg:py-4 lg:px-6 text-xs font-bold uppercase tracking-wider text-primary text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary/10">
                    <?php if(empty($candidates)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-primary">
                                <i class="bi bi-inbox text-4xl block mb-2"></i>
                                Belum ada kandidat. Klik tombol "Tambah Kandidat" untuk menambahkan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $colorMap = [
                            'primary' => 'bg-primary/10 text-primary border-primary',
                            'warning' => 'bg-accent/10 text-accent border-accent',
                            'success' => 'bg-green-600/10 text-green-600 border-green-600',
                            'danger'  => 'bg-red-600/10 text-red-600 border-red-600',
                        ];
                        $variants = ['primary', 'warning', 'success', 'danger'];
                        foreach($candidates as $index => $candidate): 
                            $variant = $variants[$index % count($variants)];
                            $colorStyle = $colorMap[$variant];
                        ?>
                        <tr class="hover:bg-input transition-colors duration-200 group">
                            <td class="py-4 px-6 align-middle">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold border <?= $colorStyle ?>">
                                    <?= $candidate->order_number ?>
                                </div>
                            </td>
                            <td class="py-4 px-6 align-middle">
                                <img src="<?= base_url('uploads/candidates/' . $candidate->image) ?>" 
                                     alt="<?= esc($candidate->chairman_name) ?>"
                                     class="w-14 h-14 object-cover rounded-xl border border-primary">
                            </td>
                            <td class="py-4 px-6 align-middle">
                                <div class="flex items-center gap-4">
                                    <div>
                                        <h6 class="font-bold text-primary text-sm mb-0.5 transition-colors"><?= esc($candidate->chairman_name) ?></h6>
                                        <span class="text-xs text-primary"><?= esc($candidate->chairman_class) ?></span>
                                        <div class="mt-1 text-xs">
                                            <span class="text-primary">Wakil:</span>
                                            <span class="font-semibold text-primary"><?= esc($candidate->vice_chairman_name) ?></span>
                                            <span class="text-primary">(<?= esc($candidate->vice_chairman_class) ?>)</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 align-middle">
                                <p class="text-sm text-primary max-w-xs truncate"><?= esc($candidate->vision) ?></p>
                            </td>
                            <td class="py-4 px-6 align-middle text-right">
                                <div class="flex justify-end gap-2">
                                    <?= view('components/ui/button', [
                                        'label'   => '',
                                        'href'    => base_url('admin/candidate/edit/' . $candidate->id),
                                        'onclick' => null,  // Explicit null to prevent leakage from delete button
                                        'variant' => 'outline',
                                        'size'    => 'icon',
                                        'icon'    => 'bi bi-pencil',
                                        'attributes' => 'title="Edit"',
                                    ]) ?>
                                    <?= view('components/ui/button', [
                                        'label'   => '',
                                        'href'    => null,  // Explicit null to prevent leakage from edit button
                                        'variant' => 'danger-outline',
                                        'size'    => 'icon',
                                        'icon'    => 'bi bi-trash',
                                        'onclick' => "confirmDelete({$candidate->id}, '" . esc($candidate->chairman_name) . "')",
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
        <?php if(!empty($candidates)): ?>
        <div class="bg-input p-4 border-t border-primary flex justify-between items-center">
            <small class="text-primary font-medium">Menampilkan <?= count($candidates) ?> kandidat</small>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-surface rounded-2xl border border-primary w-full max-w-md mx-4 overflow-hidden">
        <div class="p-4 border-b border-primary bg-input flex justify-between items-center">
            <h3 class="font-bold text-primary text-sm uppercase tracking-wider">
                <i class="bi bi-exclamation-triangle-fill me-2 text-red-600"></i>Konfirmasi Hapus
            </h3>
            <?= view('components/ui/button', [
                'label'   => '',
                'variant' => 'outline',
                'size'    => 'icon',
                'icon'    => 'bi bi-x-lg',
                'onclick' => 'closeDeleteModal()',
            ]) ?>
        </div>
        <div class="p-6 text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-red-600/10 border border-red-600 rounded-full flex items-center justify-center">
                <i class="bi bi-trash-fill text-red-600 text-2xl"></i>
            </div>
            <p class="text-primary mb-6">Apakah Anda yakin ingin menghapus kandidat <span id="deleteName" class="font-bold"></span>?</p>
            <div class="flex gap-3 justify-center">
                <?= view('components/ui/button', [
                    'label'   => 'Batal',
                    'variant' => 'outline',
                    'size'    => 'md',
                    'onclick' => 'closeDeleteModal()',
                ]) ?>
                <form id="deleteForm" method="POST" class="inline">
                    <?= view('components/ui/button', [
                        'label'   => 'Ya, Hapus',
                        'variant' => 'danger',
                        'size'    => 'md',
                        'type'    => 'submit',
                        'icon'    => 'bi bi-trash-fill',
                    ]) ?>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        document.getElementById('deleteName').textContent = name;
        document.getElementById('deleteForm').action = '<?= base_url('admin/candidate/delete/') ?>' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
    
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
</script>

<?= $this->endSection() ?>
