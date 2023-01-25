<?php
    session_start();
    require_once('func.php');
    require_once('db.php');
    require_once('user.php');

    if (!findInSession('user')) {
        $_SESSION['error'] = 'Вы незалогинены!';
        header('Location: index.php');
    }

    try {
        $user = Person::loadFromDb(findInSession('user')['username']);
    } catch (\Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: logout.php');
    }


if (isset($_POST['user'])) {
    if (isset($_FILES['user'])) {
        ImageService::uploadImage($_FILES['user'], $user);
    }
    $user
        ->edit($_POST['user'])
        ->save()
    ;
    header('Location: profile.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>STEP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row">
    <div class="col">

	    <div class="error">
		    <span><?php echo findAndDelete('error'); ?></span>
        </div>
        <img src="<?= $user->getImage();?>" style="width: 200px; height: 100px;">
    
        <form method="POST" enctype="multipart/form-data">
            <input class="form-control" name="user[username]" type="text" placeholder="Username" value="<?= $user->getUsername();?>">
            <input class="form-control" name="user[password]" type="text" placeholder="Password">
            <input class="form-control" name="user[email]" type="text" placeholder="Email" value="<?= $user->email;?>">
            <input class="form-control" name="user[location]" type="text" placeholder="Location" value="<?= $user->location;?>">
            <input class="form-control" name="user[link]" type="text" placeholder="Link" value="<?= $user->link;?>">
            <input class="form-control" name="user[image]" type="file" placeholder="Image">
            <input class="btn btn-primary" type="submit" value="Save">
        </form>
            <a class="btn btn-danger" id="delete_user" href="delete.php">Удалить пользователя</a>
    </div>
    <div class="col">
        <?php if (isset(findInSession('user')['username'])) { ?>
            <span><?= 'Username: '. findInSession('user')['username']; ?></span>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        <?php }?>
    </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script lang="javascript">
    let btn = document.getElementById('delete_user');
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        if (confirm('Удалить пользователя?')) {
            window.location = btn.getAttribute('href');
        }
    });
</script>
</body>
</html>

