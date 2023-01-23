<div class="container">
    <div class="row">
        <div class="col mt-1">
            <table class="table shadow ">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>UserId</th>
                    <th>PostId</th>
                    <th>Content</th>
                    <th>Liked</th>
                    <th>Date</th>
                </tr>
                <?php

                use Api\Models\Repositories\CommentRepository;

                $result = CommentRepository::getCommentsForAdmin();
                foreach ($result as $value) { ?>
                    <tr>
                        <td><?= $value['id'] ?></td>
                        <td><?= $value['user_id'] ?></td>
                        <td><?= $value['post_id'] ?></td>
                        <td><?= $value['content'] ?></td>
                        <td><?= $value['liked'] ?></td>
                        <td><?= $value['date'] ?></td>
                    </tr> <?php } ?>
                </thead>
            </table>
        </div>
    </div>
</div>
