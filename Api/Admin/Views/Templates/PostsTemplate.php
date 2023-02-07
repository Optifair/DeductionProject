<div style="overflow:scroll">
    <div class="row">
        <div class="col mt-1">
            <table class="table shadow ">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>UserId</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Image</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                <?php

                use Api\Models\Repositories\PostRepository;

                $result = PostRepository::getPostsForAdmin();
                foreach ($result

                         as $value) { ?>
                    <tr>
                        <td><?= $value['id'] ?></td>
                        <td><?= $value['user_id'] ?></td>
                        <td><?= $value['title'] ?></td>
                        <td><?= $value['content'] ?></td>
                        <td><?= $value['image'] ?></td>
                        <td><?= $value['date'] ?></td>
                        <td>
                            <a href="?edit=<?= $value['id'] ?>" class="btn btn-success btn-sm" data-toggle="modal"
                               data-target="#editModal<?= $value['id'] ?>"><i class="fa fa-edit"></i></a>
                            <a href="?delete=<?= $value['id'] ?>" class="btn btn-danger btn-sm" data-toggle="modal"
                               data-target="#deleteModal<?= $value['id'] ?>"><i class="fa fa-trash"></i></a>
                            <!-- Modal Edit-->
                            <div class="modal fade" id="editModal<?= $value['id'] ?>" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content shadow">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit post
                                                № <?= $value['id'] ?>?</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/admin/editPost" method="post">
                                                <div class="form-group">
                                                    <label>
                                                        Id
                                                        <input name="id" type="text" class="form-control"
                                                               value="<?= $value['id'] ?>" readonly>
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
                                                        Title
                                                        <input name="title" type="text" class="form-control"
                                                               value="<?= $value['title'] ?>">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Content
                                                        <textarea name="content" type="text" class="form-control"
                                                        ><?= $value['content'] ?></textarea>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Image
                                                        <input name="image" type="text" class="form-control"
                                                               value="<?= $value['image'] ?>">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Date
                                                        <input name="date" type="text" class="form-control"
                                                               value="<?= $value['date'] ?>">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Message to user
                                                        <textarea name="message" type="text"
                                                                  class="form-control"></textarea>
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
                            <div class="modal fade" id="deleteModal<?= $value['id'] ?>" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content shadow">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Delete post
                                                ID № <?= $value['id'] ?>?</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/admin/deletePost" method="post">
                                                <div class="form-group">
                                                    <label>
                                                        Post id
                                                        <input name="id" type="text" class="form-control"
                                                               value="<?= $value['id'] ?>" readonly>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        User id
                                                        <input name="user_id" type="text" class="form-control"
                                                               value="<?= $value['user_id'] ?>" readonly>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Message to user
                                                        <textarea name="message" type="text"
                                                                  class="form-control"></textarea>
                                                    </label>
                                                </div>
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
