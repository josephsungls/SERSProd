<template>
    <div class="container-fluid  display-container">
        <span class='back'>
            <button type='button' @click="goBack()"><i class="fas fa-long-arrow-alt-left"></i></button>
        </span>
        <div class="table-container">
            <div class="table-header">
                <h4><i class="fas fa-edit"></i> Add A Certificate</h4>
                <br/>
            </div>
            <div class="form-wrapper">
                <form>
                    <div class="form-group">
                        <label for="cert-title">Certificate Title<b>*</b></label>
                        <input type="text" class="form-control" id="cert-title" aria-describedby="cert-title" placeholder="Certficate Title" v-model="title">
                    </div>               
                    <div class="form-group">
                        <label for="cert-issuer">Issuing Organisation<b>*</b></label>
                        <input type="text" class="form-control" id="cert-issuer" aria-describedby="cert-issuer" placeholder="Issuing Orgnisation" v-model="organisation">
                    </div>
                    <div class="form-group">
                        <label for="cert-credential">Certificate Credential (ID)<b>*</b></label>
                        <input type="text" class="form-control" id="cert-credential" aria-describedby="cert-credential" placeholder="Certificate Credential (ID)" v-model="cert_id">
                    </div>
                    <div class="form-group">
                        <label for="cert-url">Certificate URL</label>
                        <input type="text" class="form-control" id="cert-url" aria-describedby="cert-url" placeholder="Certificate URL (E-Cert)" v-model="cert_url"> 
                    </div>
                    <div class="form-group">
                        <label for="issuing-date">Date of Issue</label>
                        <input type="date" class="form-control" id="issuing-date" aria-describedby="issuing-date" v-model="issue">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="expire" v-model="expiry_bool">
                        <label class="form-check-label" for="expire">Does Certificate has expiry date?</label>
                    </div>
                    <div class="form-group" v-if="expiry_bool">
                        <label for="expiry-date">Expiry Date</label>
                        <input type="date" class="form-control" id="expiry-date" aria-describedby="expiry-date" v-model="expiry">
                    </div>
                     <button type="button" class="btn btn-primary mt-3" @click="UploadCert()">Upload</button> 
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import {UPLOADCERTIFICATE_URL} from '../../../variables'
    export default{
        name: 'AddCert',
        data(){
            return{
                title:'',
                organisation:'',
                cert_id: '',
                cert_url: '',
                issue: '',
                expiry_bool:false,
                expiry: ''
            }
        },
        methods:{
            goBack(){
                this.$parent.back()
            },
            UploadCert(){
                if (this.title == ''){
                    alert('title cannot be empty')
                }else if (this.organisation == ''){
                    alert('issuing organisation cannot be empty')
                }else if (this.cert_id == ''){
                    alert('certificate id cannot be empty')
                }else if (this.issue == ''){
                    alert('please fill in date of issue')
                }else if (this.expiry_bool && this.expiry == ''){
                    alert('please fill in expiry date')
                }
                console.log(
                    this.title,
                    this.organisation,
                    this.cert_id,
                    this.cert_url,
                    this.issue,
                    this.expiry_bool,
                    this.expiry
                )
                axios.post(UPLOADCERTIFICATE_URL,{
                    'user_id': this.$store.state.user_id,
                    'title': this.title,
                    'organisation': this.organisation,
                    'cert_id': this.cert_id,
                    'cert_url': this.cert_url,
                    'issue': this.issue,
                    'expiry_bool': this.expiry_bool,
                    'expiry': this.expiry
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data == 'created'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Certification uploaded',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.goBack()
                    }
                })  
            }
        }
    }
</script>
<style scoped>
    .form-wrapper form{
        width: 80%;
        margin:auto;
        margin-bottom: 5em;
    }
</style>