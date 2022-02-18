<template>
    <div class="container-fluid">
        <form>
            <div class="form-group">

                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" placeholder="Name" v-model="register.name">

                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email"  placeholder="Enter email" v-model="register.email">
                <small id="emailHelp" class="form-text text-muted">Please enter the school's email address</small>

                <label for="company">Company</label>
                <input type="text" class="form-control" id="company" placeholder="Company" v-model="register.company">

                <label for="designation">Designation</label>
                <input type="text" class="form-control" id="designation" placeholder="Designation" v-model="register.designation">

                <label for="contact">Contact number</label>
                <input type="number" class="form-control" id="contact" placeholder="Contact Number" v-model="register.contact">
                <div class="custom-control custom-switch mt-2">
                    <input type="checkbox" class="custom-control-input" id="alumni-check" v-model="register.alumni">

                    <label class="custom-control-label" for="alumni-check">Alumni?</label>
                </div>

            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Password" v-model="register.password">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm-password" placeholder="Password" v-model="register.confirmPassword">
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-primary sign-up" @click="submit()">SIGN UP</button>
            </div>
        </form>
    </div>
</template>
<script>
    import {sha256} from 'js-sha256'
    import axios from 'axios'
    import {REGISTERACCOUNTEXTERNAL_URL, LOGIN_URL} from '../variables'
    import router from '../router'
    export default{
        name:"ExternalRegister",
        data(){
            return{
                register:{
                    name: '',
                    email: '',
                    company: '',
                    designation:'',
                    contact: '',
                    alumni: false,
                    password: '',
                    confirmPassword: ''

                }
            }
        },
        methods:{
            submit(){
                if (this.register.password != this.register.confirmPassword || !this.validateEmail(this.register.email)){
                    alert('wrong wron wrongggggg!!!!')
                }else{
                    this.register.email = this.register.email.toLowerCase()
                    axios.post(REGISTERACCOUNTEXTERNAL_URL,{
                        'user_name': this.register.name,
                        'user_email': this.register.email,
                        'company': this.register.company,
                        'designation': this.register.designation,
                        'contact': this.register.contact,
                        'alumni': this.register.alumni,
                        'user_password': this.register.password,
                        'user_role': 5,
                    }).catch(function(error){
                        console.log(error)
                        return
                    })
                    .then(response=>(
                        console.log(response),
                        this.login(this.register.email, this.register.password)
                    ))
                }
                
            },
            validateEmail(email) {
                const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                
                return re.test(String(email).toLowerCase());
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
        width: 40%;
        margin:auto;
    }
</style>