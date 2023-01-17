<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">
</head>
<div class="container">
    <div class="row">
        <div class="col mt-1">
            <table class="table shadow ">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>UserID</th>
                    <th>PostID</th>
                    <th>Date</th>
                </tr>
                <?php

                use Api\Models\Repositories\MarkRepository;

                $result = MarkRepository::getMarksForAdmin();
                foreach ($result as $value) { ?>
                    <tr>
                        <td><?= $value['mark_id'] ?></td>
                        <td><?= $value['user_id'] ?></td>
                        <td><?= $value['post_id'] ?></td>
                        <td><?= $value['mark_date'] ?></td>
                    </tr> <?php } ?>
                </thead>
            </table>
        </div>
    </div>
</div>
