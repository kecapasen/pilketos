<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<?php
    // Ensure values are integers to avoid NaN
    $total_voters = (int)($total_voters ?? 0);
    $already_voted = (int)($already_voted ?? 0);
    $total_candidates = (int)($total_candidates ?? 0);
    $not_voted = $total_voters - $already_voted;
    $vote_percentage = $total_voters > 0 ? round(($already_voted / $total_voters) * 100, 1) : 0;

    // Color mapping like in example files
    $colorMap = [
        'primary' => 'bg-primary/10 text-primary border-primary',
        'warning' => 'bg-accent/10 text-accent border-accent',
        'success' => 'bg-green-600/10 text-green-600 border-green-600',
        'danger'  => 'bg-red-600/10 text-red-600 border-red-600',
    ];
    
    // Get Pusher config
    $pusherConfig = config('Pusher');
?>

<div class="w-full p-4 lg:p-8">
    <!-- Page Header -->
    <?= view('components/page_header', [
        'title'    => 'Dashboard',
        'subtitle' => 'Selamat datang, ' . esc($admin_name ?? 'Admin') . '! Berikut ringkasan data pemilihan.',
    ]) ?>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
        <div data-aos="fade-up" data-aos-delay="100">
            <?= view('components/stat_card', [
                'title'   => 'Total Pemilih',
                'value'   => $total_voters,
                'icon'    => 'bi-person-vcard-fill',
                'variant' => 'primary',
                'trend'   => '<i class="bi bi-people-fill me-1"></i> Terdaftar',
            ]) ?>
        </div>
        <div data-aos="fade-up" data-aos-delay="200">
            <?= view('components/stat_card', [
                'title'   => 'Sudah Memilih',
                'value'   => '<span id="alreadyVoted">' . $already_voted . '</span>',
                'icon'    => 'bi-check-circle-fill',
                'variant' => 'success',
                'trend'   => '<i class="bi bi-bar-chart-fill me-1"></i> <span id="votePercentage">' . $vote_percentage . '</span>% partisipasi',
            ]) ?>
        </div>
        <div data-aos="fade-up" data-aos-delay="300">
            <?= view('components/stat_card', [
                'title'   => 'Belum Memilih',
                'value'   => '<span id="notVoted">' . $not_voted . '</span>',
                'icon'    => 'bi-hourglass-split',
                'variant' => 'warning',
                'trend'   => '<i class="bi bi-clock-fill me-1"></i> Menunggu voting',
            ]) ?>
        </div>
        <div data-aos="fade-up" data-aos-delay="400">
            <?= view('components/stat_card', [
                'title'   => 'Total Kandidat',
                'value'   => $total_candidates,
                'icon'    => 'bi-award-fill',
                'variant' => 'danger',
                'trend'   => '<i class="bi bi-person-check-fill me-1"></i> Terdaftar',
            ]) ?>
        </div>
    </div>

    <!-- Quick Count Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Vote Results Chart -->
        <div class="lg:col-span-2 bg-surface rounded-2xl border border-primary overflow-hidden" data-aos="fade-up" data-aos-delay="500">
            <div class="p-4 border-b border-primary bg-input flex justify-between items-center">
                <h3 class="font-bold text-primary text-sm uppercase tracking-wider">
                    <i class="bi bi-bar-chart-fill me-2"></i>Quick Count Real-time
                </h3>
                <div class="flex items-center gap-2">
                    <span id="connectionStatus" class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                    </span>
                    <span id="connectionText" class="text-xs text-yellow-600 font-bold">CONNECTING...</span>
                </div>
            </div>
            <div class="p-6">
                <div id="voteResultsContainer" class="space-y-6">
                    <?php 
                    $variantColors = ['primary', 'warning', 'success', 'danger'];
                    foreach($vote_results as $index => $result): 
                        $variant = $variantColors[$index % count($variantColors)];
                        $colorStyle = $colorMap[$variant];
                        $totalVotes = (int) $result->total_votes;
                        $percentage = $already_voted > 0 ? round(($totalVotes / $already_voted) * 100, 1) : 0;
                    ?>
                    <div class="vote-result-item" data-candidate-id="<?= $result->id ?>">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold border <?= $colorStyle ?>">
                                    <?= $result->order_number ?>
                                </div>
                                <div>
                                    <h4 class="font-bold text-primary text-sm"><?= esc($result->chairman_name) ?></h4>
                                    <span class="text-xs text-primary/60">Paslon <?= $result->order_number ?></span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="vote-count text-2xl font-bold text-primary"><?= $totalVotes ?></span>
                                <span class="text-xs text-primary/60 block">suara</span>
                            </div>
                        </div>
                        <div class="w-full bg-primary/10 border border-primary rounded-full h-3 overflow-hidden">
                            <div class="vote-bar bg-primary h-3 rounded-full transition-all duration-500" 
                                 style="width: <?= $percentage ?>%"></div>
                        </div>
                        <span class="text-xs font-bold text-primary vote-percentage-label"><?= $percentage ?>%</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="p-4 border-t border-primary flex justify-between items-center">
                <small class="text-primary font-medium">
                    <i class="bi bi-broadcast me-1"></i> Pusher WebSocket
                </small>
                <span id="lastUpdate" class="text-xs text-primary/60">Menunggu update...</span>
            </div>
        </div>

        <!-- Recent Voters -->
        <div class="bg-surface rounded-2xl border border-primary overflow-hidden h-fit" data-aos="fade-up" data-aos-delay="600">
            <div class="p-4 border-b border-primary bg-input flex justify-between items-center">
                <h3 class="font-bold text-primary text-sm uppercase tracking-wider">
                    <i class="bi bi-clock-fill me-2"></i>Aktivitas Terbaru
                </h3>
                <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary border border-primary rounded-full px-3 py-1 text-xs font-bold">
                    <i class="bi bi-activity"></i> Live
                </span>
            </div>
            <div id="recentVotersContainer" class="divide-y divide-primary/10">
                <?php 
                $db = \Config\Database::connect();
                $recentVoters = $db->table('voters')
                    ->select('voters.name, votes.voted_at')
                    ->join('votes', 'votes.voter_id = voters.id')
                    ->orderBy('votes.voted_at', 'DESC')
                    ->limit(5)
                    ->get()
                    ->getResult();
                
                if(count($recentVoters) > 0):
                    foreach($recentVoters as $voter):
                ?>
                <div class="p-4 hover:bg-input transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-600/10 border border-green-600 rounded-full flex items-center justify-center">
                            <i class="bi bi-check-lg text-green-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h6 class="font-bold text-primary text-sm truncate"><?= esc($voter->name) ?></h6>
                            <span class="text-xs text-primary/60"><?= date('H:i', strtotime($voter->voted_at)) ?></span>
                        </div>
                    </div>
                </div>
                <?php 
                    endforeach;
                else:
                ?>
                <div class="p-4 text-center text-primary/60 text-sm">
                    <i class="bi bi-hourglass-split me-1"></i> Belum ada yang memilih
                </div>
                <?php endif; ?>
            </div>
            <div class="bg-input p-4 border-t border-primary">
                <a href="<?= base_url('admin/voters') ?>" class="text-xs text-primary font-bold flex items-center justify-center gap-1 hover:text-accent transition-colors">
                    Lihat Semua Pemilih <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Pusher JS -->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    // Enable Pusher logging for debugging (set to false for production)
    Pusher.logToConsole = false;

    const pusher = new Pusher('<?= esc($pusherConfig->key) ?>', {
        cluster: '<?= esc($pusherConfig->cluster) ?>'
    });

    const channel = pusher.subscribe('voting-channel');
    
    // Connection state
    pusher.connection.bind('connected', function() {
        document.getElementById('connectionStatus').innerHTML = `
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
        `;
        document.getElementById('connectionText').textContent = 'LIVE';
        document.getElementById('connectionText').className = 'text-xs text-green-600 font-bold';
    });
    
    pusher.connection.bind('disconnected', function() {
        document.getElementById('connectionStatus').innerHTML = `
            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
        `;
        document.getElementById('connectionText').textContent = 'OFFLINE';
        document.getElementById('connectionText').className = 'text-xs text-red-600 font-bold';
    });

    // Listen for vote updates
    channel.bind('vote-updated', function(data) {
        // Update stat cards
        document.getElementById('alreadyVoted').textContent = data.already_voted;
        document.getElementById('notVoted').textContent = data.not_voted;
        document.getElementById('votePercentage').textContent = data.vote_percentage;
        
        // Update vote results with animation
        data.vote_results.forEach(result => {
            const item = document.querySelector(`[data-candidate-id="${result.id}"]`);
            if (item) {
                const countEl = item.querySelector('.vote-count');
                const prevCount = parseInt(countEl.textContent);
                
                // Animate if vote increased
                if (result.total_votes > prevCount) {
                    item.classList.add('bg-green-50');
                    setTimeout(() => item.classList.remove('bg-green-50'), 1000);
                }
                
                countEl.textContent = result.total_votes;
                item.querySelector('.vote-bar').style.width = result.percentage + '%';
                item.querySelector('.vote-percentage-label').textContent = result.percentage + '%';
            }
        });
        
        // Update recent voters
        if (data.recent_voters && data.recent_voters.length > 0) {
            let html = '';
            data.recent_voters.forEach((voter, index) => {
                const isNew = index === 0 ? 'animate-pulse' : '';
                html += `
                    <div class="p-4 hover:bg-input transition-colors ${isNew}">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-600/10 border border-green-600 rounded-full flex items-center justify-center">
                                <i class="bi bi-check-lg text-green-600 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h6 class="font-bold text-primary text-sm truncate">${voter.name}</h6>
                                <span class="text-xs text-primary/60">${voter.voted_at}</span>
                            </div>
                        </div>
                    </div>
                `;
            });
            document.getElementById('recentVotersContainer').innerHTML = html;
        }
        
        // Update timestamp
        document.getElementById('lastUpdate').textContent = 'Update: ' + data.timestamp;
        
        // Play notification sound (optional)
        // new Audio('/notification.mp3').play();
    });
</script>

<?= $this->endSection() ?>
