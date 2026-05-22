<?php

require BASE_PATH . '/view/layout/header.php'; 

use View\ItemView;
?>

<div class="section catalog page">
	<div class="wrapper">

		<h1>
<?php

$isSearching = !empty($search);
$hasSection  = !empty($section);

$title = $isSearching
    ? 'Search results for "' . htmlspecialchars($search) . '"'
    : htmlspecialchars($pageTitle);

if ($hasSection) {

    $title .= $isSearching
        ? ' in ' . ucfirst($section)
        : " <a href='index.php?page=catalog'>Full Catalog</a> &gt; ";
}

echo $title;
?>
		</h1>

<?php if (empty($catalog)): ?>

	<?php if (!empty($section) && !empty($catalog)): ?>

				<p>You are searching in the wrong section. Please check again.</p>

				<p>
					<a href="index.php?page=catalog&s=<?= urlencode($search) ?>">
						Search in the Full Catalog
					</a>
				</p>

			<?php else: ?>

				<p>No items were found matching that search term.</p>

				<p>
					Search again or
					<a href="index.php?page=catalog">Browse the Full Catalog.</a>
				</p>

			<?php endif; ?>

		<?php else: ?>

			<?php require BASE_PATH . '/view/partials/pagination.php'; ?>

			<ul class="catalog">
				<?php foreach ($catalog as $item): ?>
					<?= ItemView::render($item); ?>
				<?php endforeach; ?>
			</ul>

			<?php require BASE_PATH . '/view/partials/pagination.php'; ?>

		<?php endif; ?>

	</div>
</div>

<?php require BASE_PATH . '/view/layout/footer.php'; ?>
