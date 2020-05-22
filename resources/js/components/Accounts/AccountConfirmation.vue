<template>
    <div>
        <div class="alert alert-success"  v-if="status">
            <b>{{message}}</b>
        </div>
        <div v-else>
            <div class="alert alert-danger">
                <b>{{message}}</b>
            </div>
            <form @submit.prevent="generate" novalidate="true">
                <input type="hidden" v-model="this.$route.params.token"/>
                <div class="form-group">
                    <input type="submit" value='Generate New Account Token'/>
                </div>
            </form>
        </div>
    </div>
</template>

<script>

    export default {
        name: "AccountConfirmation",

        mounted() {
            let token = this.$route.params.token;
            let id = this.$route.params.id;
            axios.post(`/api/account/account_confirmation/${token}/${id}`,{},{
                headers:{
                    "Authorization":`Bearer ${this.currentUser.token}`,
                }
            }).then(res => {
                if(res.data.data.code === 200){
                    this.$store.commit("logout");
                    this.message  = res.data.data.message;
                    this.status = true;
                } else if(res.data.data.code === 400){
                    this.message  = res.data.data.error;
                    this.status = false
                }
            }).catch(err => {});
        },
        computed:{
            currentUser(){
                return this.$store.getters.currentUser;
            }
        },
        data() {
            return {
                message: null,
                status:false
            };
        },
        methods: {
            generate:function(e) {
                e.preventDefault();

                axios.post('/api/auth/generate',{'token' : this.$route.params.token}).then(res => {
                    this.message  = res.data.data.message;
                    if(res.data.data.code === 200){
                        this.status = true
                    }
                }).catch(err => {});

            },


        }
    }
</script>

<style scoped>

</style>
