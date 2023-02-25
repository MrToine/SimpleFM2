<?php
chdir(dirname(__DIR__));

require 'vendor/autoload.php';

// On initialise nos objets
$pdo = new PDO(
    'mysql:host=localhost;dbname=simplefm2',
    'root',
    '',
    [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
);
$categoryModel = new \App\News\Models\CategoryModel($pdo);
$postTable = new \App\News\Models\NewsModel($pdo);
$textHelper = new \Utils\TwigTextExtension();

// La page curante
$page = $_GET['p'] ?? 1;
$title = 'Bienvenue sur le blog';
if ($page  > 1) {
    $title .= ', page ' . $page;
}

// On récupère nos articles
$chunk = new Twig\Extension\CoreExtension();
$categories = $categoryModel->findAll();
$posts = $postTable->findPaginatedPublic(12, $page);
$postsBatches = twig_array_batch($posts, 4);

?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css"
          integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
    <style>
        body {
            padding-top: 5rem;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-fixed-top navbar-dark bg-inverse">
    <a class="navbar-brand" href="#">Mon super site</a>
    <ul class="nav navbar-nav">
        <li class="nav-item active">
            <a class="nav-link" href="/demo.php">Blog</a>
        </li>
    </ul>
</nav>

<div class="container">


    <h1><?= $title ?></h1>

    <div class="row">
        <div class="col-md-9">

            <div class="row">

                <?php foreach ($postsBatches as $postBatch): ?>
                <div class="card-deck">
                    <?php foreach ($postBatch as $post): ?>
                    <div class="card">
                        <?php if($post->category_name): ?>
                        <div class="card-header"><?= htmlentities($post->category_name) ?></div>
                        <?php endif ?>
                        <div class="card-block">
                            <h4 class="card-title">
                                <a href="/blog/<?= $post->slug ?>-<?= $post->id ?>">
                                    <?= htmlentities($post->name) ?>
                                </a>
                            </h4>
                            <p class="card-text">
                                <?= nl2br($textHelper->slice($post->content)); ?>
                            </p>
                            <p class="text-muted">
                                
                            </p>
                        </div>
                        <div class="card-footer">
                            <a href="/blog/<?= $post->slug ?>-<?= $post->id ?>" class="btn btn-primary">
                                Voir l'article
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <?php
            $view = new \Pagerfanta\View\TwitterBootstrap4View();
            echo $view->render($posts, function (int $page) {
                if ($page > 1) {
                    $params = array_merge(['p' => $page], $_GET);
                } else {
                    $params = $_GET;
                }
                return '/blog?' . http_build_query($params);
            });
            ?>

        </div>
        <div class="col-md-3">
            <ul class="list-group">
                <?php foreach($categories as $category): ?>
                <li class="list-group-item">
                    <a style="color:inherit;" href="/blog/category/<?= $category->slug; ?>"><?= $category->name ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

</div><!-- /.container -->

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js"
        integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/timeago.js/3.0.2/timeago.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/timeago.js/3.0.2/timeago.locales.min.js"></script>
<script>
  timeago().render(document.querySelectorAll('.timeago'), 'fr')
</script>
</body>
</html>
