<template>
    <div  v-if="plan">
            <table class="table">
                <tr>
                    <th>ID</th>
                    <td>{{ plan.id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ plan.name }}</td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td>{{ plan.price }}</td>
                </tr>
            </table>
            <router-link to="/plan">Back to all plans</router-link>
            <div v-if="errors.length" class="alert alert-danger">
                <b>Please fix following errors</b>
                <ul>
                    <strong><li v-for="(error,index) in errors" :key="index">{{ error }}</li></strong>
                </ul>
            </div>
    </div>
</template>

<script>
    export default {
        name: 'show',
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
                plan: [],
                errors:[],
            };
        },
        // methods:{
        //     sendInvitation: function(e){
        //         this.errors = [];
        //         if (!this.member_email) {
        //             this.errors.push('Member Email required.');
        //         } else if (!this.validEmail(this.member_email)) {
        //             this.errors.push('Please provide valid email address.');
        //         }
        //         if (!this.errors.length) {
        //             axios.post(`/api/plan/sendInvitation`,{'member_email':this.member_email,'acc_id':this.plan.id},{
        //                 headers:{
        //                     'Authorization':`Bearer ${this.currentUser.token}`
        //                 },
        //
        //             })
        //             .then((response) => {
        //                 this.invitation = true
        //             });
        //         }
        //
        //         e.preventDefault();
        //     },
        //     validEmail: function (email) {
        //         var reg = /[^@]+@[^\.]+\..+/g;
        //         return reg.test(email);
        //     }
        // },

    }
</script>
<style scoped>

    .plan-view {
        display: flex;
        align-items: center;
    }

</style>
