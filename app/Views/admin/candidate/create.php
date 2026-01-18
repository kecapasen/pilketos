<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="w-full p-4 lg:p-8">
    <!-- Page Header -->
    <?= view('components/page_header', [
        'title'    => 'Tambah Kandidat',
        'subtitle' => 'Isi data pasangan calon ketua dan wakil ketua OSIS',
    ]) ?>

    <!-- Flash Messages -->
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

    <!-- Form Card -->
    <div class="bg-surface rounded-2xl border border-primary overflow-hidden">
        <div class="p-4 border-b border-primary bg-input flex justify-between items-center">
            <h3 class="font-bold text-primary text-sm uppercase tracking-wider">
                <i class="bi bi-person-plus-fill me-2"></i>Form Kandidat Baru
            </h3>
            <?= view('components/ui/button', [
                'label'   => 'Kembali',
                'href'    => base_url('admin/candidates'),
                'variant' => 'outline',
                'size'    => 'sm',
                'icon'    => 'bi bi-arrow-left',
            ]) ?>
        </div>
        
        <form action="<?= base_url('admin/candidate/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="p-6 space-y-6">
                <!-- Nomor Urut -->
                <div>
                    <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Nomor Urut <span class="text-red-500">*</span></label>
                    <input type="number" name="order_number" value="<?= old('order_number') ?>" required min="1"
                           class="w-full bg-input border <?= session('errors.order_number') ? 'border-red-500' : 'border-primary' ?> text-primary text-sm rounded-xl py-3 px-4 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all">
                    <?php if(session('errors.order_number')): ?>
                        <p class="text-xs text-red-500 mt-1"><?= session('errors.order_number') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Ketua Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Nama Ketua <span class="text-red-500">*</span></label>
                        <input type="text" name="chairman_name" value="<?= old('chairman_name') ?>" required
                               class="w-full bg-input border border-primary text-primary text-sm rounded-xl py-3 px-4 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all"
                               placeholder="Contoh: Ahmad Rizky">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Kelas Ketua <span class="text-red-500">*</span></label>
                        <input type="text" name="chairman_class" value="<?= old('chairman_class') ?>" required
                               class="w-full bg-input border border-primary text-primary text-sm rounded-xl py-3 px-4 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all"
                               placeholder="Contoh: XII MIPA 1">
                    </div>
                </div>

                <!-- Wakil Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Nama Wakil <span class="text-red-500">*</span></label>
                        <input type="text" name="vice_chairman_name" value="<?= old('vice_chairman_name') ?>" required
                               class="w-full bg-input border border-primary text-primary text-sm rounded-xl py-3 px-4 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all"
                               placeholder="Contoh: Siti Aminah">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Kelas Wakil <span class="text-red-500">*</span></label>
                        <input type="text" name="vice_chairman_class" value="<?= old('vice_chairman_class') ?>" required
                               class="w-full bg-input border border-primary text-primary text-sm rounded-xl py-3 px-4 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all"
                               placeholder="Contoh: XI IPS 2">
                    </div>
                </div>

                <!-- Visi -->
                <div>
                    <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Visi <span class="text-red-500">*</span></label>
                    <textarea name="vision" required rows="3"
                              class="w-full bg-input border border-primary text-primary text-sm rounded-xl py-3 px-4 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all resize-none"
                              placeholder="Tuliskan visi pasangan calon..."><?= old('vision') ?></textarea>
                </div>

                <!-- Misi -->
                <div>
                    <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Misi <span class="text-red-500">*</span></label>
                    <textarea name="mission" required rows="5"
                              class="w-full bg-input border border-primary text-primary text-sm rounded-xl py-3 px-4 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all resize-none"
                              placeholder="Tuliskan misi pasangan calon (pisahkan dengan enter untuk poin-poin)..."><?= old('mission') ?></textarea>
                </div>

                <!-- Foto -->
                <div>
                    <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Foto Paslon <span class="text-red-500">*</span></label>
                    <div class="flex items-start gap-4">
                        <div id="imagePreview" class="w-24 h-24 bg-primary/10 border border-primary rounded-xl flex items-center justify-center overflow-hidden shrink-0">
                            <i class="bi bi-image text-3xl text-primary"></i>
                        </div>
                        <div class="flex-1">
                            <div class="border-2 border-dashed border-primary rounded-xl p-4 text-center hover:border-accent hover:bg-accent/5 transition-all cursor-pointer" id="dropZone">
                                <input type="file" name="image" id="imageInput" accept="image/jpeg,image/jpg,image/png" class="hidden">
                                <div class="flex flex-col items-center gap-1">
                                    <i class="bi bi-cloud-arrow-up text-2xl text-primary"></i>
                                    <p class="text-sm text-primary font-medium">Drag & Drop atau <span class="text-accent font-bold">klik untuk browse</span></p>
                                    <p class="text-[10px] text-primary">Format: JPG, JPEG, PNG. Max 2MB</p>
                                </div>
                            </div>
                            <?php if(session('errors.image')): ?>
                                <p class="text-xs text-red-500 mt-1"><?= session('errors.image') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Footer -->
            <div class="px-6 py-4 bg-input border-t border-primary flex justify-end gap-3">
                <?= view('components/ui/button', [
                    'label'   => 'Batal',
                    'href'    => base_url('admin/candidates'),
                    'variant' => 'outline',
                    'size'    => 'md',
                ]) ?>
                <?= view('components/ui/button', [
                    'label'   => 'Simpan Kandidat',
                    'variant' => 'primary',
                    'size'    => 'md',
                    'type'    => 'submit',
                    'icon'    => 'bi bi-check-circle-fill',
                ]) ?>
            </div>
        </form>
    </div>
</div>

<script>
    const dropZone = document.getElementById('dropZone');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');

    dropZone?.addEventListener('click', () => imageInput.click());
    
    dropZone?.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-accent', 'bg-accent/10');
    });

    dropZone?.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-accent', 'bg-accent/10');
    });

    dropZone?.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-accent', 'bg-accent/10');
        const files = e.dataTransfer.files;
        if (files.length) {
            imageInput.files = files;
            showPreview(files[0]);
        }
    });

    imageInput?.addEventListener('change', function() {
        if (this.files.length) {
            showPreview(this.files[0]);
        }
    });

    function showPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        }
        reader.readAsDataURL(file);
    }
</script>

<?= $this->endSection() ?>
