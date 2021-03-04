<?php foreach ($editoriales['editoriales'] as $editorial => $subtotal){ ?>
    <iframe src="localhost/uppl/backend/web/pagos/<?=strtolower($editorial)?>?subtotal=<?=$subtotal?>" frameborder="0"></iframe>
<?php } ?>