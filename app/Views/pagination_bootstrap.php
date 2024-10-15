<?php
/**
 * @var \CodeIgniter\Pager\PagerRenderer $pager  Instance de la classe de pagination
 * @var array $link                              Information d'un lien vers une page (créée par l'instruction foreach)
 */

// Définir combien de numéro de page à afficher de chaque côté du numéro de la page courante
$pager->setSurroundCount(2);
?>

<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
    <ul class="pagination justify-content-center">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>" class="page-link">
                    <span aria-hidden="true"><?= lang('Pager.first') ?></span>
                </a>
            </li>

            <li>
                <a href="<?= $pager->getPreviousPage() ?>" aria-label="<?= lang('Pager.previous') ?>" class="page-link">
                    <span aria-hidden="true"><?= lang('Pager.previous') ?></span>
                </a>
            </li>

            <li class="page-item">
                <a href="<?= $pager->getPrevious() ?>" class="page-link">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li class="<?= $link['active'] ? 'page-item active' : 'page-item' ?>">
                <a href="<?= $link['uri'] ?>" class="page-link">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getNext() ?>" class="page-link">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>

            <li>
            <a href="<?= $pager->getNextPage() ?>" aria-label="<?= lang('Pager.next') ?>" class="page-link">
                <span aria-hidden="true"><?= lang('Pager.next') ?></span>
                </a>
            </li>
            
            <li class="page-item">
                <a href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>" class="page-link">
                    <span aria-hidden="true"><?= lang('Pager.last') ?></span>
                </a>
            </li>
        <?php endif ?>
    </ul>
</nav>