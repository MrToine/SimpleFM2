<?= $renderer->render('header') ?>

<h1>Actus</h1>
<ul>
    <li><a href="<?= $router->generateUri('news.view', ['slug' => 'je-suis-un-test']); ?>">Article 1</a></li>
    <li>article 2</li>
    <li>article 3</li>
    <li>article 4</li>
    <li>article 5</li>
</ul>

<?= $renderer->render('footer') ?>
