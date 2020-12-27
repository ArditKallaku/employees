<div class="container">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white form-wrapper">
            <div class="container">
                <form method="POST">
                    <div class="form-group">
                        <label for="email">Name</label>
                        <input class="form-control" type="text" name="dep_name" id="dep_name" value="">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Parent Department</label>
                        <select class="form-control" name="parent" id="parentD" value="0">
                            <option value="0">No parent</option>
                            <?php foreach($all_dep as $row ): ?>;
                                <option value="<?= $row['id'] ?>"><?= $row['description'] ?></option>
                            <?php endforeach; ?>;
                        </select>
                    </div> 
                    <?php if (isset($validation)): ?>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <button type="submit" name='add' class="btn btn-primary">Add New</button>
                </form>
            </div>
        </div>
    </div>
</div>