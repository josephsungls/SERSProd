<template>
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="contact-info col-lg-6 d-none d-lg-block">
                <img src = "https://img77.uenicdn.com/image/upload/v1578156938/business/business-builder/8a4b5875-8d01-4d3d-827d-065fc1bda737.png"/>
                <p>Email: ECCord@smu.edu.sg</p>
            </div>
            <div class="contact-form col-lg-6 col-sm-12">
                <p>You can contact us using the form below</p>
                <form>
                    <div class="form-group">
                        <label for="name">Name *</label>
                        <input type="text" class="form-control" id="name" placeholder="Name" v-model="name">
                        
                        <label for="email" class="mt-3">Email *</label>
                        <input type="text" class="form-control" id="email" placeholder="Email@smu.edu.sg" v-model="email">

                        <label for="message" class="mt-3">Message *</label>
                        <textarea class="form-control" rows="10" id="message" placeholder="Message... 🖊️" v-model="message"></textarea>
                    </div>
                    <button type="button" class="submit-btn btn btn-primary" @click="sendContact()" v-if="!loading">Submit</button>
                    <button class="submit-btn btn btn-primary" type="button" disabled v-else>
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        Sending...
                    </button>
                </form>
                <div class="d-lg-none d-block">
                    <p>Email: ECCord@smu.edu.sg</p>
                </div>
            </div>

        </div>

    </div>
</template>
<script>
    import Swal from 'sweetalert2'
    import axios from 'axios'
    import {CONTACT_URL} from '../variables'
	export default {
        data(){
            return{
                loading: false,
                name: '',
                email: '',
                message: ''
            }
        },
        methods:{
            sendContact(){
                var ref = this
                this.loading = true
                const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                if (this.name == '' || !re.test(this.email.toLowerCase()) || this.message == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Please make sure you filled in all fields and entered a correct email format.',
                        showConfirmButton: true,
                        
                    })
                    this.loading = false
                    return
                }
                axios.post(CONTACT_URL,
                {
                    'name': this.name,
                    'email': this.email,
                    'message': this.message
                }).catch(function(error){
                    console.log(error)
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong, please try again later',
                        showConfirmButton: false,
                        timer: 1000
                        
                    }).then(
                        ref.loading=false
                    )
                }).then(response=>{
                    
                    if (response.data == 'sent'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Sent!',
                            showConfirmButton: false,
                            timer: 1000
                        })
                        this.loading = false
                        this.name = ''
                        this.email = ''
                        this.message = ''
                    }
                })
            }
        },
		name: "ContactForm",
		components: {
        },
	};
</script>
<style scoped>
    .container-fluid{
        max-width: 1800px;
        width: 90%;
    }

    .row p {
        color: var(--dark-gray);
        font-size: 1.2em;
        font-weight: bold;
    }
    .contact-form{
        max-width: 800px;
        width: 100%;
    }

    .form-control{
        font-weight: bold;
    }
    .submit-btn{
        background-color: var(--button-color);
        border: none;
        font-weight: bold;
        width: 100%;
    }
    .submit-btn:hover{
        background-color: var(--button-color-hover);
    }
    .contact-info img{
        max-width: 700px;
        width: 100%;
    }

</style>