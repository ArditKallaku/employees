<div class="container" id="depContainer">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>Departments</h1>
            <button class="btn btn-primary" onclick="location.href='/department/new'" type="button">New Department</button>
            <button class="btn btn-primary" onclick="location.href='/employee/new'" type="button">New Employee</button>
        </div>
        <div class="panel-body">
            <div class="col-md-6">
                <div id="tree"></div>
            </div>
            <div class="col-md-6">
                <div id="selectedD" style="display:none">
                    <b id="selectedName"></b>
                    <span id="selectedId" style="display:none"></span>
                    <button class="btn btn-success" id="updateD">Modify</button>
                    <hr>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" id="employees">
                            <thead>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </thead>				
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/assets/scripts/admin.js"></script>