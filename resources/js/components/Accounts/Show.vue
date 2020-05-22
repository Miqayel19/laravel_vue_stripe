<template>
    <div  v-if="account">
            <table class="table">
                <tr>
                    <th>ID</th>
                    <td>{{ account.id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ account.name }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ account.phone }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>{{ account.address }}</td>
                </tr>
                <tr>
                    <th>Country</th>
                    <td>{{ account.country }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ account.status }}</td>
                </tr>
                <tr>
                    <th>Stripe Customer</th>
                    <td>{{ account.stripe_customer_id }}</td>
                </tr>
                <tr>
                    <th>Currency</th>
                    <td>{{ account.currency }}</td>
                </tr>
            </table>
            <router-link to="/account">Back to all accounts</router-link>
            <div v-if="errors.length" class="alert alert-danger">
                <b>Please fix following errors</b>
                <ul>
                    <strong><li v-for="(error,index) in errors" :key="index">{{ error }}</li></strong>
                </ul>
            </div>
            <div class="alert alert-success"  v-if="invitation" >
                <b>Your invitation has been successfully sent.</b>
            </div>
            <form @submit.prevent="sendInvitation">
                <input type="email" placeholder="Member email" v-model="member_email"/>
                <input type="submit" value="Send Invitation Link" class="btn btn-primary">
            </form>
    </div>
</template>

<script>
    export default {
        name: 'show',
        mounted() {
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
        },
        data() {
            return {
                account: [],
                member_email:'',
                errors:[],
                invitation:false
            };
        },
        methods:{
            sendInvitation: function(e){
                this.errors = [];
                if (!this.member_email) {
                    this.errors.push('Member Email required.');
                } else if (!this.validEmail(this.member_email)) {
                    this.errors.push('Please provide valid email address.');
                }
                if (!this.errors.length) {
                    axios.post(`/api/account/sendInvitation`,{'member_email':this.member_email,'acc_id':this.account.id},{
                        headers:{
                            'Authorization':`Bearer ${this.currentUser.token}`
                        },
                    })
                    .then((response) => {
                        this.invitation = true
                    });
                }

                e.preventDefault();
            },
            validEmail: function (email) {
                var reg = /[^@]+@[^\.]+\..+/g;
                return reg.test(email);
            }
        },

    }
</script>
<style scoped>

    .account-view {
        display: flex;
        align-items: center;
    }

</style>
