<template>
    <div>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <span>Manage Users</span>
                <div class="form-group d-flex justify-content-center">
                    <button class="btn btn-primary btn-sm" @click="openNewUserModal()">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item,index) in users" :key="item.id">
                                <td>{{ item.name }}</td>
                                <td>{{ item.email }}</td>
                                <td>{{ item.role_name }}</td>
                                <!-- <td>{{ item.role.name }}</td> -->
                                <td class="">
                                    <a class="" href="#" @click="edit(item)">
                                        <i class="fa-solid fa-pen text-secondary"></i>
                                    </a>
                                    <a class="" href="#" @click="remove(item,index)">
                                        <i class="fa-solid fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- New User Modal -->
        <div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" action="#">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- alert -->
                        <div class=" alert alert-danger" role="alert" v-if="error_messages">
                            <p class="text-danger">{{ error_status }}</p>
                            <div class="text-danger" v-for="error_message in error_messages">
                                <small><i>{{ error_message[0] }}</i></small> 
                            </div>
                        </div>

                        <!-- new user form -->
                        <div>
                            <div class="form-group mb-2">
                                <label for="">Full Name</label>
                                <input type="text" class="form-control" v-model="form.name">
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Email</label>
                                <input type="email" class="form-control" v-model="form.email">
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Assign Role</label>
                                <select class="form-control" v-model="form.role_id">
                                    <option v-for="(item,index) in roles" v-bind:value="item.id">{{ item.name }}</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Password</label>
                                <input type="password" class="form-control" v-model="form.password">
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Confirm Password</label>
                                <input type="password" class="form-control" v-model="form.password_confirmation">
                            </div>
                        </div>
                    </div>
                    <!-- modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" action="#">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- alert -->
                        <div class=" alert alert-danger" role="alert" v-if="error_messages">
                            <p class="text-danger">{{ error_status }}</p>
                            <div class="text-danger" v-for="error_message in error_messages">
                                <small><i>{{ error_message[0] }}</i></small> 
                            </div>
                        </div>

                        <!-- edit user form -->
                        <div>
                            <div class="form-group mb-2">
                                <label for="">Full Name</label>
                                <input type="text" class="form-control" placeholder="Input full name" v-model="formEdit.name">
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Email</label>
                                <input type="email" class="form-control" placeholder="Input role description" v-model="formEdit.email">
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Assign Role</label>
                                <select class="form-control" v-model="formEdit.role_id">
                                    <option v-for="(item,index) in roles" v-bind:value="item.id">{{ item.name }}</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="">New Password</label>
                                <input type="password" class="form-control" v-model="formEdit.password">
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Confirm Password</label>
                                <input type="password" class="form-control" v-model="formEdit.password_confirmation">
                            </div>
                        </div>
                    </div>
                    <!-- modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeEditUserModal()">Close</button>
                        <button type="button" class="btn btn-primary" @click="update()">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { $dataMetaSchema } from 'ajv';

    export default {
        data () {
            return {
                users: null,
                roles: null,
                form: {
                    name: null,
                    email: null,
                    role_id: null,
                    password: null,
                    password_confirmation: null,
                },
                formEdit: {
                    id: null,
                    name: null,
                    email: null,
                    role_id: null,
                    password: null,
                    password_confirmation: null,
                },
                selectedId: null,
                error_status: null,
                error_messages: null,
            }
        },
        mounted() {
            axios
            .get('/api/users')
            // .then(response => (console.log(response.data)));
            .then(response => (this.users = response.data));

            axios
            .get('/api/roles')
            // .then(response => (console.log(response.data)));
            .then(response => (this.roles = response.data));
        },
        methods: {
            submit() {
                const xy = this;
                axios.post('/api/users', this.form)
                .then(function (response) {
                    xy.users.push(response.data.data);
                    $('#newUserModal').modal('hide');
                    xy.form.name = null;
                    xy.form.email = null;
                    xy.form.role_id = null;
                    xy.form.password = null;
                    xy.form.password_confirmation = null;
                })
                .catch(function (error) {
                    console.log(error.response);
                    if (error.response) {
                        xy.error_messages = error.response.data.errors;
                        xy.error_status = error.response.data.message;
                    }
                });
            },
            remove(item,index) {
                const xy = this;
                axios.delete(`/api/users/${item.id}`)
                .then(function (response) {
                    // xy.users.splice(index,1);
                    location.reload();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            edit(item) {
                const xy = this;
                $('#editUserModal').modal('show');
                xy.formEdit.id = item.id;
                xy.formEdit.name = item.name;
                xy.formEdit.email = item.email;
                xy.formEdit.role_id = item.role_id;
                xy.formEdit.password = null;
                xy.formEdit.password_confirmation = null;
                xy.selectedId = item.id;
            },
            update() {
                const xy = this;
                axios.put(`api/users/${xy.selectedId}`, this.formEdit)
                .then(function (response) {
                    alert(response.data.message);
                    location.reload();
                })
                .catch(function (error) {
                    console.log(error.response);
                    if (error.response) {
                        xy.error_messages = error.response.data.errors;
                        xy.error_status = error.response.data.message;
                    }
                });
            },
            openNewUserModal () {
                const xy = this;
                xy.form.email = null;
                xy.form.password = null;
                $('#newUserModal').modal('show');
            },
            closeEditUserModal() {
                const xy = this;
                $('#editUserModal').modal('hide');
                xy.error_messages = null;
                xy.error_status = null;
            }
        }
    }
</script>
