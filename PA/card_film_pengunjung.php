<a href="detail_film_pengunjung.php?id=<?= $row['id']; ?>" 
   class="block bg-white rounded-xl shadow hover:shadow-xl transform transition-transform duration-300 hover:scale-105 overflow-hidden">

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
        <h4 class="text-lg font-bold text-gray-900 truncate"><?= htmlspecialchars($row["judul"]); ?></h4>
        <p class="text-sm text-gray-700"><span class="font-medium">🎬 Sutradara:</span> <?= htmlspecialchars($row["sutradara"]); ?></p>
        <p class="text-sm text-gray-700"><span class="font-medium">🎭 Aktor:</span> <?= htmlspecialchars($row["aktor"]); ?></p>
        <p class="text-sm text-gray-700"><span class="font-medium">🔞 Rating Usia:</span> <?= htmlspecialchars($row["rating_usia"]); ?></p>
        <p class="text-sm text-gray-700"><span class="font-medium">⭐ Rating Film:</span> 
            <?= isset($row['rating_film']) && $row['rating_film'] !== null 
                ? htmlspecialchars($row['rating_film']) 
                : "Belum Tersedia"; ?>
        </p>
    </div>
</a>
