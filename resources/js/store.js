import {getLoggedinUser} from './auth'

const user = getLoggedinUser();
export default {
    state:{
        welcome:"Welcome to my App",
        currentUser:user,
        auth_error:null,
        registeredUser: null,
        reg_error:null,
        accounts:[]
    },
    getters:{
        welcome(state){
            return state.welcome;
        },
        currentUser(state){
            return state.currentUser
        },
        authError(state){
            return state.auth_error
        },
        isLogged(state){
            return state.isLogged
        },
        regError(state){
            return state.reg_error;
        },
        registeredUser(state){
            return state.registeredUser;
        },
        accounts(state){
            return state.accounts
        },

    },
    mutations:{
        login(state) {
            state.auth_error = null;
        },
        loginSuccess(state,payload){
            state.auth_error = null
            state.currentUser = Object.assign({},payload.user,{token:payload.token})
            localStorage.setItem('user',JSON.stringify(state.currentUser))
        },
        loginFailed(state,payload){
            state.auth_error = payload.error
        },
        regFailed(state,payload){
            state.reg_error = payload.error
        },
        regSuccess(state,payload){
            state.reg_error = null
            state.registeredUser = payload.user;
        },
        logout(state){
            state.isLogged = false
            localStorage.removeItem('user')
            state.currentUser = null
        },
        updateAccounts(state,payload){
            state.accounts = payload
        }
    },
    actions:{
        login(context){
            context.commit('login')
        },
        getAccounts(context){
            axios.get('/api/account',{
                headers:{
                    'Authorization':`Bearer ${context.state.currentUser.token}`
                }
            })
                .then((response) => {
                    context.commit('updateAccounts', response.data.data);
                })
        }
    }
};
