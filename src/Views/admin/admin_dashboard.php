<?php require_once __DIR__ . '/../layout.php'; ?>
<script src="https://cdn.tailwindcss.com"></script>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Espace Administrateur</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Utilisateurs</h2>
            <p class="text-4xl font-bold"><?= $stats['totalUsers'] ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Projets</h2>
            <p class="text-4xl font-bold"><?= $stats['totalProjects'] ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Rôles</h2>
            <p class="text-4xl font-bold"><?= $stats['totalRoles'] ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h2 class="text-2xl font-bold mb-4">Gestion des utilisateurs</h2>
            <a href="/admin/users" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Gérer les utilisateurs
            </a>
        </div>
        <div>
            <h2 class="text-2xl font-bold mb-4">Gestion des projets</h2>
            <a href="/admin/projects" class="inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Gérer les projets
            </a>
        </div>
    </div>
</div>