<?php
if (!file_exists('deploy.config.php')) {
    die('No existe configuración');
} else {
    require_once 'deploy.config.php';
}
if (!defined('SECRET_ACCESS_TOKEN')) {
    define('SECRET_ACCESS_TOKEN', 'clavesupersecreta');
}

if (!isset($_GET['sat']) ||
        $_GET['sat'] !== SECRET_ACCESS_TOKEN ||
        SECRET_ACCESS_TOKEN === 'clavesupersecreta') {
    header('HTTP/1.0 403 Forbidden');
    echo 'No tienes permiso para entrar aquí!';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = json_decode(file_get_contents('php://input'), true);
    if ($payload === false || $payload['ref'] !== 'refs/heads/' . BRANCH) {
        echo 'No hay nada que subir';
        exit;
    } else {
        fastcgi_finish_request();
    }
}
/**
 * GIT DEPLOYMENT SCRIPT
 *
 * Used for automatically deploying websites via github or bitbucket, more deets here:
 *
 * 		https://gist.github.com/1809044
 */
// The commands
$commands = array(
    'echo $PWD',
    'whoami',
    'git pull',
    'git status',
    'php ../app/console assets:install --symlink web',
    'php ../app/console assetic:dump --env=prod',
);
if (isset($_GET['install']) && $_GET['install'] === 'si') {
    $commands[] = 'composer install --no-dev -d ..';
}

// Run the commands for output
$output = '';
foreach ($commands AS $command) {
// Run it
    $tmp = shell_exec($command);
// Output
    $output .= "<span style=\"color: #6BE234;\">\$</span> <span style=\"color: #729FCF;\">{$command}\n</span>";
    $output .= htmlentities(trim($tmp)) . "\n";
}

// Make it pretty for manual user access (and why not?)
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>GIT DEPLOYMENT SCRIPT</title>
    </head>
    <body style="background-color: #000000; color: #FFFFFF; font-weight: bold; padding: 0 10px;">
        <pre>
 .  ____  .    ____________________________
 |/      \|   |                            |
[| <span style="color: #FF0000;">&hearts;    &hearts;</span> |]  | Git Deployment Script v0.1 |
 |___==___|  /              &copy; oodavid 2012 |
              |____________________________|
 
            <?php echo $output; ?>
        </pre>
    </body>
</html>