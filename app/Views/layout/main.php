<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'E-Voting OSIS' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5f4e78',
                        accent: '#e3882d',
                        surface: '#fffefa',
                        base: '#fbf3e8',
                        input: '#fdfdfd',
                    },
                    fontFamily: {
                        sans: ['"Baloo 2"', 'cursive', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Baloo 2', cursive; background-color: #fbf3e8; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-base text-primary min-h-screen flex flex-col">
    <nav class="bg-surface py-3 sm:py-4 px-4 sm:px-6 border-b border-primary sticky top-0 z-50">
        <div class="max-w-6xl mx-auto flex justify-between items-center gap-4">
            <h1 class="text-lg sm:text-xl md:text-2xl font-bold tracking-wide text-primary">PILKETOS</h1>
            <?php if(session()->get('is_voter_logged_in')): ?>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs sm:text-sm text-gray-500 font-semibold leading-none">Halo,</p>
                    <p class="font-bold text-primary text-sm md:text-lg leading-none truncate sm:max-w-[200px] md:max-w-none"><?= session()->get('voter_name') ?></p>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    <main class="flex-grow container mx-auto px-3 sm:px-4 py-4 sm:py-6 md:py-8 flex flex-col justify-center items-center">
        <?php if(session()->getFlashdata('error')): ?>
            <div class="max-w-md mx-auto mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                <p class="font-bold">Gagal!</p>
                <p><?= session()->getFlashdata('error') ?></p>
            </div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </main>
    <footer class="bg-primary text-white text-center py-3 sm:py-4 mt-auto px-4">
        <p class="text-xs sm:text-sm">© <?= date('Y') ?> OSIS Smansa. Built with ❤️ & CodeIgniter 4.</p>
    </footer>
</body>
</html>