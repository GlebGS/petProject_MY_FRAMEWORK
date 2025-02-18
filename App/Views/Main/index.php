<html>
    <head>
        <title><?= $this->meta["title"]; ?></title>
    </head>
    <body>
        
        <h1><?= $this->meta["title"]; ?></h1>
        
        <?= $title; ?>
            <br>
        <?= $body; ?>
            <br>
        <?= $footer; ?>
        
            
            <?= $this->getDbLogs(); ?>
    </body>
</html>
