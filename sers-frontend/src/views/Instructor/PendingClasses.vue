<template>
<div class="container-fluid" v-if="access">
    <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.action">
        <PendingAction/>
    </div>
    <div class = "animate__animated animate__fadeIn animate__faster" v-else>
        <IndividualCourse :selectedCourse="current"/>
    </div>
</div>
</template>
<script>
    import PendingAction from '../../components/Instructor/PendingAction'
    import IndividualCourse from '../../components/Instructor/IndividualCourse'
    import axios from 'axios'
    import {GETUSERROLE_URL} from '../../variables.js'
    import Swal from 'sweetalert2'
    import router from '../../router.js'
    export default{
        name: "PendingClasses",
        components: {
            PendingAction,
            IndividualCourse
        },
        data(){
            return{
                tabs:{
                    action: true,
                    individual:false,
                    
                },
                current: {},
                access: false
            }
        },
        methods:{
            individualCourse(){
                this.tabs.action = false
                this.tabs.individual = true
            },
            checkUserRole(){
                axios.get(GETUSERROLE_URL,
                {withCredentials: true}                
                ).then(response=>{
                    
                    if (response.data != 3){
                        this.redirectToCourse()
                        

                    }else{
                        
                        this.access = true
  
                        
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
            },
            back(){
                this.tabs.action = true
                this.tabs.indivudal = false
            }
        },
        mounted(){
            this.checkUserRole()
        }
    }
</script>
<style scoped>

</style>