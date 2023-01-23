<div class="container">
    <div class="row">
        <div class="col mt-1">
            <table class="table shadow ">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>UserId</th>
                    <th>Title</th>
                </tr>
                <?php

                use Api\Models\Repositories\PostRepository;

                $result = PostRepository::getPostsForAdmin();
                foreach ($result as $value) { ?>
                    <tr>
                        <td><?= $value['id'] ?></td>
                        <td><?= $value['user_id'] ?></td>
                        <td><?= $value['title'] ?></td
                    </tr> <?php } ?>
                </thead>
            </table>
        </div>
    </div>
</div>
