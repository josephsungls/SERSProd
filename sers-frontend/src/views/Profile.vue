<template>
    <div v-if="access">
        <div class="profile-card text-center">
            <h4>Hi, {{profile.User_Name}}</h4>
            <h6><b>ID: </b>{{profile.User_ID}}</h6>
            <h6><b>Email: </b>{{profile.User_Email}}</h6>
            <button type="button" class="btn btn-primary mt-3" @click="show = !show">Change Password?</button>

        </div>
        <div class="profile-card animate__animated animate__fadeIn" v-if="show">
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Password" v-model="password">

                <small id="pwHelp" class="form-text text-muted" v-if="password.length < 8">At least 8 characters </small>
                <small id="pwHelp" class="form-text field-ok" v-else>Length Ok!</small>

                <small id="pwHelp" class="form-text text-muted" v-if="(password.toUpperCase() === password) || password.length == 0 || password.toLowerCase() === password">Mixture of uppercase and lowercase letters</small>
                <small id="pwHelp" class="form-text field-ok" v-else>Letter cases Ok!</small>

                <small id="pwHelp" class="form-text text-muted" v-if="!/.*?(?:[a-z].*?[0-9]|[0-9].*?[a-z]).*?/.test(password)">Mixture of letters and numbers</small>
                <small id="pwHelp" class="form-text field-ok" v-else>Mixture Ok!</small>

                <small id="pwHelp" class="form-text text-muted mb-3" v-if="!/(?=.*[@$!%*#?&\`\'])/.test(password)">Include atleast one special character</small>
                <small id="pwHelp" class="form-text field-ok mb-3" v-else>Special character included!</small>

                <label for="confirm-password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm-password" placeholder="Password" v-model="confirmPassword">
                <small id="pwCfm" class="form-text text-muted mb-3" v-if="password !== confirmPassword || confirmPassword.length === 0">Please confirm your password</small>
                <small id="pwCfm" class="form-text field-ok mb-3" v-else>Passwords match</small>
                <div class="text-center">
                    <button type="button" class="btn btn-primary mt-3" @click="changePassword()">Change!</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {sha256} from 'js-sha256'
    import {GETUSERPROFILE_URL, CHANGEPASSWORD_URL} from '../variables'
    import router from '../router'
    import Swal from 'sweetalert2'
    import axios from 'axios'

    export default{
        data(){
            return {
                access: false,
                profile: {},
                show: false,
                password: '',
                confirmPassword: ''
            }
        },
        methods:{
            getUserProfile(){
                axios.get(GETUSERPROFILE_URL,
                {withCredentials: true}
                ).then(response=>{
                    if (response.data == 'no rights'){
                        Swal.fire({
                            icon: 'error',
                            title: 'You do not have access rights to this page',
                            showConfirmButton: false,
                            timer: 1000
                        })
                        router.push('/')
                    }
                    else{
                        
                        this.access = true
                        this.profile = response.data
                    }
                })
            },
            changePassword(){
                axios.post(CHANGEPASSWORD_URL,
                {'new_password': sha256(this.confirmPassword)},
                {withCredentials: true}
                ).then(response=>{
                    if (response.data == 'updated'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Password successfully changed!',
                            showConfirmButton: false,
                            timer: 1000
                        })
                        this.password = ''
                        this.confirmPassword = ''
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong, please try again later.',
                            showConfirmButton: false,
                            timer: 1000
                        })
                    }
                })
            }
        },
        mounted(){
            this.getUserProfile()
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
        background-color: white;
        border-radius: 15px;
    }
    .form-group{
        width:80%;
        margin:auto;
    }
    .field-ok{
        color: var(--pill-green);
    }
</style>