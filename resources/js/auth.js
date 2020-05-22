
/*
Function for getting Auth User after Login
*/
export function login(credentials){

    return new Promise((resolve,reject)=>{
        axios.post('/api/auth/login', credentials)
            .then(response => {
                if(response.data.code === 200){
                    resolve(response.data)
                }else {
                    reject(response.data);
                }
            })
    })
}
/*
    Function for getting  User after Registration
*/
export function register(credentials){

    return new Promise((resolve,reject)=>{
        axios.post('/api/auth/register', credentials)
            .then(response => {
                resolve(response.data);
            })
            .catch(err => {
                reject('Something wrong ,try again.')
            })
    })
}

/*
    Function for saving Auth User in Front End
*/
export function getLoggedinUser(){
    const userStr = localStorage.getItem('user');

    if(!userStr){
        return null
    }

    return JSON.parse(userStr);
}
