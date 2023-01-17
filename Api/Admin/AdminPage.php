<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">
    <style>
        body {
            font-family: Arial;
        }

        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
        }

        .tab button:hover {
            background-color: #ddd;
        }

        .tab button.active {
            background-color: #ccc;
        }

        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }
    </style>
</head>
<body>

<p>Click on the buttons inside the tabbed menu:</p>

<div class="tab">
    <button class="tablinks" onclick="openTemplate(event, 'Users')">Users</button>
    <button class="tablinks" onclick="openTemplate(event, 'Posts')">Posts</button>
    <button class="tablinks" onclick="openTemplate(event, 'Comments')">Comments</button>
    <button class="tablinks" onclick="openTemplate(event, 'Marks')">Marks</button>
</div>

<div id="Users" class="tabcontent">
    <?= include "UsersTemplate.php" ?>
</div>

<div id="Posts" class="tabcontent">
    <?= include "PostsTemplate.php" ?>

</div>

<div id="Comments" class="tabcontent">
    <?= include "CommentsTemplate.php" ?>

</div>

<div id="Marks" class="tabcontent">
    <?= include "MarksTemplate.php" ?>
</div>

<script>
    function openTemplate(evt, Template) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace("active", "");
        }
        document.getElementById(Template).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>

</body>
</html>



