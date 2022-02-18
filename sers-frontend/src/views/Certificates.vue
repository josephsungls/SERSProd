<template>
    <div class ="container-fluid display-container" v-if="access">
        <div class = "table-container animate__animated animate__fadeIn animate__faster">
            <div class="table-header">
                <h4>Certificates</h4>
                <p class = "text-muted mb-5">Certificates attained by students</p>
            </div>
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Certificate Name</th>
                        <th scope = "col">Issued By</th>
                        <th scope = "col"># of Students attained</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="cert in Allcertificates" :key="cert">
                        <td>{{cert.Certificate_Name}}</td>
                        <td>{{cert.Certified_By}}</td>
                        <td>{{cert.No_Attained}}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>
<script>
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import router from '../router'
    import {GETALLCERTIFICATES_URL, GETUSERROLE_URL} from '../variables'
    export default {
        name: "Certificates",
        data(){
            return{
                Allcertificates: [],
                access: false,
            }
        },
        methods:{
            getAllCertificates(){
                axios.get(GETALLCERTIFICATES_URL,
                {
                    withCredentials: true
                }).then(response=>{
                    this.Allcertificates = response.data
                })
            },
            checkUserRole(){
                axios.get(GETUSERROLE_URL,
                {withCredentials: true}
                ).then(response=>{
                    if (response.data !=2 && response.data!=4){
                        this.redirectToCourse()
                    }else{
                        this.access = true
                        this.getAllCertificates()
                    }
                })
            },
            redirectToCourse(){
                let timerInterval
                Swal.fire({
                icon: 'error',
                title: 'Access denied!',
                html: 'You do not have the access rights! Redirecting...',
                timer: 1000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
                })
                router.push('/courses')
            }
        },
        mounted(){
            this.checkUserRole()
        }
    }
</script>
<style scoped>
.table-container{
    margin-top: 7em;
}
</style>