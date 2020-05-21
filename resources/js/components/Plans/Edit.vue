<template>
    <div class="plan-view">
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
                        <input type="text"  class="form-control" v-model="plan.name">
                    </td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td>
                        <input type="number" class="form-control" v-model="plan.price">
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
        mounted() {
            axios.get(`/api/plan/${this.$route.params.id}`,{
                headers:{
                    "Authorization":`Bearer ${this.currentUser.token}`
                }
            })
                .then((response) => {
                    this.plan = response.data.data
                });
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
        },
        data() {
            return {
                plan: {
                    name:'',
                    price:'',
                },
                errors: []
            };
        },
        methods:{
            updateForm: function (e) {

                if (!this.plan.name) {
                    this.errors.push ("Name required")
                }else if(this.plan.name.length < 3){
                    this.errors.push("Name must contains minimum 3 characters")
                }
                if (!this.errors.length) {
                    axios.put(`/api/plan/${this.$route.params.id}`, this.$data.plan,{
                        headers:{
                            'Authorization':`Bearer ${this.currentUser.token}`
                        }
                    })
                    .then((response) => {
                        this.$store.commit('updatePlans',response.data.data);
                        this.$router.push('/plan');
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
