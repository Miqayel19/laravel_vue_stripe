<template>
    <div class="account-view" v-if="account">
        <form @submit.prevent="updateForm">
            <div v-if="errors.length" class="alert alert-danger" >
                <b>Please fix following errors</b>
                <ul>
                    <strong><li v-for="(error,index) in errors" :key="index">{{ error }}</li></strong>
                </ul>
                </div>
            <table class="table table-bordered">
                <tr>
                    <th>Name</th>
                    <td>
                        <input type="text"  class="form-control" v-model="account.name">
                    </td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>
                        <input type="text" class="form-control" v-model="account.phone">
                    </td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>
                        <input type="text" class="form-control" v-model="account.address">
                    </td>
                </tr>
                <tr>
                    <th>Country</th>
                    <td>
                        <input type="text" class="form-control" v-model="account.country">
                    </td>
                </tr>
                <tr>
                    <th>Action</th>
                    <td>
                        <input type="submit" value="Update" class="btn btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'edit',
        created() {
            axios.get(`/api/account/${this.$route.params.id}`,{
                headers:{
                    "Authorization":`Bearer ${this.currentUser.token}`
                }
            })
                .then((response) => {
                    this.account = response.data.data
                });

        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
            accounts() {
                return this.$store.getters.accounts;
            }
        },
        data() {
            return {
                account: {
                    name:'',
                    phone:'',
                    address:'',
                    country:'',
                },
                errors: []
            };
        },
        methods:{

            updateForm: function (e) {

                if (!this.account.name) {
                    this.errors.push ("Name required")
                }
                if (!this.account.phone) {
                    this.errors.push("Phone required")
                }
                if (!this.account.country) {
                    this.errors.push("Country required")
                }
                if (!this.account.address) {
                    this.errors.push("Address required")
                }

                if (!this.errors.length) {
                    axios.put(`/api/account/${this.$route.params.id}`, this.$data.account,{
                        headers:{
                            'Authorization':`Bearer ${this.currentUser.token}`
                        }
                    })
                    .then((response) => {
                        this.$store.commit('updateAccounts',response.data.data);
                        this.$router.push('/account');
                    });
                }
                e.preventDefault();
            },
        }
    }
</script>
<style scoped>

    .table th{
        vertical-align:middle
    }

</style>
