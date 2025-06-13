<a href="detail_film_admin.php?id=<?= $row['id']; ?>" 
   class="block bg-white rounded-lg shadow-md hover:shadow-lg transition-transform transform hover:scale-105 duration-300 overflow-hidden">

    <?php if (!empty($row["gambar"])) : ?>
        <img src="gambar_film/<?= htmlspecialchars($row["gambar"]); ?>" 
             alt="Poster <?= htmlspecialchars($row["judul"]); ?>" 
             class="w-full h-[400px] object-cover">
    <?php else : ?>
        <img src="placeholder.jpg" 
             alt="Tidak ada gambar" 
             class="w-full h-[400px] object-cover bg-gray-200">
    <?php endif; ?>

    <div class="p-4 space-y-1">
        <h4 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($row["judul"]); ?></h4>
        <p class="text-sm text-gray-600"><span class="font-medium">🎬 Sutradara:</span> <?= htmlspecialchars($row["sutradara"]); ?></p>
        <p class="text-sm text-gray-600"><span class="font-medium">🎭 Aktor:</span> <?= htmlspecialchars($row["aktor"]); ?></p>
        <p class="text-sm text-gray-600"><span class="font-medium">🔞 Rating Usia:</span> <?= htmlspecialchars($row["rating_usia"]); ?></p>
        <p class="text-sm text-gray-600"><span class="font-medium">⭐ Rating Film:</span> 
            <?= isset($row['rating_film']) && $row['rating_film'] !== null 
                ? htmlspecialchars($row['rating_film']) 
                : "Belum Tersedia"; ?>
        </p>
    </div>
</a>
