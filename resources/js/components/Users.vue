<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Users Table</h3>
                        <div class="card-tools">
                            <button class="btn btn-success" @click="newUserModal">
                                Add New <i class="fas fa-user-plus"></i>
                            </button>
                        </div>
                        <div class="input-group input-group-sm float-right" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search" @keyup="$parent.searchIt" v-model="$parent.search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default" @click="$parent.searchIt"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Registered At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users.data" :key="user.id" v-if="users.data.length > 0">
                                    <td>{{user.id}}</td>
                                    <td>{{user.name}}</td>
                                    <td>{{user.email}}</td>
                                    <td>{{user.type | uppercase}}</td>
                                    <td>{{user.created_at | formatDate}}</td>
                                    <td>
                                        <a href="#" @click="editUserModal(user)">
                                            <i class="fas fa-edit blue"></i>
                                        </a>
                                        /
                                        <a href="#" @click="deleteUser(user.id)">
                                            <i class="fas fa-trash red"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-center" v-if="users.data.length == 0">No Entries</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="users" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <div v-if="!$gate.isAdminOrAuthor()">
            <not-found></not-found>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="add-new" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editMode" id="exampleModalLabel">Add New User</h5>
                        <h5 class="modal-title" v-show="editMode" id="exampleModalLabel">Edit User Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" placeholder="Name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.email" type="email" name="email" placeholder="Email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <textarea v-model="form.bio" name="bio" id="bio" placeholder="Short Bio for User (Optional)" class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }">
                            </textarea>
                                <has-error :form="form" field="bio"></has-error>
                            </div>
                            <div class="form-group">
                                <select name="type" v-model="form.type" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                    <option value="" disabled>Select User Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                    <option value="author">Author</option>
                                </select>
                                <has-error :form="form" field="type"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.password" type="password" name="password" placeholder="Password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            editMode: false,
            users: {},
            form: new Form({
                id: '',
                name: '',
                email: '',
                password: '',
                type: '',
                bio: '',
                photo: '',
            })
        }
    },
    methods: {
        getResults(page = 1) {
            axios.get('api/user?page=' + page)
                .then(response => {
                    this.users = response.data;
                });
        },
        loadUsers() {
            if (this.$gate.isAdminOrAuthor()) {
                axios.get("api/user").then(({ data }) => (this.users = data)); // load user table
            }
        },
        newUserModal() {
            this.editMode = false;
            this.form.reset();
            $('#add-new').modal('show');
        },
        editUserModal(user) {
            this.editMode = true;
            this.form.reset();
            $('#add-new').modal('show');
            this.form.fill(user);
            

        },
        // crud functions
        createUser() {
            this.$Progress.start();
            this.form.post('api/user')
                .then(() => {
                    Fire.$emit('refreshTableOnCrUD');
                    $('#add-new').modal('hide')

                    toast.fire({
                        icon: 'success',
                        title: 'User Created Successfully'
                    })
                    this.$Progress.finish();
                })
                .catch((e) => {
                    if (e.response.status == 403) {
                        swal.fire("Forbidden!", "Action is Unauthorized.", "error");
                    }
                    this.$Progress.fail();
                })
        },
        updateUser() {
            this.$Progress.start();
            this.form.put('api/user/' + this.form.id)
                .then(() => {
                    this.$Progress.finish();
                    swal.fire(
                        'Updated!',
                        'User Information has been Updated.',
                        'success'
                    )
                    Fire.$emit('refreshTableOnCrUD')
                })
                .catch((e) => {
                    if (e.response.status == 403) {
                        swal.fire("Forbidden!", "Action is Unauthorized.", "error");
                    }
                    this.$Progress.fail();
                });
        },
        deleteUser(id) {
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                //Send request to the server
                if (result.value) {
                    this.form.delete("api/user/" + id)
                        .then(() => {
                            swal.fire(
                                'Deleted!',
                                'User has been Deleted.',
                                'success'
                            )
                            Fire.$emit('refreshTableOnCrUD')
                        })
                        .catch((e) => {
                            console.log(e.response);
                            if (e.response.status == 403) {
                                swal.fire("Forbidden!", "Action is Unauthorized.", "error");
                            }
                        });
                }
            })
        }
    },
    created() {
        Fire.$on('searching', () => {
            let query = this.$parent.search;
            axios.get('api/findUser?q=' + query)
                .then((searched) => {
                    this.users = searched.data;
                })
                .catch(() => {

                });
        });
        this.loadUsers();
        Fire.$on('refreshTableOnCrUD', () => { this.loadUsers() });
        // setInterval(() => this.loadUsers(),5000);
    }
}

</script>
