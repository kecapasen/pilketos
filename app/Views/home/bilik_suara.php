<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="w-full p-4 sm:p-6 md:p-8">
    <!-- Page Header -->
    <div class="flex flex-col items-center text-center mb-6 sm:mb-8 md:mb-10" data-aos="fade-down">
        <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-primary/10 border border-primary rounded-full flex items-center justify-center mb-3 md:mb-4">
            <i class="bi bi-award-fill text-xl sm:text-2xl text-primary"></i>
        </div>
        <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-primary uppercase tracking-wider mb-1">Bilik Suara</h2>
        <p class="text-xs sm:text-sm text-primary/60">Tentukan masa depan sekolahmu. Pilih dengan bijak!</p>
    </div>

    <!-- Candidates Container -->
    <div class="bg-surface rounded-2xl border border-primary overflow-hidden max-w-6xl mx-auto" data-aos="fade-up" data-aos-delay="100">
        <!-- Table Header -->
        <div class="p-3 sm:p-4 border-b border-primary bg-input flex justify-between items-center">
            <h3 class="font-bold text-primary text-sm uppercase tracking-wider">
                <i class="bi bi-people-fill me-2"></i>Daftar Kandidat
            </h3>
            <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary border border-primary rounded-full px-3 py-1 text-xs font-bold">
                <i class="bi bi-person-vcard-fill"></i> <?= count($candidates) ?> Paslon
            </span>
        </div>

        <!-- Candidates Grid -->
        <div class="p-3 sm:p-4 md:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-<?= min(count($candidates), 3) ?> gap-4 sm:gap-5 md:gap-6">
                <?php foreach ($candidates as $index => $candidate) : ?>
                <div class="bg-input rounded-2xl border border-primary overflow-hidden hover:-translate-y-1 transition-all duration-300 group flex flex-col h-full" 
                     data-aos="zoom-in" data-aos-delay="<?= ($index + 1) * 150 ?>">
                    
                    <!-- Candidate Photo -->
                    <div class="relative aspect-[4/3] sm:aspect-square overflow-hidden">
                        <img src="<?= base_url('uploads/candidates/' . $candidate->image) ?>" 
                             alt="Foto Paslon <?= $candidate->order_number ?>" 
                             class="w-full h-full object-cover object-center transition duration-500 group-hover:scale-110">
                        
                        <!-- Number Overlay -->
                        <div class="absolute top-2 left-2 sm:top-3 sm:left-3">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-accent border-2 border-surface rounded-full flex items-center justify-center">
                                <span class="text-lg sm:text-xl font-bold text-white"><?= $candidate->order_number ?></span>
                            </div>
                        </div>
                        
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-transparent to-transparent"></div>
                        
                        <!-- Name on Image -->
                        <div class="absolute bottom-0 left-0 right-0 p-3 sm:p-4 text-white">
                            <h4 class="font-bold text-base sm:text-lg leading-tight"><?= $candidate->chairman_name ?></h4>
                            <span class="text-[10px] sm:text-xs text-white/80"><?= $candidate->chairman_class ?></span>
                            <?php if($candidate->vice_chairman_name): ?>
                                <span class="text-white/60 mx-1">&</span>
                                <span class="text-xs sm:text-sm font-medium"><?= $candidate->vice_chairman_name ?></span>
                                <span class="text-[10px] sm:text-xs text-white/80 ml-1"><?= $candidate->vice_chairman_class ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Visi Misi -->
                    <div class="p-3 sm:p-4 border-t border-primary flex-grow flex flex-col">
                        <div class="text-sm mb-4">
                            <div class="mb-2">
                                <span class="text-xs font-bold text-primary uppercase tracking-wider flex items-center gap-1 mb-1">
                                    <i class="bi bi-lightbulb-fill text-primary"></i> Visi
                                </span>
                                <p class="text-primary italic text-xs">"<?= $candidate->vision ?>"</p>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-primary uppercase tracking-wider flex items-center gap-1 mb-1">
                                    <i class="bi bi-list-check text-primary"></i> Misi
                                </span>
                                <div class="text-primary text-xs pl-2 border-l-2 border-primary">
                                    <?= nl2br($candidate->mission) ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vote Button -->
                        <div class="mt-auto">
                            <button type="button" 
                                    onclick="openConfirmModal(<?= $candidate->id ?>, <?= $candidate->order_number ?>, '<?= esc($candidate->chairman_name) ?>')"
                                    class="w-full inline-flex items-center justify-center font-bold rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 gap-2 px-4 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm bg-transparent border border-primary text-primary hover:bg-primary hover:text-surface hover:-translate-y-1">
                                <i class="bi bi-check2-circle"></i>
                                COBLOS NO <?= $candidate->order_number ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-input p-3 sm:p-4 border-t border-primary">
            <p class="text-[11px] text-primary text-center flex items-center justify-center gap-1.5">
                <i class="bi bi-shield-lock-fill text-primary"></i>
                Suara Anda bersifat rahasia dan tidak dapat diubah setelah memilih.
            </p>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-surface rounded-2xl border border-primary w-full max-w-md overflow-hidden" data-aos="zoom-in">
        <!-- Modal Header -->
        <div class="p-4 border-b border-primary bg-input text-center">
            <div class="w-16 h-16 bg-primary/10 border border-primary rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="bi bi-exclamation-circle-fill text-2xl text-primary"></i>
            </div>
            <h3 class="font-bold text-primary text-lg uppercase tracking-wider">Konfirmasi Pilihan</h3>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 text-center">
            <p class="text-primary mb-2">Anda akan memilih:</p>
            <div class="bg-input rounded-xl border border-primary p-4 mb-4">
                <div class="w-14 h-14 bg-accent border-2 border-primary rounded-full flex items-center justify-center mx-auto mb-2">
                    <span id="modalCandidateNumber" class="text-2xl font-bold text-white">1</span>
                </div>
                <h4 id="modalCandidateName" class="font-bold text-primary text-lg">Nama Kandidat</h4>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-xl p-3 mb-4">
                <p class="text-red-600 text-sm font-medium flex items-center justify-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Pilihan tidak dapat diubah setelah dikonfirmasi!
                </p>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="p-4 border-t border-primary bg-input flex gap-3">
            <form id="voteForm" action="<?= base_url('vote') ?>" method="post" class="flex gap-3 w-full">
                <?= csrf_field() ?>
                <input type="hidden" name="candidate_id" id="modalCandidateId" value="">
                
                <?= view('components/ui/button', [
                    'type'    => 'button',
                    'label'   => 'Batal',
                    'icon'    => 'bi-x-circle',
                    'variant' => 'outline',
                    'class'   => 'flex-1',
                    'onclick' => 'closeConfirmModal()',
                ]) ?>
                
                <?= view('components/ui/button', [
                    'type'    => 'submit',
                    'label'   => 'Ya, Coblos!',
                    'icon'    => 'bi-check2-circle',
                    'variant' => 'primary',
                    'class'   => 'flex-1',
                ]) ?>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('confirmModal');
    const modalCandidateId = document.getElementById('modalCandidateId');
    const modalCandidateNumber = document.getElementById('modalCandidateNumber');
    const modalCandidateName = document.getElementById('modalCandidateName');

    function openConfirmModal(candidateId, orderNumber, chairmanName) {
        modalCandidateId.value = candidateId;
        modalCandidateNumber.textContent = orderNumber;
        modalCandidateName.textContent = chairmanName;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeConfirmModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeConfirmModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeConfirmModal();
        }
    });
</script>

<?= $this->endSection() ?>