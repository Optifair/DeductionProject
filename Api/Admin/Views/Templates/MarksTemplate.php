<div style="overflow:scroll">
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
                            <a href="?edit=<?= $value['mark_id'] ?>" class="btn btn-success btn-sm" data-toggle="modal"
                               data-target="#editModal<?= $value['mark_id'] ?>"><i class="fa fa-edit"></i></a>
                            <a href="?delete=<?= $value['mark_id'] ?>" class="btn btn-danger btn-sm" data-toggle="modal"
                               data-target="#deleteModal<?= $value['mark_id'] ?>"><i class="fa fa-trash"></i></a>

                            <!-- Modal Edit-->
                            <div class="modal fade" id="editModal<?= $value['mark_id'] ?>" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content shadow">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit mark
                                                № <?= $value['mark_id'] ?>?</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/admin/editMark" method="post">
                                                <div class="form-group">
                                                    <label>
                                                        Mark id
                                                        <input name="mark_id" type="text" class="form-control"
                                                               value="<?= $value['mark_id'] ?>" readonly>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        User id
                                                        <input name="user_id" type="text" class="form-control"
                                                               value="<?= $value['user_id'] ?>">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Post id
                                                        <input name="post_id" type="text" class="form-control"
                                                               value="<?= $value['post_id'] ?>">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Mark Date
                                                        <input name="mark_date" type="text" class="form-control"
                                                               value="<?= $value['mark_date'] ?>">
                                                    </label>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" name="edit-submit" class="btn btn-primary">
                                                        Edit
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Delete-->
                            <div class="modal fade" id="deleteModal<?= $value['mark_id'] ?>" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content shadow">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Delete mark
                                                ID № <?= $value['mark_id'] ?>?</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="/admin/deleteMark?id=<?= $value['mark_id'] ?>" method="post">
                                                <button type="submit" name="delete_submit" class="btn btn-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr> <?php } ?>
                </thead>
            </table>
        </div>
    </div>
</div>
