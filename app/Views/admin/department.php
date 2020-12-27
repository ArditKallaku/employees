<!-- Modal to confirm deletion of department -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirm deletion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        This department has sub-departments. Are you sure you want to delete?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="javascript: deleteAll(<?= $department['id'] ?>);">Delete</button>
      </div>
    </div>
  </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white form-wrapper">
            <div class="container">
                <form method="POST">
                    <div class="form-group">
                        <label for="email">Name</label>
                        <input class="form-control" type="text" name="name" id="dep_name" value="<?= set_value('dep_name', $department['description']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Parent Department</label>
                        <select class="form-control" name="parent" id="parentD" value="<?= set_value('parent', $department['parent_dept']) ?>">
                            <option>No parent</option>
                        <?php 
                            foreach($all_dep as $row ){
                                if($row['id'] == $department['parent_dept']){
                                    echo "<option value='".$row['id']."' selected>".$row['description']."</option>";
                                }
                                else{
                                    echo "<option value='".$row['id']."' >".$row['description']."</option>";
                                }
                            }
                        ?>
                        </select>
                    </div> 
                
                    <?php if (isset($validation)): ?>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->get('success')): ?>
                        <div class="alert alert-success" role="alert">
                        <?= session()->get('success') ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" name='update' class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-danger" onclick="javascript: deleteD(<?= $department['id'] ?>);">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/assets/scripts/department.js"></script>