<template>
    <div>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <span>Manage Users</span>
                <div class="form-group d-flex justify-content-center">
                    <button class="btn btn-primary btn-sm"  data-bs-toggle="modal" data-bs-target="#newUserModal">
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
        
        <!-- Modal -->
        <div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" action="#">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <div class="form-group mb-2">
                                <label for="">Full Name</label>
                                <input type="text" class="form-control" placeholder="Input full name" v-model="form.name">
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Email</label>
                                <input type="email" class="form-control" placeholder="Input role description" v-model="form.email">
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Assign Role</label>
                                <select class="form-control" v-model="form.role_id" >
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="submit">Save changes</button>
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
                selectedId: null,
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
                    console.log(error);
                });
            }
        }
    }
</script>
