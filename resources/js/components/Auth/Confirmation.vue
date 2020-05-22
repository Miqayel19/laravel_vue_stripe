<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
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
                            <input type="submit" value='Generate New Token'/>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "Confirmation",
        beforeMount() {
            let token = this.$route.params.token;
            axios.post(`/api/auth/confirmation/${token}`).then(res => {
                this.message  = res.data.data.message;
                if(res.data.data.code === 200){
                    this.status = true;
                } else if(res.data.data.code === 400){
                    this.status = false
                }
            }).catch(err => {});
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
                    this.message  = res.data.data.error;
                    if(res.data.data.code === 200){
                        this.status = true
                    }
                }).catch(err => {});
            },
        }
    }
</script>
