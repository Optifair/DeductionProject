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
                    <th>Actions</th>
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
                        <td>
                            <button onсlick="<?= MarkRepository::deleteMark($value['mark_id']) ?>">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr> <?php } ?>
                </thead>
            </table>
        </div>
    </div>
</div>
