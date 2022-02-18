<template>
    <div class="container-fluid">
        <form>
            <div class="form-group">
                <div class="text-center">
                    <div class="form-check form-check-inline text-center">
                        
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="student-radio" value="1" checked v-model="register.type">
                        <label class="form-check-label" for="student-radio">Student</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="instructor-radio" value="5" v-model="register.type">
                        <label class="form-check-label" for="instructor-radio">Instructor</label>
                    </div>
                </div>
                <br/>
                <label for="smu-id">SMU's ID</label>
                <input type="number" class="form-control" id="smu-id" placeholder="SMU's ID" v-model="register.id">
                <small id="ID" class="form-text text-muted" v-if="register.id.length != 8">Please input your 8 digit ID (01234567)</small>
                <small id="ID" class="form-text field-ok" v-else>ID OK!</small>

                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" placeholder="Name" v-model="register.name">
                <small id="name" class="form-text text-muted" v-if="register.name.length == 0">Please input your name</small>
                <small id="name" class="form-text field-ok" v-else>Name Ok!</small>

                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email"  placeholder="Enter email" v-model="register.email">
                <small id="emailHelp" class="form-text text-muted" v-if="register.email.indexOf('@smu.edu.sg') == -1">Please enter the school's email address (@smu.edu.sg)</small>
                <small id="emailHelp" class="form-text field-ok" v-else>Email Ok!</small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Password" v-model="register.password">

                <small id="pwHelp" class="form-text text-muted" v-if="register.password.length < 8">At least 8 characters </small>
                <small id="pwHelp" class="form-text field-ok" v-else>Length Ok!</small>

                <small id="pwHelp" class="form-text text-muted" v-if="(register.password.toUpperCase() === register.password) || register.password.length == 0 || register.password.toLowerCase() === register.password">Mixture of uppercase and lowercase letters</small>
                <small id="pwHelp" class="form-text field-ok" v-else>Letter cases Ok!</small>

                <small id="pwHelp" class="form-text text-muted" v-if="!/.*?(?:[a-z].*?[0-9]|[0-9].*?[a-z]).*?/.test(register.password)">Mixture of letters and numbers</small>
                <small id="pwHelp" class="form-text field-ok" v-else>Mixture Ok!</small>

                <small id="pwHelp" class="form-text text-muted mb-3" v-if="!/(?=.*[@$!%*#?&\`\'])/.test(register.password)">Include atleast one special character</small>
                <small id="pwHelp" class="form-text field-ok mb-3" v-else>Special character included!</small>

                <label for="confirm-password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm-password" placeholder="Password" v-model="register.confirmPassword">
                <small id="pwCfm" class="form-text text-muted mb-3" v-if="register.password !== register.confirmPassword || register.confirmPassword.length === 0">Please confirm your password</small>
                <small id="pwCfm" class="form-text field-ok mb-3" v-else>Passwords match</small>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-primary sign-up" @click="submit()">SIGN UP</button>
            </div>
        </form>
    </div>
</template>
<script>
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import {sha256} from 'js-sha256'
    import {REGISTERACCOUNTSMU_URL, LOGIN_URL} from '../variables'
    import router from '../router'
    export default{
        name: "SMURegister",
        data(){
            return{
                register:{
                    type: '1',
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    confirmPassword: ''
                }
            }
        },
        methods:{
            submit(){
                if (this.register.password != this.register.confirmPassword 
                || this.register.id.toString().length != 8 
                || this.register.name.length === 0
                || this.register.email.indexOf('@smu.edu.sg') == -1
                || this.register.password.length < 8
                || this.register.password.toUpperCase() === this.register.password 
                || this.register.password.toLowerCase() === this.register.password
                ||!/.*?(?:[a-z].*?[0-9]|[0-9].*?[a-z]).*?/.test(this.register.password
                ||!/(?=.*[@$!%*#?&`'])/.test(this.register.password))
                || this.register.password !== this.register.confirmPassword 
                || this.register.confirmPassword.length === 0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration failed',
                        text: 'Please ensure the fields are entered correctly!',
                        })
                }else{
                    this.register.email = this.register.email.toLowerCase()
                    axios.post(REGISTERACCOUNTSMU_URL,{
                        'user_id': this.register.id,
                        'user_name': this.register.name,
                        'user_email': this.register.email,
                        'user_password': sha256(this.register.password),
                        'user_role': this.register.type,
                    }).catch(function(error){
                        console.log(error)
                        return
                    })
                    .then(response=>{
                        
                        if (response.data=== "id exists"){
                            Swal.fire({
                                icon: 'error',
                                title: 'ID already exists',
                                })
                        }else if (response.data === "email exists"){
                            Swal.fire({
                                icon: 'error',
                                title: 'Email already exists',
                                })
                        }else{
                            this.login(this.register.email, this.register.password)
                        }
                    })
                }
                    
            },

            login(email, password){
                axios.post(LOGIN_URL,{
                    'email': email,
                    'pw': sha256(password),   
                },
                {withCredentials: true}
                ).catch(function(error){
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data._token){  
                        // console.log(response)
                        this.$store.commit('setAuthentication', response.data)
                        console.log(this.$store)
                        if (response.data.role == 5){
                            router.push('/pending')
                        }else{
                            router.push('/')
                        }
                    }else{
                        this.attempt = response.data
                    }
                })
            }
            
        }
    }
</script>
<style scoped>
    .form-control{
        background-color: var(--lighter-gray);
        border-radius: 15px;
    }
    .sign-up{
        border-radius: 20px;
        width: 150px;
        font-family: var(--body-text);
        font-weight: bold;
    }
    form{
        max-width:800px;
        width: 80%;
        margin:auto;
    }
    .field-ok{
        color: var(--pill-green);
    }
</style>