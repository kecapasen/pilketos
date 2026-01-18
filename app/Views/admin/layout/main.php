<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?> - E-Voting</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 'baloo': ['"Baloo 2"', 'cursive'] },
                    colors: {
                        primary: '#5f4e78',
                        accent: '#e3882d',
                        surface: '#fffefa',
                        base: '#fbf3e8',
                        input: '#fdfdfd',
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Baloo 2', sans-serif; }
        body { background-color: #fbf3e8; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Mobile Sidebar Animation */
        #mobileSidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        #mobileSidebar.open {
            transform: translateX(0);
        }
        #sidebarBackdrop {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease-in-out;
        }
        #sidebarBackdrop.open {
            opacity: 1;
            pointer-events: auto;
        }
    </style>
</head>
<body class="bg-base text-primary min-h-screen flex flex-col lg:flex-row">
    
    <!-- Mobile Header -->
    <header class="lg:hidden sticky top-0 z-40 bg-surface border-b border-primary p-4 flex items-center justify-between">
        <button onclick="openSidebar()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-primary/10 border border-primary text-primary hover:bg-primary hover:text-surface transition-all">
            <i class="bi bi-list text-xl"></i>
        </button>
        <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary border-primary border">
                <i class="bi bi-award-fill text-sm"></i>
            </div>
            <span class="text-lg font-bold tracking-tight text-primary">
                E-<span class="text-accent">Voting</span>
            </span>
        </a>
    </header>
    
    <!-- Desktop Sidebar -->
    <aside class="hidden lg:block sticky top-0 h-screen flex-shrink-0">
        <?= view('layout/sidebar') ?>
    </aside>
    
    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" onclick="closeSidebar()" class="fixed inset-0 z-50 bg-black/50 lg:hidden"></div>
    
    <!-- Mobile Sidebar -->
    <aside id="mobileSidebar" class="fixed top-0 left-0 h-full z-50 lg:hidden">
        <?= view('layout/sidebar') ?>
    </aside>
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen lg:min-h-0">
        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto bg-base">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
    
    <script>
        function openSidebar() {
            document.getElementById('mobileSidebar').classList.add('open');
            document.getElementById('sidebarBackdrop').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebar() {
            document.getElementById('mobileSidebar').classList.remove('open');
            document.getElementById('sidebarBackdrop').classList.remove('open');
            document.body.style.overflow = '';
        }
        
        // Close sidebar when clicking a link (for mobile UX)
        document.querySelectorAll('#mobileSidebar a').forEach(link => {
            link.addEventListener('click', closeSidebar);
        });
        
        // Close sidebar on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSidebar();
        });
    </script>
</body>
</html>
