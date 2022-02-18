import {createStore} from 'vuex'
import axios from 'axios'
// import router from '../router'
import {GETUSER_URL} from '../variables'
export default createStore(
    {
        state: {
            authenticated: '',
            role: '',
            user_name: '',
            user_id: ''
        },
        mutations: {
            setAuthentication (state, payload){
                state.authenticated = 1
                state.role = payload.role
                state.user_id = payload.user_id
                state.user_name = payload.user_name
            },
            logout(state){
                state.authenticated = ''
                state.role = ''
                state.user_id = ''
            },
            resetAuthentication(state, payload){
                state.authenticated = 1
                state.role = payload.role
                state.user_id = payload.user_id
                state.user_name = payload.user_name
            }
        },
        actions: {
            setRole(){
                axios.get(GETUSER_URL,
                    {withCredentials: true},
                    // {'token': state.authenticated}
                    ).catch(function(error){
                        console.log(error)
                        return
                    })
                    .then(response=>{
                        if (response.data.role){
                            this.commit('resetAuthentication', response.data)
                        }
                        
                    })
                    
            },
        },
        getters:{
            updatedUser(state){
                return state.user_id
            }
        },
        modules: {

        }
    }
)
