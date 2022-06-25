<template>
    <div class="card mb-4">
        <div class="card-header">Manage Roles</div>

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
                        <tr v-for="item in roles" :key="item.id">
                            <td>{{ item.name }}</td>
                            <td>{{ item.description }}</td>
                            <td>action</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Input role name" v-model="form.name">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Input role description" v-model="form.description">
                                </div>
                            </td>
                            <td>
                                <div class="form-group d-flex justify-content-center">
                                    <button class="btn btn-primary btn-sm" @click="submit">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
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
                }
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
                    xy.roles.push(response.data.data);
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    }
</script>
