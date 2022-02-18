<template>
    <div class="profile-card animate__animated animate__fadeIn">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" placeholder="Email@smu.edu.sg" v-model="email">
            <small class="form-text text-muted">john.doe@smu.edu.sg</small>
            <div class="text-center">
                <button type="button" class="btn btn-primary mt-3" @click="resetPassword()" v-if="!loading">Reset</button>

                <button class="btn btn-primary mt-3" type="button" disabled v-else>
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div>

        </div>
    </div>
</template>
<script>
    import Swal from 'sweetalert2'
    import router from '../router'
    import axios from 'axios'
    import {RESETPASSWORD_URL,} from '../variables'
    export default {
        data(){
            return{
                email: '',
                loading: false,
            }
        },
        methods:{
            resetPassword(){
                var ref = this
                this.loading = true
                axios.post(RESETPASSWORD_URL, 
                {
                    'email': this.email
                }).catch(function(error){
                    console.error(error)
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong, please try again later',
                        showConfirmButton: false,
                        timer: 1000
                        
                    }).then(
                        ref.loading=false
                    )
                    
                })
                .then(response=>{
                    console.log(response)
                    if (response.data == 'updated'){
                        Swal.fire({
                            icon: 'success',
                            title: 'New password sent to the email',
                            showConfirmButton: false,
                            timer: 1000
                        })
                        router.push('/login')
                    }else if (response.data == 'do not exist'){
                        Swal.fire({
                            icon: 'error',
                            title: 'Email dooes not exist',
                            showConfirmButton: false,
                            timer: 1000
                        })
                    }
                    this.loading = false
                })
            }
        }
    }
</script>
<style scoped>
    .profile-card{
        box-shadow: 3px 3px 15px rgba(0,0,0,0.2);
        margin:auto;
        max-width: 600px;
        margin-top:7em;
        padding-top: 1em;
        padding-bottom: 1em;
        /* background-color: pink; */
        border-radius: 15px;

    }
    .form-group{
        width:80%;
        margin:auto;
    }
    .red{
        color: var(--pill-red);
    }
</style>