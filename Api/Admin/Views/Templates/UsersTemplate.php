<div style="overflow:scroll">
    <div class="row">
        <div class="col mt-1">
            <table class="table shadow ">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Login</th>
                    <th>Password</th>
                    <th>Salt</th>
                    <th>Cookie</th>
                    <th>Avatar</th>
                    <th>Rating</th>
                    <th>Pass_reset_code</th>
                    <th>Ban_timeout</th>
                    <th>is_editor</th>
                    <th>Actions</th>
                </tr>
                <?php

                use Api\Models\Repositories\UserRepository;

                $result = UserRepository::getUsersForAdmin();
                foreach ($result as $value) { ?>
                    <tr>
                        <td><?= $value['id'] ?></td>
                        <td><?= $value['name'] ?></td>
                        <td><?= $value['login'] ?></td>
                        <td><?= $value['password'] ?></td>
                        <td><?= $value['salt'] ?></td>
                        <td><?= $value['cookie'] ?></td>
                        <td><?= $value['avatar'] ?></td>
                        <td><?= $value['rating'] ?></td>
                        <td><?= $value['pass_reset_code'] ?></td>
                        <td><?= $value['ban_timeout'] ?></td>
                        <td><?= $value['is_editor'] ?></td>
                        <td>
                            <a href="?edit=<?= $value['id'] ?>" class="btn btn-success btn-sm" data-toggle="modal"
                               data-target="#editModal<?= $value['id'] ?>"><i class="fa fa-edit"></i></a>
                            <a href="?delete=<?= $value['id'] ?>" class="btn btn-danger btn-sm" data-toggle="modal"
                               data-target="#deleteModal<?= $value['id'] ?>"><i class="fa fa-trash"></i></a>
                            <a href="?ban=<?= $value['id'] ?>" class="btn btn-danger btn-sm" data-toggle="modal"
                               data-target="#banModal<?= $value['id'] ?>"><i class="fa fa-ban"></i></a>


                            <!-- Modal Edit-->
                            <div class="modal fade" id="editModal<?= $value['id'] ?>" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content shadow">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit user
                                                № <?= $value['id'] ?>?</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/admin/editUser" method="post">
                                                <div class="form-group">
                                                    <label>
                                                        User id
                                                        <input name="id" type="text" class="form-control"
                                                               value="<?= $value['id'] ?>" readonly>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Key login
                                                        <input name="keyLogin" type="text" class="form-control"
                                                               value="<?= $value['login'] ?>" readonly>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Name
                                                        <input name="name" type="text" class="form-control"
                                                               value="<?= $value['name'] ?>">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Login
                                                        <input name="login" type="text" class="form-control"
                                                               value="<?= $value['login'] ?>">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Password
                                                        <input name="password" type="text" class="form-control"
                                                               value="<?= $value['password'] ?>">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Salt
                                                        <input name="salt" type="text" class="form-control"
                                                               value="<?= $value['salt'] ?>">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Cookie
                                                        <input name="cookie" type="text" class="form-control"
                                                               value="<?= $value['cookie'] ?>">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Avatar
                                                        <input name="avatar" type="text" class="form-control"
                                                               value="<?= $value['avatar'] ?>">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Rating
                                                        <input name="rating" type="text" class="form-control"
                                                               value="<?= $value['rating'] ?>">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Password reset code
                                                        <input name="pass_reset_code" type="text" class="form-control"
                                                               value="<?= $value['pass_reset_code'] ?>">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Ban timeout
                                                        <input name="pass_reset_code" type="text" class="form-control"
                                                               value="<?= $value['ban_timeout'] ?>" readonly>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Is Editor
                                                        <input name="is_editor" type="text" class="form-control"
                                                               value="<?= $value['is_editor'] ?>">
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
                                            <h5 class="modal-title" id="exampleModalLabel">Delete user
                                                ID № <?= $value['id'] ?>?</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/admin/deleteUser" method="post">
                                                <div class="form-group">
                                                    <label>
                                                        User id
                                                        <input name="id" type="text" class="form-control"
                                                               value="<?= $value['id'] ?>" readonly>
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

                            <!-- Modal Ban-->
                            <div class="modal fade" id="banModal<?= $value['id'] ?>" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content shadow">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Ban user
                                                ID № <?= $value['id'] ?>?</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/admin/banUser" method="post">
                                                <div class="form-group">
                                                    <label>
                                                        User id
                                                        <input name="id" type="text" class="form-control"
                                                               value="<?= $value['id'] ?>" readonly>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Ban time in hours
                                                        <input name="ban_time" type="number"
                                                               class="form-control">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Message to user
                                                        <textarea name="message" type="text"
                                                                  class="form-control"></textarea>
                                                    </label>
                                                </div>
                                                <button type="submit" name="ban_submit" class="btn btn-danger">
                                                    Ban
                                                </button>
                                            </form>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/admin/unbanUser" method="post">
                                                <input name="id" type="text" class="form-control"
                                                       value="<?= $value['id'] ?>" hidden>
                                                <button type="submit" name="unban_submit" class="btn btn-danger">
                                                    Unban
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

