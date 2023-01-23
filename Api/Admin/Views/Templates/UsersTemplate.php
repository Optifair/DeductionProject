<div class="container">
    <div class="row">
        <div class="col mt-1">
            <table class="table shadow ">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Login</th>
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
                        <td>
                            <a class="btn btn-danger btn-sm" data-toggle="modal"
                               data-target="#banModal
                            <?= $value['id'] ?>"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr> <?php } ?>
                </thead>
            </table>
        </div>
    </div>
</div>