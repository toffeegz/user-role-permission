<template>
    <div>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <span>Manage Roles</span>
                <div class="form-group d-flex justify-content-center">
                    <button class="btn btn-primary btn-sm" @click="openNewRoleModal()">
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
                                <th scope="col">Description</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item,index) in roles" :key="item.id">
                                <td>{{ item.name }}</td>
                                <td>{{ item.description }}</td>
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
        
        <!-- Edit Role Modal -->
        <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Role</h5>
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
                        <div>
                            <div class="form-group mb-2">
                                <label for="">Name</label>
                                <input type="text" class="form-control" placeholder="Input role name" v-model="formEdit.name">
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <input type="text" class="form-control" placeholder="Input role description" v-model="formEdit.description">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeEditRoleModal()" >Close</button>
                        <button type="button" class="btn btn-primary" @click="update()">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Role Modal -->
        <div class="modal fade" id="newRoleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Role</h5>
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
                        <!-- new role modal body -->
                        <div>
                            <div class="form-group mb-2">
                                <label for="">Name</label>
                                <input type="text" class="form-control" v-model="form.name">
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <input type="text" class="form-control" v-model="form.description">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="submit()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                roles: null,
                form: {
                    name: null,
                    description: null,
                },
                formEdit: {
                    name: null,
                    description: null,
                },
                selectedId: null,
                error_status: null,
                error_messages: null,
            }
        },
        mounted() {
            axios
            .get('/api/roles')
            // .then(response => (console.log(response.data)));
            .then(response => (this.roles = response.data));
        },
        methods: {
            submit() {
                const xy = this;
                axios.post('/api/roles', this.form)
                .then(function (response) {
                    $('#newRoleModal').modal('hide');
                    xy.roles.push(response.data.data);
                    xy.form.name = null;
                    xy.form.description = null;
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
                axios.delete(`/api/roles/${item.id}`)
                .then(function (response) {
                    xy.roles.splice(index,1);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            edit(item) {
                const xy = this;
                $('#editRoleModal').modal('show');
                xy.formEdit.name = item.name;
                xy.formEdit.description = item.description;
                xy.selectedId = item.id;
            },
            update() {
                const xy = this;
                axios.put(`api/roles/${xy.selectedId}`, this.formEdit)
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
            openNewRoleModal () {
                const xy = this;
                xy.form.name = null;
                xy.form.description = null;
                $('#newRoleModal').modal('show');
            },
            closeEditRoleModal() {
                const xy = this;
                $('#editRoleModal').modal('hide');
                xy.error_messages = null;
                xy.error_status = null;
            }
        }
    }
</script>
